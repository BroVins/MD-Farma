<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardActivity;
use App\Events\AdminInboxActivity;
use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.inbox');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.inbox'));
    }

    public function dashboard(Request $request): View
    {
        return view(
            'admin.dashboard',
            $this->buildDashboardData($request)
        );
    }

    public function liveData(Request $request): JsonResponse
    {
        $data = $this->buildDashboardData($request);

        return response()->json([
            'kpis' => [
                'totalConsultation' => $data['totalConsultation'],
                'activeChat' => $data['activeChat'],
                'completedChat' => $data['completedChat'],
                'averageResponseLabel' =>
                    $data['averageResponseLabel'],
                'formViews' => $data['formViews'],
                'uniqueFormSessions' =>
                    $data['uniqueFormSessions'],
                'trackedConsultations' =>
                    $data['trackedConsultations'],
                'uniqueCreatedSessions' =>
                    $data['uniqueCreatedSessions'],
                'chatOpens' => $data['chatOpens'],
                'conversionRate' => $data['conversionRate'],
            ],
            'types' => [
                'resep' => $data['resep'],
                'nonResep' => $data['nonResep'],
            ],
            'trend' => $data['trend'],
            'busyMetrics' => $data['busyMetrics'],
            'compactHourly' => $data['compactHourly'],
            'calendar' => $data['calendar'],
            'tableHtml' => view(
                'admin.partials.consultation-table-rows',
                [
                    'consultations' => $data['consultations'],
                    'timezone' => $data['timezone'],
                ]
            )->render(),
            'paginationHtml' => view(
                'admin.partials.consultation-pagination',
                [
                    'consultations' => $data['consultations'],
                ]
            )->render(),
            'syncedAt' => CarbonImmutable::now(
                $data['timezone']
            )->format('H.i.s').' WIB',
        ]);
    }

    public function updateStatus(
        Request $request,
        Consultation $consultation
    ): JsonResponse|RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', 'in:aktif,selesai'],
            'status_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $reason = trim((string) (
            $validated['status_reason'] ?? ''
        ));

        $result = DB::transaction(
            function () use (
                $consultation,
                $validated,
                $reason
            ): array {
                $lockedConsultation = Consultation::query()
                    ->lockForUpdate()
                    ->findOrFail($consultation->getKey());

                $previousStatus = $lockedConsultation->status;
                $newStatus = $validated['status'];

                if ($previousStatus === $newStatus) {
                    return [
                        'consultation' => $lockedConsultation,
                        'changed' => false,
                    ];
                }

                if ($newStatus === 'aktif' && mb_strlen($reason) < 10) {
                    throw ValidationException::withMessages([
                        'status_reason' =>
                            'Alasan mengaktifkan kembali konsultasi wajib diisi minimal 10 karakter.',
                    ]);
                }

                if ($newStatus === 'selesai') {
                    $this->validateConsultationCanBeClosed(
                        $lockedConsultation
                    );
                }

                $lockedConsultation->forceFill([
                    'status' => $newStatus,
                    'closed_at' => $newStatus === 'selesai'
                        ? now()
                        : null,
                ])->save();

                $lockedConsultation->statusLogs()->create([
                    'admin_id' => (int) Auth::guard('admin')->id(),
                    'previous_status' => $previousStatus,
                    'new_status' => $newStatus,
                    'reason' => $reason !== ''
                        ? $reason
                        : 'Konsultasi diselesaikan setelah klasifikasi, skrining, dan hasil akhir lengkap.',
                    'created_at' => now(),
                ]);

                return [
                    'consultation' => $lockedConsultation,
                    'changed' => true,
                ];
            }
        );

        /** @var Consultation $updatedConsultation */
        $updatedConsultation = $result['consultation'];

        if (! $result['changed']) {
            $message = 'Status konsultasi tidak berubah.';
        } else {
            AnalyticsEvent::recordOnce(
                $request,
                $updatedConsultation->status === 'selesai'
                    ? 'consultation_closed'
                    : 'consultation_reopened',
                $updatedConsultation,
                [
                    'status' => $updatedConsultation->status,
                    'reason_recorded' => $reason !== '',
                ],
                'status:'.$updatedConsultation->status.':'
                    .$updatedConsultation->updated_at->timestamp
            );

            $this->broadcastDashboardActivity(
                $updatedConsultation,
                'status_changed'
            );

            $this->broadcastInboxActivity(
                $updatedConsultation,
                'status_changed'
            );

            $message = $updatedConsultation->status === 'selesai'
                ? 'Konsultasi ditandai selesai dan sekarang bersifat hanya-baca.'
                : 'Konsultasi diaktifkan kembali. Alasan perubahan tersimpan pada audit.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'changed' => $result['changed'],
                'message' => $message,
                'status' => $updatedConsultation->status,
            ]);
        }

        return back()->with('success', $message);
    }

    private function validateConsultationCanBeClosed(
        Consultation $consultation
    ): void {
        if (! $consultation->service_classification) {
            throw ValidationException::withMessages([
                'status' =>
                    'Tetapkan klasifikasi pelayanan sebelum menyelesaikan konsultasi.',
            ]);
        }

        $screeningProgress = $consultation->screeningProgress();

        if (! $screeningProgress['is_complete']) {
            $message = 'Lengkapi checklist skrining ('
                .$screeningProgress['completed'].'/'
                .$screeningProgress['required']
                .') sebelum menyelesaikan konsultasi.';

            if (
                $screeningProgress['completed']
                    === $screeningProgress['required']
                && $screeningProgress['notes_required']
                && ! $screeningProgress['notes_complete']
            ) {
                $message = 'Isi catatan skrining wajib sebelum menyelesaikan konsultasi.';
            }

            throw ValidationException::withMessages([
                'status' => $message,
            ]);
        }

        if (! $consultation->outcomeProgress()['is_complete']) {
            throw ValidationException::withMessages([
                'status' =>
                    'Tetapkan hasil akhir pelayanan sebelum menyelesaikan konsultasi.',
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }

    private function buildDashboardData(Request $request): array
    {
        $timezone = config('analytics.timezone', 'Asia/Jakarta');
        $range = $this->resolvePeriod($request, $timezone);

        $records = Consultation::query()
            ->whereBetween('created_at', [
                $range['start_utc'],
                $range['end_utc'],
            ])
            ->get([
                'id',
                'jenis_konsultasi',
                'status',
                'created_at',
                'first_admin_reply_at',
                'last_message_at',
                'closed_at',
            ]);

        $totalConsultation = $records->count();
        $activeChat = $records->where('status', 'aktif')->count();
        $completedChat = $records->where('status', 'selesai')->count();
        $resep = $records
            ->where('jenis_konsultasi', 'resep')
            ->count();
        $nonResep = $records
            ->where('jenis_konsultasi', 'non_resep')
            ->count();

        $responseSeconds = $records
            ->filter(
                fn (Consultation $item): bool =>
                    $item->first_admin_reply_at !== null
            )
            ->map(
                fn (Consultation $item): int =>
                    (int) $item->created_at->diffInSeconds(
                        $item->first_admin_reply_at
                    )
            );

        $averageResponseSeconds = $responseSeconds->isEmpty()
            ? null
            : (int) round((float) $responseSeconds->average());

        $eventBase = AnalyticsEvent::query()
            ->whereBetween('occurred_at', [
                $range['start_utc'],
                $range['end_utc'],
            ]);

        $formViews = (clone $eventBase)
            ->where('event_type', 'consultation_form_viewed')
            ->count();

        $uniqueFormSessions = (clone $eventBase)
            ->where('event_type', 'consultation_form_viewed')
            ->distinct()
            ->count('session_hash');

        $trackedConsultations = (clone $eventBase)
            ->where('event_type', 'consultation_created')
            ->count();

        $uniqueCreatedSessions = (clone $eventBase)
            ->where('event_type', 'consultation_created')
            ->distinct()
            ->count('session_hash');

        $chatOpens = (clone $eventBase)
            ->where('event_type', 'chat_opened')
            ->count();

        $conversionRate = $uniqueFormSessions > 0
            ? min(
                100,
                round(
                    ($uniqueCreatedSessions / $uniqueFormSessions)
                    * 100,
                    1
                )
            )
            : 0.0;

        $hourlyDistribution = $this->buildHourlyDistribution(
            $records,
            $timezone
        );

        $search = trim((string) $request->query('search', ''));
        $type = (string) $request->query('type', '');
        $status = (string) $request->query('status', '');
        $sort = (string) $request->query('sort', 'latest');

        $consultations = $this->buildTablePaginator(
            $request,
            $range,
            $search,
            $type,
            $status,
            $sort
        );

        return [
            'timezone' => $timezone,
            'period' => $range['period'],
            'periodLabel' => $range['label'],
            'startDate' => $range['start_local']->format('Y-m-d'),
            'endDate' => $range['end_local']->format('Y-m-d'),
            'totalConsultation' => $totalConsultation,
            'activeChat' => $activeChat,
            'completedChat' => $completedChat,
            'resep' => $resep,
            'nonResep' => $nonResep,
            'averageResponseLabel' => $this->formatDuration(
                $averageResponseSeconds
            ),
            'trend' => $this->buildTrend(
                $records,
                $range['period'],
                $range['start_local'],
                $range['end_local'],
                $timezone
            ),
            'busyMetrics' => $this->buildBusyMetrics(
                $records,
                $timezone
            ),
            'hourlyDistribution' => $hourlyDistribution,
            'compactHourly' => $this->buildCompactHourly(
                $hourlyDistribution
            ),
            'formViews' => $formViews,
            'uniqueFormSessions' => $uniqueFormSessions,
            'trackedConsultations' => $trackedConsultations,
            'uniqueCreatedSessions' => $uniqueCreatedSessions,
            'chatOpens' => $chatOpens,
            'conversionRate' => $conversionRate,
            'calendar' => $this->buildCalendar(
                $request,
                $timezone
            ),
            'consultations' => $consultations,
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'sort' => $sort,
        ];
    }

    private function buildTablePaginator(
        Request $request,
        array $range,
        string $search,
        string $type,
        string $status,
        string $sort
    ): LengthAwarePaginator {
        $tableQuery = Consultation::query()
            ->withCount('messages')
            ->whereBetween('created_at', [
                $range['start_utc'],
                $range['end_utc'],
            ]);

        if ($search !== '') {
            $tableQuery->where(
                function ($query) use ($search): void {
                    $query
                        ->where('nama', 'like', '%'.$search.'%')
                        ->orWhere(
                            'no_hp',
                            'like',
                            '%'.$search.'%'
                        );
                }
            );
        }

        if (in_array($type, ['resep', 'non_resep'], true)) {
            $tableQuery->where('jenis_konsultasi', $type);
        }

        if (in_array($status, ['aktif', 'selesai'], true)) {
            $tableQuery->where('status', $status);
        }

        match ($sort) {
            'oldest' => $tableQuery->oldest('created_at'),
            'last_activity' => $tableQuery->orderByRaw(
                'COALESCE(last_message_at, created_at) DESC'
            ),
            default => $tableQuery->latest('created_at'),
        };

        $paginator = $tableQuery->paginate(12);

        $paginator
            ->withPath(route('admin.dashboard'))
            ->appends($request->except('page'));

        return $paginator;
    }

    private function resolvePeriod(
        Request $request,
        string $timezone
    ): array {
        $period = (string) $request->query('period', 'month');

        if (! in_array(
            $period,
            ['today', 'week', 'month', 'year', 'custom'],
            true
        )) {
            $period = 'month';
        }

        $now = CarbonImmutable::now($timezone);

        switch ($period) {
            case 'today':
                $start = $now->startOfDay();
                $end = $now->endOfDay();
                break;

            case 'week':
                $start = $now->startOfWeek(
                    CarbonInterface::MONDAY
                );
                $end = $now->endOfWeek(
                    CarbonInterface::SUNDAY
                );
                break;

            case 'year':
                $start = $now->startOfYear();
                $end = $now->endOfYear();
                break;

            case 'custom':
                try {
                    $start = CarbonImmutable::createFromFormat(
                        '!Y-m-d',
                        (string) $request->query(
                            'start_date',
                            $now->subDays(29)->format('Y-m-d')
                        ),
                        $timezone
                    )->startOfDay();

                    $end = CarbonImmutable::createFromFormat(
                        '!Y-m-d',
                        (string) $request->query(
                            'end_date',
                            $now->format('Y-m-d')
                        ),
                        $timezone
                    )->endOfDay();
                } catch (Throwable) {
                    $start = $now->subDays(29)->startOfDay();
                    $end = $now->endOfDay();
                }

                if ($end->lessThan($start)) {
                    [$start, $end] = [
                        $end->startOfDay(),
                        $start->endOfDay(),
                    ];
                }

                if ($start->diffInDays($end) > 366) {
                    $start = $end->subDays(366)->startOfDay();
                }
                break;

            default:
                $start = $now->startOfMonth();
                $end = $now->endOfMonth();
        }

        $label = $start->isSameDay($end)
            ? $start->locale('id')
                ->isoFormat('dddd, D MMMM YYYY')
            : $start->locale('id')->isoFormat('D MMM YYYY')
                .' – '
                .$end->locale('id')->isoFormat('D MMM YYYY');

        return [
            'period' => $period,
            'start_local' => $start,
            'end_local' => $end,
            'start_utc' => $start->setTimezone('UTC'),
            'end_utc' => $end->setTimezone('UTC'),
            'label' => $label,
        ];
    }

    private function buildTrend(
        Collection $records,
        string $period,
        CarbonImmutable $start,
        CarbonImmutable $end,
        string $timezone
    ): array {
        $labels = [];
        $indexes = [];
        $values = [];

        if ($period === 'today') {
            for ($hour = 0; $hour < 24; $hour++) {
                $key = $start->setHour($hour)
                    ->format('Y-m-d-H');

                $indexes[$key] = count($labels);
                $labels[] = sprintf('%02d.00', $hour);
                $values[] = 0;
            }

            foreach ($records as $record) {
                $key = $this->toLocal(
                    $record->created_at,
                    $timezone
                )->format('Y-m-d-H');

                if (isset($indexes[$key])) {
                    $values[$indexes[$key]]++;
                }
            }

            return [
                'title' => 'Konsultasi per jam',
                'labels' => $labels,
                'values' => $values,
            ];
        }

        if (
            $period === 'year'
            || $start->diffInDays($end) > 62
        ) {
            $cursor = $start->startOfMonth();
            $last = $end->startOfMonth();

            while ($cursor->lessThanOrEqualTo($last)) {
                $key = $cursor->format('Y-m');
                $indexes[$key] = count($labels);
                $labels[] = $cursor
                    ->locale('id')
                    ->isoFormat('MMM YYYY');
                $values[] = 0;
                $cursor = $cursor->addMonth();
            }

            foreach ($records as $record) {
                $key = $this->toLocal(
                    $record->created_at,
                    $timezone
                )->format('Y-m');

                if (isset($indexes[$key])) {
                    $values[$indexes[$key]]++;
                }
            }

            return [
                'title' => 'Konsultasi per bulan',
                'labels' => $labels,
                'values' => $values,
            ];
        }

        $cursor = $start->startOfDay();
        $last = $end->startOfDay();

        while ($cursor->lessThanOrEqualTo($last)) {
            $key = $cursor->format('Y-m-d');
            $indexes[$key] = count($labels);
            $labels[] = $cursor
                ->locale('id')
                ->isoFormat('D MMM');
            $values[] = 0;
            $cursor = $cursor->addDay();
        }

        foreach ($records as $record) {
            $key = $this->toLocal(
                $record->created_at,
                $timezone
            )->format('Y-m-d');

            if (isset($indexes[$key])) {
                $values[$indexes[$key]]++;
            }
        }

        return [
            'title' => 'Konsultasi per hari',
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function buildBusyMetrics(
        Collection $records,
        string $timezone
    ): array {
        if ($records->isEmpty()) {
            return [
                'day' => null,
                'date' => null,
                'month' => null,
                'hour' => null,
            ];
        }

        $groups = [
            'day' => $records->groupBy(
                fn (Consultation $item): string =>
                    $this->toLocal(
                        $item->created_at,
                        $timezone
                    )->locale('id')->isoFormat('dddd')
            ),
            'date' => $records->groupBy(
                fn (Consultation $item): string =>
                    $this->toLocal(
                        $item->created_at,
                        $timezone
                    )->format('Y-m-d')
            ),
            'month' => $records->groupBy(
                fn (Consultation $item): string =>
                    $this->toLocal(
                        $item->created_at,
                        $timezone
                    )->format('Y-m')
            ),
            'hour' => $records->groupBy(
                fn (Consultation $item): string =>
                    $this->toLocal(
                        $item->created_at,
                        $timezone
                    )->format('H')
            ),
        ];

        $top = [];

        foreach ($groups as $name => $group) {
            $sorted = $group->sortByDesc(
                fn (Collection $items): int =>
                    $items->count()
            );

            $top[$name] = [
                'key' => $sorted->keys()->first(),
                'total' => $sorted->first()?->count() ?? 0,
            ];
        }

        return [
            'day' => [
                'label' => (string) $top['day']['key'],
                'total' => $top['day']['total'],
            ],
            'date' => [
                'label' => CarbonImmutable::createFromFormat(
                    '!Y-m-d',
                    (string) $top['date']['key'],
                    $timezone
                )->locale('id')
                    ->isoFormat('D MMMM YYYY'),
                'total' => $top['date']['total'],
            ],
            'month' => [
                'label' => CarbonImmutable::createFromFormat(
                    '!Y-m',
                    (string) $top['month']['key'],
                    $timezone
                )->locale('id')
                    ->isoFormat('MMMM YYYY'),
                'total' => $top['month']['total'],
            ],
            'hour' => [
                'label' => sprintf(
                    '%02d.00–%02d.59 WIB',
                    (int) $top['hour']['key'],
                    (int) $top['hour']['key']
                ),
                'total' => $top['hour']['total'],
            ],
        ];
    }

    private function buildHourlyDistribution(
        Collection $records,
        string $timezone
    ): array {
        $hours = array_fill(0, 24, 0);

        foreach ($records as $record) {
            $hour = (int) $this->toLocal(
                $record->created_at,
                $timezone
            )->format('H');

            $hours[$hour]++;
        }

        return collect($hours)->map(
            fn (int $total, int $hour): array => [
                'hour' => sprintf('%02d', $hour),
                'label' => sprintf(
                    '%02d.00–%02d.59 WIB',
                    $hour,
                    $hour
                ),
                'total' => $total,
            ]
        )->values()->all();
    }

    private function buildCompactHourly(array $hours): array
    {
        $total = max(
            1,
            (int) collect($hours)->sum('total')
        );

        $ranges = [
            [
                'label' => 'Dini hari',
                'range' => '00.00–05.59',
                'start' => 0,
                'end' => 5,
            ],
            [
                'label' => 'Pagi',
                'range' => '06.00–11.59',
                'start' => 6,
                'end' => 11,
            ],
            [
                'label' => 'Siang–Sore',
                'range' => '12.00–17.59',
                'start' => 12,
                'end' => 17,
            ],
            [
                'label' => 'Malam',
                'range' => '18.00–23.59',
                'start' => 18,
                'end' => 23,
            ],
        ];

        $periods = collect($ranges)->map(
            function (array $range) use (
                $hours,
                $total
            ): array {
                $count = collect($hours)
                    ->slice(
                        $range['start'],
                        $range['end']
                        - $range['start']
                        + 1
                    )
                    ->sum('total');

                return [
                    'label' => $range['label'],
                    'range' => $range['range'],
                    'total' => (int) $count,
                    'share' => round(
                        ((int) $count / $total) * 100,
                        1
                    ),
                ];
            }
        )->values()->all();

        $topHours = collect($hours)
            ->filter(
                fn (array $hour): bool =>
                    $hour['total'] > 0
            )
            ->sortByDesc('total')
            ->take(6)
            ->values();

        $maximum = max(
            1,
            (int) $topHours->max('total')
        );

        return [
            'periods' => $periods,
            'topHours' => $topHours->map(
                fn (array $hour): array => [
                    ...$hour,
                    'width' => round(
                        ($hour['total'] / $maximum) * 100,
                        1
                    ),
                ]
            )->all(),
        ];
    }

    private function buildCalendar(
        Request $request,
        string $timezone
    ): array {
        try {
            $start = CarbonImmutable::createFromFormat(
                '!Y-m',
                (string) $request->query(
                    'calendar_month',
                    CarbonImmutable::now(
                        $timezone
                    )->format('Y-m')
                ),
                $timezone
            )->startOfMonth();
        } catch (Throwable) {
            $start = CarbonImmutable::now(
                $timezone
            )->startOfMonth();
        }

        $end = $start->endOfMonth();

        $records = Consultation::query()
            ->whereBetween('created_at', [
                $start->setTimezone('UTC'),
                $end->setTimezone('UTC'),
            ])
            ->get([
                'id',
                'jenis_konsultasi',
                'created_at',
            ]);

        $grouped = $records->groupBy(
            fn (Consultation $item): string =>
                $this->toLocal(
                    $item->created_at,
                    $timezone
                )->format('Y-m-d')
        );

        $maximum = max(
            1,
            (int) $grouped
                ->map(
                    fn (Collection $items): int =>
                        $items->count()
                )->max()
        );

        $cells = array_fill(
            0,
            $start->dayOfWeekIso - 1,
            null
        );

        for (
            $day = 1;
            $day <= $start->daysInMonth;
            $day++
        ) {
            $date = $start->setDay($day);
            $items = $grouped->get(
                $date->format('Y-m-d'),
                collect()
            );

            $hours = $items->groupBy(
                fn (Consultation $item): string =>
                    $this->toLocal(
                        $item->created_at,
                        $timezone
                    )->format('H')
            );

            $busyHour = $hours->isEmpty()
                ? null
                : $hours->sortByDesc(
                    fn (Collection $hourItems): int =>
                        $hourItems->count()
                )->keys()->first();

            $total = $items->count();

            $cells[] = [
                'day' => $day,
                'date_label' => $date
                    ->locale('id')
                    ->isoFormat('dddd, D MMMM YYYY'),
                'total' => $total,
                'resep' => $items
                    ->where(
                        'jenis_konsultasi',
                        'resep'
                    )->count(),
                'non_resep' => $items
                    ->where(
                        'jenis_konsultasi',
                        'non_resep'
                    )->count(),
                'busiest_hour' => $busyHour === null
                    ? 'Belum ada data'
                    : sprintf(
                        '%02d.00–%02d.59 WIB',
                        (int) $busyHour,
                        (int) $busyHour
                    ),
                'intensity' => $total === 0
                    ? 0
                    : max(
                        1,
                        min(
                            4,
                            (int) ceil(
                                ($total / $maximum) * 4
                            )
                        )
                    ),
                'is_today' => $date->isToday(),
            ];
        }

        while (count($cells) % 7 !== 0) {
            $cells[] = null;
        }

        return [
            'label' => $start
                ->locale('id')
                ->isoFormat('MMMM YYYY'),
            'previous' => $start
                ->subMonth()
                ->format('Y-m'),
            'next' => $start
                ->addMonth()
                ->format('Y-m'),
            'cells' => $cells,
            'total' => $records->count(),
        ];
    }

    private function broadcastDashboardActivity(
        Consultation $consultation,
        string $activityType
    ): void {
        try {
            event(
                new AdminDashboardActivity(
                    $consultation->fresh(),
                    $activityType
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi dashboard realtime gagal.',
                [
                    'consultation_id' =>
                        $consultation->id,
                    'exception' =>
                        $exception::class,
                ]
            );
        }
    }

    private function broadcastInboxActivity(
        Consultation $consultation,
        string $activityType
    ): void {
        try {
            event(
                new AdminInboxActivity(
                    $consultation->fresh([
                        'lastMessage',
                    ]),
                    $activityType
                )
            );
        } catch (Throwable $exception) {
            Log::warning(
                'Sinkronisasi inbox realtime gagal.',
                [
                    'consultation_id' =>
                        $consultation->id,
                    'exception' =>
                        $exception::class,
                ]
            );
        }
    }

    private function toLocal(
        CarbonInterface $date,
        string $timezone
    ): CarbonImmutable {
        return CarbonImmutable::instance($date)
            ->setTimezone($timezone);
    }

    private function formatDuration(?int $seconds): string
    {
        if ($seconds === null) {
            return 'Belum ada balasan';
        }

        if ($seconds < 60) {
            return $seconds.' detik';
        }

        $minutes = intdiv($seconds, 60);

        if ($minutes < 60) {
            return $minutes.' menit';
        }

        return intdiv($minutes, 60).' jam '
            .($minutes % 60).' menit';
    }
}
