<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
use App\Events\MessageSent;
use App\Models\AdminConsultationRead;
use App\Models\Consultation;
use App\Models\ConsultationArchiveCopyRequest;
use App\Models\Message;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminInboxController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderInbox($request);
    }

    public function show(
        Request $request,
        Consultation $consultation
    ): View {
        $this->markConsultationRead($consultation);

        return $this->renderInbox(
            $request,
            $consultation
        );
    }

    public function conversation(
        Request $request,
        Consultation $consultation
    ): JsonResponse {
        $this->markConsultationRead($consultation);

        $consultation->load([
            'messages' => fn ($query) =>
                $query->with('classificationNotice')->oldest('id'),
            'lastMessage',
            'classificationLogs.admin',
            'classificationLogs.notice',
            'classificationScreenings.admin',
            'consultationOutcomes.admin',
        ]);

        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );

        $state = $this->resolveInboxState(
            $consultation
        );

        $consultation->setAttribute(
            'inbox_state',
            $state['key']
        );

        $consultation->setAttribute(
            'inbox_state_label',
            $state['label']
        );

        return response()->json([
            'conversationHtml' => view(
                'admin.inbox.partials.conversation-panel',
                compact('consultation', 'timezone')
            )->render(),
            'patientHtml' => view(
                'admin.inbox.partials.patient-panel',
                compact('consultation', 'timezone')
            )->render(),
            'publicId' => $consultation->public_id,
            'pageTitle' =>
                $consultation->nama.' · Inbox MD Farma',
            'readState' => $this->buildCounts(),
        ]);
    }

    public function liveData(Request $request): JsonResponse
    {
        $activePublicId = trim(
            (string) $request->query('active', '')
        );

        $data = $this->buildListData(
            $request,
            $activePublicId
        );

        return response()->json([
            'listHtml' => view(
                'admin.inbox.partials.conversation-list',
                $data
            )->render(),
            'paginationHtml' => view(
                'admin.inbox.partials.pagination',
                [
                    'consultations' =>
                        $data['consultations'],
                ]
            )->render(),
            'counts' => $data['counts'],
            'syncedAt' => CarbonImmutable::now(
                $data['timezone']
            )->format('H.i.s').' WIB',
        ]);
    }

    public function markRead(
        Consultation $consultation
    ): JsonResponse {
        $this->markConsultationRead($consultation);

        return response()->json([
            'success' => true,
            'counts' => $this->buildCounts(),
        ]);
    }

    public function updateClassification(
        Request $request,
        Consultation $consultation
    ): JsonResponse|\Illuminate\Http\RedirectResponse {
        $validated = $request->validate([
            'service_classification' => [
                'required',
                'string',
                Rule::in(array_keys(
                    Consultation::serviceClassificationOptions()
                )),
            ],
            'classification_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'send_classification_notice' => [
                'nullable',
                'boolean',
            ],
        ]);

        $adminId = (int) Auth::guard('admin')->id();
        $sendNotice = (bool) (
            $validated['send_classification_notice'] ?? false
        );

        $result = DB::transaction(
            function () use (
                $consultation,
                $validated,
                $adminId,
                $sendNotice
            ): array {
                $lockedConsultation = Consultation::query()
                    ->lockForUpdate()
                    ->findOrFail($consultation->getKey());

                $previousClassification =
                    $lockedConsultation->service_classification;

                $newClassification =
                    $validated['service_classification'];

                $reason = trim((string) (
                    $validated['classification_reason'] ?? ''
                ));

                if (
                    $previousClassification !== null
                    && $previousClassification !== $newClassification
                    && $reason === ''
                ) {
                    throw ValidationException::withMessages([
                        'classification_reason' =>
                            'Alasan wajib diisi ketika kategori diubah.',
                    ]);
                }

                if ($previousClassification === $newClassification) {
                    $lockedConsultation->load([
                        'classificationLogs.admin',
                        'classificationLogs.notice',
                        'classificationScreenings.admin',
                        'consultationOutcomes.admin',
                    ]);

                    return [
                        'consultation' => $lockedConsultation,
                        'changed' => false,
                        'previous' => $previousClassification,
                        'notice_message' => null,
                    ];
                }

                $lockedConsultation->forceFill([
                    'service_classification' =>
                        $newClassification,
                    'classified_by_admin_id' => $adminId,
                    'classified_at' => now(),
                ])->save();

                $classificationLog =
                    $lockedConsultation->classificationLogs()->create([
                        'admin_id' => $adminId,
                        'previous_classification' =>
                            $previousClassification,
                        'new_classification' => $newClassification,
                        'reason' => $reason !== '' ? $reason : null,
                    ]);

                $noticeMessage = null;

                if (
                    $sendNotice
                    && $lockedConsultation->status !== 'aktif'
                ) {
                    throw ValidationException::withMessages([
                        'send_classification_notice' =>
                            'Aktifkan kembali konsultasi sebelum mengirim pemberitahuan.',
                    ]);
                }

                if ($sendNotice) {
                    $template = Consultation::classificationNoticeTemplate(
                        $newClassification
                    );

                    if ($template === null) {
                        throw ValidationException::withMessages([
                            'send_classification_notice' =>
                                'Template pemberitahuan belum tersedia.',
                        ]);
                    }

                    $noticeMessage = $lockedConsultation
                        ->messages()
                        ->create([
                            'sender' => 'admin',
                            'message' => $template['message'],
                            'image' => null,
                        ]);

                    $consultationChanges = [
                        'last_message_at' =>
                            $noticeMessage->created_at,
                        'last_message_sender' => 'admin',
                    ];

                    if (! $lockedConsultation->first_admin_reply_at) {
                        $consultationChanges['first_admin_reply_at'] =
                            $noticeMessage->created_at;
                    }

                    $lockedConsultation
                        ->forceFill($consultationChanges)
                        ->save();

                    $classificationNotice =
                        $lockedConsultation
                            ->classificationNotices()
                            ->create([
                                'classification_log_id' =>
                                    $classificationLog->id,
                                'message_id' => $noticeMessage->id,
                                'admin_id' => $adminId,
                                'template_code' => $template['code'],
                                'service_classification' =>
                                    $newClassification,
                                'content_snapshot' =>
                                    $template['message'],
                                'sent_at' =>
                                    $noticeMessage->created_at,
                            ]);

                    $noticeMessage->setRelation(
                        'classificationNotice',
                        $classificationNotice
                    );
                }

                $lockedConsultation->load([
                    'classificationLogs.admin',
                    'classificationLogs.notice',
                    'classificationScreenings.admin',
                    'consultationOutcomes.admin',
                ]);

                return [
                    'consultation' => $lockedConsultation,
                    'changed' => true,
                    'previous' => $previousClassification,
                    'notice_message' => $noticeMessage,
                ];
            }
        );

        /** @var Consultation $updatedConsultation */
        $updatedConsultation = $result['consultation'];

        /** @var Message|null $noticeMessage */
        $noticeMessage = $result['notice_message'];

        $previousLabel = $result['previous']
            ? (Consultation::SERVICE_CLASSIFICATIONS[
                $result['previous']
            ] ?? $result['previous'])
            : null;

        if (! $result['changed']) {
            $message = 'Klasifikasi tidak berubah.';
        } elseif ($previousLabel) {
            $message = 'Klasifikasi diubah dari '
                .$previousLabel.' menjadi '
                .$updatedConsultation
                    ->serviceClassificationLabel().'.';
        } else {
            $message = 'Klasifikasi awal disimpan sebagai '
                .$updatedConsultation
                    ->serviceClassificationLabel().'.';
        }

        $noticePayload = null;
        $realtimeDelivered = null;

        if ($noticeMessage) {
            [$noticePayload, $realtimeDelivered] =
                $this->broadcastClassificationNotice(
                    $updatedConsultation,
                    $noticeMessage
                );

            $message .= ' Pemberitahuan klasifikasi dikirim kepada pasien.';
        }

        if ($request->expectsJson()) {
            $timezone = config(
                'analytics.timezone',
                'Asia/Jakarta'
            );

            return response()->json([
                'success' => true,
                'changed' => $result['changed'],
                'message' => $message,
                'classification' =>
                    $updatedConsultation->service_classification,
                'classificationLabel' =>
                    $updatedConsultation
                        ->serviceClassificationLabel(),
                'classifiedAt' => $updatedConsultation
                    ->classified_at
                    ?->toIso8601String(),
                'noticeSent' => $noticeMessage !== null,
                'noticeMessage' => $noticePayload,
                'realtimeDelivered' => $realtimeDelivered,
                'screeningComplete' => $updatedConsultation
                    ->screeningProgress()['is_complete'],
                'screeningLabel' => $updatedConsultation
                    ->screeningProgress()['label'],
                'screeningClass' => $updatedConsultation
                    ->screeningProgress()['class'],
                'screeningHtml' => view(
                    'admin.inbox.partials.screening-panel',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
                'outcomeComplete' => $updatedConsultation
                    ->outcomeProgress()['is_complete'],
                'outcomeLabel' => $updatedConsultation
                    ->outcomeProgress()['label'],
                'outcomeClass' => $updatedConsultation
                    ->outcomeProgress()['class'],
                'outcomeHtml' => view(
                    'admin.inbox.partials.outcome-panel',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
                'historyHtml' => view(
                    'admin.inbox.partials.classification-history',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function updateScreening(
        Request $request,
        Consultation $consultation
    ): JsonResponse|\Illuminate\Http\RedirectResponse {
        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $adminId = (int) Auth::guard('admin')->id();

        $updatedConsultation = DB::transaction(
            function () use (
                $consultation,
                $validated,
                $adminId
            ): Consultation {
                $lockedConsultation = Consultation::query()
                    ->lockForUpdate()
                    ->findOrFail($consultation->getKey());

                $classification =
                    $lockedConsultation->service_classification;

                $template = Consultation::screeningTemplate(
                    $classification
                );

                if ($template === null) {
                    throw ValidationException::withMessages([
                        'service_classification' =>
                            'Tetapkan klasifikasi pelayanan sebelum mengisi skrining.',
                    ]);
                }

                $allowedKeys = array_keys($template['items']);
                $submittedAnswers = (array) (
                    $validated['answers'] ?? []
                );

                $answers = [];

                foreach ($allowedKeys as $key) {
                    $answers[$key] = array_key_exists(
                        $key,
                        $submittedAnswers
                    );
                }

                $requiredCount = count($allowedKeys);
                $completedCount = count(
                    array_filter($answers)
                );

                $notes = trim((string) (
                    $validated['notes'] ?? ''
                ));

                $notesRequired = (bool) (
                    $template['notes_required'] ?? false
                );

                $isComplete = $requiredCount > 0
                    && $completedCount === $requiredCount
                    && (! $notesRequired || $notes !== '');

                $classificationLog =
                    $lockedConsultation
                        ->classificationLogs()
                        ->where(
                            'new_classification',
                            $classification
                        )
                        ->first();

                $lockedConsultation
                    ->classificationScreenings()
                    ->create([
                        'classification_log_id' =>
                            $classificationLog?->id,
                        'admin_id' => $adminId,
                        'service_classification' =>
                            $classification,
                        'answers' => $answers,
                        'notes' => $notes !== '' ? $notes : null,
                        'required_count' => $requiredCount,
                        'completed_count' => $completedCount,
                        'is_complete' => $isComplete,
                        'completed_at' => $isComplete
                            ? now()
                            : null,
                    ]);

                $lockedConsultation->load([
                    'classificationLogs.admin',
                    'classificationLogs.notice',
                    'classificationScreenings.admin',
                    'consultationOutcomes.admin',
                ]);

                return $lockedConsultation;
            }
        );

        $progress = $updatedConsultation
            ->screeningProgress();

        if ($progress['is_complete']) {
            $message = 'Skrining lengkap dan tersimpan.';
        } elseif (
            $progress['completed'] === $progress['required']
            && $progress['notes_required']
            && ! $progress['notes_complete']
        ) {
            $message = 'Checklist tersimpan. Isi catatan wajib agar skrining dinyatakan lengkap.';
        } else {
            $message = 'Progres skrining tersimpan ('
                .$progress['completed'].'/'
                .$progress['required'].').';
        }

        if ($request->expectsJson()) {
            $timezone = config(
                'analytics.timezone',
                'Asia/Jakarta'
            );

            return response()->json([
                'success' => true,
                'message' => $message,
                'screeningComplete' =>
                    $progress['is_complete'],
                'screeningLabel' => $progress['label'],
                'screeningClass' => $progress['class'],
                'screeningHtml' => view(
                    'admin.inbox.partials.screening-panel',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
                'outcomeComplete' => $updatedConsultation
                    ->outcomeProgress()['is_complete'],
                'outcomeLabel' => $updatedConsultation
                    ->outcomeProgress()['label'],
                'outcomeClass' => $updatedConsultation
                    ->outcomeProgress()['class'],
                'outcomeHtml' => view(
                    'admin.inbox.partials.outcome-panel',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
            ]);
        }

        return back()->with('success', $message);
    }


    public function updateOutcome(
        Request $request,
        Consultation $consultation
    ): JsonResponse|\Illuminate\Http\RedirectResponse {
        $validated = $request->validate([
            'outcome_code' => [
                'required',
                'string',
                'max:60',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        $adminId = (int) Auth::guard('admin')->id();

        $result = DB::transaction(
            function () use (
                $consultation,
                $validated,
                $adminId
            ): array {
                $lockedConsultation = Consultation::query()
                    ->lockForUpdate()
                    ->findOrFail($consultation->getKey());

                $classification =
                    $lockedConsultation->service_classification;

                $template = Consultation::finalOutcomeTemplate(
                    $classification
                );

                if ($template === null) {
                    throw ValidationException::withMessages([
                        'service_classification' =>
                            'Tetapkan klasifikasi pelayanan sebelum menentukan hasil akhir.',
                    ]);
                }

                $screeningProgress = $lockedConsultation
                    ->screeningProgress();

                if (! $screeningProgress['is_complete']) {
                    throw ValidationException::withMessages([
                        'outcome_code' =>
                            'Lengkapi skrining sebelum menentukan hasil akhir pelayanan.',
                    ]);
                }

                $outcomeCode = (string) $validated['outcome_code'];
                $outcomeLabel = $template['options'][$outcomeCode]
                    ?? null;

                if ($outcomeLabel === null) {
                    throw ValidationException::withMessages([
                        'outcome_code' =>
                            'Hasil akhir tidak sesuai dengan klasifikasi pelayanan.',
                    ]);
                }

                $notes = trim((string) (
                    $validated['notes'] ?? ''
                ));

                if (
                    (bool) ($template['notes_required'] ?? false)
                    && $notes === ''
                ) {
                    throw ValidationException::withMessages([
                        'notes' =>
                            'Catatan hasil akhir wajib diisi untuk klasifikasi ini.',
                    ]);
                }

                $classificationLog =
                    $lockedConsultation
                        ->classificationLogs()
                        ->where(
                            'new_classification',
                            $classification
                        )
                        ->first();

                $currentScreening =
                    $lockedConsultation->currentScreening();

                if ($currentScreening === null) {
                    throw ValidationException::withMessages([
                        'outcome_code' =>
                            'Snapshot skrining aktif tidak ditemukan.',
                    ]);
                }

                $currentOutcome =
                    $lockedConsultation
                        ->consultationOutcomes()
                        ->where(
                            'service_classification',
                            $classification
                        )
                        ->where(
                            'screening_id',
                            $currentScreening->id
                        )
                        ->when(
                            $classificationLog !== null,
                            fn ($query) => $query->where(
                                'classification_log_id',
                                $classificationLog->id
                            ),
                            fn ($query) => $query->whereNull(
                                'classification_log_id'
                            )
                        )
                        ->first();

                if (
                    $currentOutcome
                    && $currentOutcome->outcome_code === $outcomeCode
                    && trim((string) $currentOutcome->notes) === $notes
                ) {
                    $lockedConsultation->load([
                        'classificationLogs.admin',
                        'classificationLogs.notice',
                        'classificationScreenings.admin',
                        'consultationOutcomes.admin',
                    ]);

                    return [
                        'consultation' => $lockedConsultation,
                        'changed' => false,
                    ];
                }

                $lockedConsultation
                    ->consultationOutcomes()
                    ->create([
                        'classification_log_id' =>
                            $classificationLog?->id,
                        'screening_id' => $currentScreening->id,
                        'admin_id' => $adminId,
                        'service_classification' =>
                            $classification,
                        'outcome_code' => $outcomeCode,
                        'outcome_label' => $outcomeLabel,
                        'notes' => $notes !== '' ? $notes : null,
                    ]);

                $lockedConsultation->load([
                    'classificationLogs.admin',
                    'classificationLogs.notice',
                    'classificationScreenings.admin',
                    'consultationOutcomes.admin',
                ]);

                return [
                    'consultation' => $lockedConsultation,
                    'changed' => true,
                ];
            }
        );

        /** @var Consultation $updatedConsultation */
        $updatedConsultation = $result['consultation'];

        $progress = $updatedConsultation->outcomeProgress();

        $message = $result['changed']
            ? 'Hasil akhir pelayanan tersimpan sebagai '
                .$progress['label'].'.'
            : 'Hasil akhir tidak berubah.';

        if ($request->expectsJson()) {
            $timezone = config(
                'analytics.timezone',
                'Asia/Jakarta'
            );

            return response()->json([
                'success' => true,
                'changed' => $result['changed'],
                'message' => $message,
                'outcomeComplete' => $progress['is_complete'],
                'outcomeLabel' => $progress['label'],
                'outcomeClass' => $progress['class'],
                'screeningComplete' => $updatedConsultation
                    ->screeningProgress()['is_complete'],
                'outcomeHtml' => view(
                    'admin.inbox.partials.outcome-panel',
                    [
                        'consultation' => $updatedConsultation,
                        'timezone' => $timezone,
                    ]
                )->render(),
            ]);
        }

        return back()->with('success', $message);
    }

    private function broadcastClassificationNotice(
        Consultation $consultation,
        Message $message
    ): array {
        $message->setRelation('consultation', $consultation);
        $event = new MessageSent($message);
        $payload = $event->broadcastWith();
        $broadcasted = true;

        try {
            event($event);
        } catch (Throwable $exception) {
            $broadcasted = false;

            Log::warning(
                'Pemberitahuan klasifikasi tersimpan, tetapi broadcast realtime gagal.',
                [
                    'consultation_id' => $consultation->id,
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }

        try {
            event(
                new AdminInboxActivity(
                    $consultation->fresh(['lastMessage']),
                    'admin_reply',
                    $message
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi inbox untuk pemberitahuan klasifikasi gagal.',
                [
                    'consultation_id' => $consultation->id,
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }

        try {
            event(
                new AdminDashboardActivity(
                    $consultation->fresh(),
                    'classification_notice',
                    $message
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi dashboard untuk pemberitahuan klasifikasi gagal.',
                [
                    'consultation_id' => $consultation->id,
                    'message_id' => $message->id,
                    'exception' => $exception::class,
                ]
            );
        }

        return [$payload, $broadcasted];
    }

    private function renderInbox(
        Request $request,
        ?Consultation $selected = null
    ): View {
        $activePublicId = $selected?->public_id ?? '';

        $data = $this->buildListData(
            $request,
            $activePublicId
        );

        if ($selected) {
            $selected->load([
                'messages' => fn ($query) =>
                    $query->with('classificationNotice')->oldest('id'),
                'lastMessage',
                'classificationLogs.admin',
                'classificationLogs.notice',
                'classificationScreenings.admin',
                'consultationOutcomes.admin',
            ]);

            $state = $this->resolveInboxState(
                $selected
            );

            $selected->setAttribute(
                'inbox_state',
                $state['key']
            );

            $selected->setAttribute(
                'inbox_state_label',
                $state['label']
            );
        }

        return view(
            'admin.inbox',
            [
                ...$data,
                'selectedConsultation' => $selected,
            ]
        );
    }

    private function buildListData(
        Request $request,
        string $activePublicId = ''
    ): array {
        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );

        $search = trim(
            (string) $request->query('search', '')
        );

        $state = (string) $request->query(
            'state',
            'all'
        );

        $type = (string) $request->query(
            'type',
            ''
        );

        $sort = (string) $request->query(
            'sort',
            'latest'
        );

        if (! in_array(
            $state,
            [
                'all',
                'unread',
                'new',
                'waiting_admin',
                'waiting_patient',
                'active',
                'completed',
            ],
            true
        )) {
            $state = 'all';
        }

        if (! in_array(
            $type,
            ['', 'resep', 'non_resep'],
            true
        )) {
            $type = '';
        }

        if (! in_array(
            $sort,
            [
                'latest',
                'oldest',
                'waiting_oldest',
            ],
            true
        )) {
            $sort = 'latest';
        }

        $adminId = (int) Auth::guard('admin')->id();
        $unreadSql = $this->unreadCountSql();

        $query = Consultation::query()
            ->select('consultations.*')
            ->selectRaw(
                $unreadSql.' AS unread_count',
                [$adminId]
            )
            ->with([
                'lastMessage' => function ($messageRelation): void {
                    $messageRelation->select([
                        'messages.id',
                        'messages.consultation_id',
                        'messages.sender',
                        'messages.message',
                        'messages.image',
                        'messages.created_at',
                    ]);
                },
            ])
            ->withCount('messages');

        $this->applyFilters(
            $query,
            $search,
            $state,
            $type,
            $unreadSql,
            $adminId
        );

        $this->applySort($query, $sort);

        $consultations = $query->paginate(
            30,
            ['*'],
            'inbox_page'
        );

        $consultations
            ->withPath(route('admin.inbox'))
            ->appends(
                $request->except('inbox_page')
            );

        foreach ($consultations as $item) {
            $this->decorateConsultation(
                $item,
                $timezone
            );
        }

        return [
            'timezone' => $timezone,
            'consultations' => $consultations,
            'counts' => $this->buildCounts(),
            'search' => $search,
            'state' => $state,
            'type' => $type,
            'sort' => $sort,
            'activePublicId' => $activePublicId,
        ];
    }

    private function applyFilters(
        Builder $query,
        string $search,
        string $state,
        string $type,
        string $unreadSql,
        int $adminId
    ): void {
        if ($search !== '') {
            $query->where(
                function (Builder $builder) use (
                    $search
                ): void {
                    $builder
                        ->where(
                            'nama',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'no_hp',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhereHas(
                            'messages',
                            fn (Builder $messageQuery) =>
                                $messageQuery->where(
                                    'message',
                                    'like',
                                    '%'.$search.'%'
                                )
                        );
                }
            );
        }

        if ($type !== '') {
            $query->where(
                'jenis_konsultasi',
                $type
            );
        }

        match ($state) {
            'unread' => $query->whereRaw(
                $unreadSql.' > 0',
                [$adminId]
            ),
            'new' => $query
                ->where('status', 'aktif')
                ->whereNull('last_message_at'),
            'waiting_admin' => $query
                ->where('status', 'aktif')
                ->where(
                    'last_message_sender',
                    'user'
                ),
            'waiting_patient' => $query
                ->where('status', 'aktif')
                ->where(
                    'last_message_sender',
                    'admin'
                ),
            'active' => $query->where(
                'status',
                'aktif'
            ),
            'completed' => $query->where(
                'status',
                'selesai'
            ),
            default => null,
        };
    }

    private function applySort(
        Builder $query,
        string $sort
    ): void {
        match ($sort) {
            'oldest' => $query->orderByRaw(
                'COALESCE(last_message_at, created_at) ASC'
            ),
            'waiting_oldest' => $query
                ->orderByRaw(
                    "CASE WHEN status = 'aktif' "
                    ."AND last_message_sender = 'user' "
                    .'THEN 0 ELSE 1 END ASC'
                )
                ->orderByRaw(
                    'COALESCE(last_message_at, created_at) ASC'
                ),
            default => $query->orderByRaw(
                'COALESCE(last_message_at, created_at) DESC'
            ),
        };
    }

    private function decorateConsultation(
        Consultation $consultation,
        string $timezone
    ): void {
        $state = $this->resolveInboxState(
            $consultation
        );

        $lastMessage = $consultation->lastMessage;
        $activityTime = $consultation->last_message_at
            ?? $consultation->created_at;

        $localTime = CarbonImmutable::instance(
            $activityTime
        )->setTimezone($timezone);

        $today = CarbonImmutable::now(
            $timezone
        )->startOfDay();

        $label = $localTime->isToday()
            ? $localTime->format('H.i')
            : ($localTime->isYesterday()
                ? 'Kemarin'
                : ($localTime->year === $today->year
                    ? $localTime
                        ->locale('id')
                        ->isoFormat('D MMM')
                    : $localTime->format('d/m/Y')));

        $preview = $lastMessage?->message
            ? Str::limit(
                preg_replace(
                    '/\s+/',
                    ' ',
                    trim($lastMessage->message)
                ),
                72
            )
            : ($lastMessage?->image
                ? '📎 Lampiran gambar'
                : 'Konsultasi baru — belum ada pesan');

        $consultation->setAttribute(
            'inbox_state',
            $state['key']
        );

        $consultation->setAttribute(
            'inbox_state_label',
            $state['label']
        );

        $consultation->setAttribute(
            'last_message_preview',
            $preview
        );

        $consultation->setAttribute(
            'last_activity_label',
            $label
        );

        $consultation->setAttribute(
            'last_activity_title',
            $localTime
                ->locale('id')
                ->isoFormat(
                    'dddd, D MMMM YYYY [pukul] HH.mm.ss'
                ).' WIB'
        );

        $consultation->setAttribute(
            'unread_count',
            (int) $consultation->unread_count
        );
    }

    private function resolveInboxState(
        Consultation $consultation
    ): array {
        if ($consultation->status === 'selesai') {
            return [
                'key' => 'completed',
                'label' => 'Selesai',
            ];
        }

        if (! $consultation->last_message_at) {
            return [
                'key' => 'new',
                'label' => 'Baru',
            ];
        }

        if (
            $consultation->last_message_sender
            === 'user'
        ) {
            return [
                'key' => 'waiting_admin',
                'label' => 'Menunggu Admin',
            ];
        }

        return [
            'key' => 'waiting_patient',
            'label' => 'Menunggu Pasien',
        ];
    }

    private function markConsultationRead(
        Consultation $consultation
    ): void {
        $adminId = (int) Auth::guard('admin')->id();

        $lastPatientMessageId = $consultation
            ->messages()
            ->where('sender', 'user')
            ->max('id');

        AdminConsultationRead::updateOrCreate(
            [
                'admin_id' => $adminId,
                'consultation_id' =>
                    $consultation->id,
            ],
            [
                'last_read_message_id' =>
                    $lastPatientMessageId,
                'read_at' => now(),
            ]
        );
    }

    private function buildCounts(): array
    {
        $adminId = (int) Auth::guard('admin')->id();

        $unreadQuery = DB::table('messages as m')
            ->join(
                'consultations as c',
                'c.id',
                '=',
                'm.consultation_id'
            )
            ->leftJoin(
                'admin_consultation_reads as r',
                function ($join) use ($adminId): void {
                    $join
                        ->on(
                            'r.consultation_id',
                            '=',
                            'm.consultation_id'
                        )
                        ->where(
                            'r.admin_id',
                            '=',
                            $adminId
                        );
                }
            )
            ->where('m.sender', 'user')
            ->whereRaw(
                'm.id > COALESCE('
                .'r.last_read_message_id, 0)'
            );

        return [
            'total' => Consultation::count(),
            'active' => Consultation::where(
                'status',
                'aktif'
            )->count(),
            'completed' => Consultation::where(
                'status',
                'selesai'
            )->count(),
            'new' => Consultation::where(
                'status',
                'aktif'
            )->whereNull('last_message_at')->count(),
            'waitingAdmin' => Consultation::where(
                'status',
                'aktif'
            )->where(
                'last_message_sender',
                'user'
            )->count(),
            'waitingPatient' => Consultation::where(
                'status',
                'aktif'
            )->where(
                'last_message_sender',
                'admin'
            )->count(),
            'unreadMessages' =>
                (clone $unreadQuery)->count(),
            'unreadConversations' =>
                (clone $unreadQuery)
                    ->distinct()
                    ->count('m.consultation_id'),
            'activeArchiveRequests' =>
                ConsultationArchiveCopyRequest::query()
                    ->whereIn(
                        'status',
                        ConsultationArchiveCopyRequest::ACTIVE_STATUSES
                    )
                    ->count(),
        ];
    }

    private function unreadCountSql(): string
    {
        return '('
            .'SELECT COUNT(*) '
            .'FROM messages AS inbox_messages '
            .'WHERE inbox_messages.consultation_id '
            .'= consultations.id '
            ."AND inbox_messages.sender = 'user' "
            .'AND inbox_messages.id > COALESCE(('
            .'SELECT inbox_reads.last_read_message_id '
            .'FROM admin_consultation_reads '
            .'AS inbox_reads '
            .'WHERE inbox_reads.admin_id = ? '
            .'AND inbox_reads.consultation_id '
            .'= consultations.id '
            .'LIMIT 1'
            .'), 0)'
            .')';
    }
}
