<?php

namespace App\Http\Controllers;

use App\Models\AdminConsultationRead;
use App\Models\Consultation;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                $query->oldest('id'),
            'lastMessage',
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
                    $query->oldest('id'),
                'lastMessage',
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

        /*
         * Hitung pesan belum dibaca sekali per konsultasi.
         * Hasilnya di-join ke daftar konsultasi, sehingga tidak lagi
         * menjalankan correlated subquery untuk setiap baris.
         */
        $unreadCounts = $this->unreadCountsQuery(
            $adminId
        );

        $query = Consultation::query()
            ->select('consultations.*')
            ->leftJoinSub(
                $unreadCounts,
                'inbox_unread',
                function ($join): void {
                    $join->on(
                        'inbox_unread.consultation_id',
                        '=',
                        'consultations.id'
                    );
                }
            )
            ->selectRaw(
                'COALESCE(inbox_unread.unread_count, 0) '
                .'AS unread_count'
            )
            ->with([
                'lastMessage' => function (
                    $messageRelation
                ): void {
                    $messageRelation->select([
                        'messages.id',
                        'messages.consultation_id',
                        'messages.sender',
                        'messages.message',
                        'messages.image',
                        'messages.created_at',
                    ]);
                },
            ]);

        $this->applyFilters(
            $query,
            $search,
            $state,
            $type
        );

        $this->applySort($query, $sort);

        /*
         * Dua puluh item sudah cukup untuk layar inbox dan mengurangi
         * beban render serta transfer HTML pada pemuatan pertama.
         */
        $consultations = $query->paginate(
            20,
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
        string $type
    ): void {
        if ($search !== '') {
            $query->where(
                function (Builder $builder) use (
                    $search
                ): void {
                    $builder
                        ->where(
                            'consultations.nama',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'consultations.no_hp',
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
                'consultations.jenis_konsultasi',
                $type
            );
        }

        match ($state) {
            'unread' => $query->where(
                'inbox_unread.unread_count',
                '>',
                0
            ),
            'new' => $query
                ->where(
                    'consultations.status',
                    'aktif'
                )
                ->whereNull(
                    'consultations.last_message_at'
                ),
            'waiting_admin' => $query
                ->where(
                    'consultations.status',
                    'aktif'
                )
                ->where(
                    'consultations.last_message_sender',
                    'user'
                ),
            'waiting_patient' => $query
                ->where(
                    'consultations.status',
                    'aktif'
                )
                ->where(
                    'consultations.last_message_sender',
                    'admin'
                ),
            'active' => $query->where(
                'consultations.status',
                'aktif'
            ),
            'completed' => $query->where(
                'consultations.status',
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
                'COALESCE('
                .'consultations.last_message_at, '
                .'consultations.created_at'
                .') ASC'
            ),
            'waiting_oldest' => $query
                ->orderByRaw(
                    "CASE WHEN consultations.status = 'aktif' "
                    ."AND consultations.last_message_sender = 'user' "
                    .'THEN 0 ELSE 1 END ASC'
                )
                ->orderByRaw(
                    'COALESCE('
                    .'consultations.last_message_at, '
                    .'consultations.created_at'
                    .') ASC'
                ),
            default => $query->orderByRaw(
                'COALESCE('
                .'consultations.last_message_at, '
                .'consultations.created_at'
                .') DESC'
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
            : (
                $localTime->isYesterday()
                    ? 'Kemarin'
                    : (
                        $localTime->year === $today->year
                            ? $localTime
                                ->locale('id')
                                ->isoFormat('D MMM')
                            : $localTime->format('d/m/Y')
                    )
            );

        $preview = $lastMessage?->message
            ? Str::limit(
                preg_replace(
                    '/\s+/',
                    ' ',
                    trim($lastMessage->message)
                ),
                72
            )
            : (
                $lastMessage?->image
                    ? '📎 Lampiran'
                    : 'Konsultasi baru — belum ada pesan'
            );

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

        /*
         * Sebelumnya enam status konsultasi dihitung melalui enam query.
         * Sekarang seluruh status dihitung melalui satu aggregate query.
         */
        $consultationCounts = Consultation::query()
            ->selectRaw(
                'COUNT(*) AS total'
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'aktif' "
                .'THEN 1 ELSE 0 END) AS active'
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'selesai' "
                .'THEN 1 ELSE 0 END) AS completed'
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'aktif' "
                .'AND last_message_at IS NULL '
                .'THEN 1 ELSE 0 END) AS new_count'
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'aktif' "
                ."AND last_message_sender = 'user' "
                .'THEN 1 ELSE 0 END) AS waiting_admin'
            )
            ->selectRaw(
                "SUM(CASE WHEN status = 'aktif' "
                ."AND last_message_sender = 'admin' "
                .'THEN 1 ELSE 0 END) AS waiting_patient'
            )
            ->first();

        /*
         * Total pesan dan total percakapan belum dibaca juga dihitung
         * melalui satu query dari hasil agregasi per konsultasi.
         */
        $unreadCounts = DB::query()
            ->fromSub(
                $this->unreadCountsQuery($adminId),
                'unread_by_consultation'
            )
            ->selectRaw(
                'COALESCE(SUM(unread_count), 0) '
                .'AS unread_messages'
            )
            ->selectRaw(
                'COUNT(*) AS unread_conversations'
            )
            ->first();

        return [
            'total' => (int) (
                $consultationCounts?->total ?? 0
            ),
            'active' => (int) (
                $consultationCounts?->active ?? 0
            ),
            'completed' => (int) (
                $consultationCounts?->completed ?? 0
            ),
            'new' => (int) (
                $consultationCounts?->new_count ?? 0
            ),
            'waitingAdmin' => (int) (
                $consultationCounts?->waiting_admin ?? 0
            ),
            'waitingPatient' => (int) (
                $consultationCounts?->waiting_patient ?? 0
            ),
            'unreadMessages' => (int) (
                $unreadCounts?->unread_messages ?? 0
            ),
            'unreadConversations' => (int) (
                $unreadCounts?->unread_conversations ?? 0
            ),
        ];
    }

    private function unreadCountsQuery(
        int $adminId
    ): QueryBuilder {
        return DB::table('messages as unread_messages')
            ->leftJoin(
                'admin_consultation_reads as unread_reads',
                function ($join) use ($adminId): void {
                    $join
                        ->on(
                            'unread_reads.consultation_id',
                            '=',
                            'unread_messages.consultation_id'
                        )
                        ->where(
                            'unread_reads.admin_id',
                            '=',
                            $adminId
                        );
                }
            )
            ->select(
                'unread_messages.consultation_id'
            )
            ->selectRaw(
                'COUNT(*) AS unread_count'
            )
            ->where(
                'unread_messages.sender',
                'user'
            )
            ->whereRaw(
                'unread_messages.id > COALESCE('
                .'unread_reads.last_read_message_id, 0)'
            )
            ->groupBy(
                'unread_messages.consultation_id'
            );
    }
}
