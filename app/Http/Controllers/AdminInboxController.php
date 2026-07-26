<?php

namespace App\Http\Controllers;

use App\Models\AdminConsultationRead;
use App\Models\Consultation;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
