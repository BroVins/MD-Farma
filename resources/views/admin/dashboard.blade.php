<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Dashboard Analitik MD Farma</title>

    <style>
        :root {
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-500:#10b981;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --white:#fff;
            --shadow:0 16px 42px rgba(15,23,42,.08);
        }

        * { box-sizing:border-box; }

        body {
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:#f8fafc;
        }

        button,input,select { font:inherit; }

        .topbar {
            position:sticky;
            top:0;
            z-index:40;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            padding:15px clamp(18px,4vw,56px);
            background:rgba(6,78,59,.96);
            color:var(--white);
            backdrop-filter:blur(12px);
            box-shadow:0 8px 24px rgba(6,78,59,.16);
        }

        .brand {
            display:flex;
            align-items:center;
            gap:11px;
            color:var(--white);
            text-decoration:none;
            font-weight:800;
        }

        .brand-mark {
            display:grid;
            place-items:center;
            width:39px;
            height:39px;
            border:1px solid rgba(255,255,255,.22);
            border-radius:12px;
            background:rgba(255,255,255,.14);
        }

        .admin-actions {
            display:flex;
            align-items:center;
            gap:13px;
        }

        .identity { text-align:right; }
        .identity strong,.identity small { display:block; }
        .identity small { color:#a7f3d0; }

        .logout {
            padding:9px 12px;
            border:1px solid rgba(255,255,255,.3);
            border-radius:9px;
            background:rgba(255,255,255,.08);
            color:#fff;
            cursor:pointer;
        }

        .page {
            width:min(1480px,94%);
            margin:29px auto 60px;
        }

        .hero {
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:18px;
            margin-bottom:20px;
        }

        .hero h1 {
            margin:0 0 7px;
            font-size:clamp(29px,4vw,42px);
            letter-spacing:-.04em;
        }

        .hero p {
            margin:0;
            color:var(--slate-600);
        }

        .eyebrow {
            margin-bottom:6px!important;
            color:var(--green-700)!important;
            font-size:12px;
            font-weight:800;
            letter-spacing:.12em;
            text-transform:uppercase;
        }

        .period-label {
            padding:9px 13px;
            border-radius:999px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:12px;
            font-weight:800;
            white-space:nowrap;
        }

        .panel {
            border:1px solid var(--slate-200);
            border-radius:18px;
            background:var(--white);
            box-shadow:var(--shadow);
        }

        .notice {
            margin-bottom:16px;
            padding:12px 14px;
            border:1px solid var(--green-100);
            border-radius:11px;
            background:var(--green-50);
            color:var(--green-900);
        }

        .filter-panel {
            padding:17px;
            margin-bottom:20px;
        }

        .period-tabs {
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            margin-bottom:13px;
        }

        .period-tab {
            min-height:38px;
            padding:8px 13px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            color:var(--slate-700);
            text-decoration:none;
            font-size:13px;
            font-weight:800;
        }

        .period-tab.active {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .custom-period {
            display:grid;
            grid-template-columns:repeat(2,minmax(160px,220px)) auto;
            gap:10px;
            align-items:end;
        }

        .field label {
            display:block;
            margin-bottom:5px;
            color:var(--slate-600);
            font-size:11px;
            font-weight:800;
        }

        .field input,
        .table-filter input,
        .table-filter select {
            width:100%;
            min-height:41px;
            padding:8px 10px;
            border:1px solid var(--slate-300);
            border-radius:9px;
            background:#fff;
        }

        .button {
            min-height:41px;
            padding:9px 15px;
            border:0;
            border-radius:9px;
            background:var(--green-700);
            color:#fff;
            font-weight:800;
            cursor:pointer;
        }

        .kpi-grid {
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:14px;
            margin-bottom:20px;
        }

        .kpi {
            position:relative;
            overflow:hidden;
            min-height:137px;
            padding:19px;
            border:1px solid var(--slate-200);
            border-radius:16px;
            background:#fff;
            box-shadow:0 12px 28px rgba(15,23,42,.06);
        }

        .kpi::after {
            content:"";
            position:absolute;
            right:-34px;
            bottom:-45px;
            width:105px;
            height:105px;
            border-radius:50%;
            background:var(--green-50);
        }

        .kpi span,.kpi strong,.kpi small {
            position:relative;
            z-index:1;
            display:block;
        }

        .kpi span {
            margin-bottom:13px;
            color:var(--slate-600);
            font-size:12px;
            font-weight:800;
        }

        .kpi strong {
            margin-bottom:7px;
            font-size:clamp(27px,3vw,37px);
            line-height:1;
            letter-spacing:-.04em;
        }

        .kpi small { color:var(--slate-500); }

        .grid-2 {
            display:grid;
            grid-template-columns:minmax(0,1.65fr) minmax(280px,.85fr);
            gap:17px;
            margin-bottom:20px;
        }

        .panel-header {
            display:flex;
            justify-content:space-between;
            gap:12px;
            padding:19px 20px 0;
        }

        .panel-header h2 {
            margin:0 0 4px;
            font-size:18px;
        }

        .panel-header p {
            margin:0;
            color:var(--slate-500);
            font-size:12px;
        }

        .chart-wrap {
            height:300px;
            padding:14px 18px 20px;
        }

        #trendChart {
            width:100%;
            height:100%;
        }

        .donut-area {
            display:grid;
            place-items:center;
            padding:23px 18px 19px;
        }

        .donut {
            --share:0%;
            position:relative;
            display:grid;
            place-items:center;
            width:190px;
            aspect-ratio:1;
            border-radius:50%;
            background:conic-gradient(
                var(--green-600) 0 var(--share),
                var(--slate-300) var(--share) 100%
            );
        }

        .donut::after {
            content:"";
            position:absolute;
            width:65%;
            height:65%;
            border-radius:50%;
            background:#fff;
        }

        .donut-value {
            position:relative;
            z-index:1;
            text-align:center;
        }

        .donut-value strong {
            display:block;
            font-size:30px;
        }

        .donut-value small { color:var(--slate-500); }

        .legend {
            display:grid;
            gap:8px;
            width:100%;
            margin-top:18px;
        }

        .legend-row {
            display:flex;
            justify-content:space-between;
            padding:9px 11px;
            border-radius:9px;
            background:var(--slate-100);
            font-size:12px;
        }

        .legend-name {
            display:flex;
            align-items:center;
            gap:7px;
        }

        .dot {
            width:9px;
            height:9px;
            border-radius:50%;
            background:var(--green-600);
        }

        .dot.gray { background:var(--slate-300); }

        .busy-grid {
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:13px;
            margin-bottom:20px;
        }

        .busy {
            padding:17px;
            border:1px solid var(--slate-200);
            border-radius:15px;
            background:linear-gradient(145deg,#fff,var(--green-50));
        }

        .busy span {
            display:block;
            margin-bottom:8px;
            color:var(--slate-500);
            font-size:11px;
            font-weight:800;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        .busy strong {
            display:block;
            margin-bottom:5px;
            font-size:16px;
        }

        .busy small { color:var(--slate-600); }

        .hourly {
            display:grid;
            grid-template-columns:repeat(24,minmax(25px,1fr));
            align-items:end;
            gap:6px;
            min-height:235px;
            padding:20px 18px 17px;
            overflow-x:auto;
        }

        .hour {
            display:grid;
            grid-template-rows:175px auto;
            align-items:end;
            min-width:25px;
        }

        .bar-wrap {
            display:flex;
            align-items:end;
            height:175px;
        }

        .bar {
            position:relative;
            width:100%;
            min-height:3px;
            border-radius:6px 6px 2px 2px;
            background:linear-gradient(180deg,var(--green-500),var(--green-800));
        }

        .bar:hover::after {
            content:attr(data-tooltip);
            position:absolute;
            left:50%;
            bottom:calc(100% + 6px);
            transform:translateX(-50%);
            z-index:3;
            padding:5px 7px;
            border-radius:6px;
            background:var(--slate-950);
            color:#fff;
            font-size:10px;
            white-space:nowrap;
        }

        .hour-label {
            padding-top:7px;
            color:var(--slate-500);
            font-size:9px;
            text-align:center;
        }

        .calendar-panel {
            margin:20px 0;
            overflow:hidden;
        }

        .calendar-nav {
            display:flex;
            gap:7px;
        }

        .calendar-nav a {
            display:grid;
            place-items:center;
            width:35px;
            height:35px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            color:var(--slate-700);
            text-decoration:none;
        }

        .calendar {
            display:grid;
            grid-template-columns:repeat(7,minmax(0,1fr));
            gap:7px;
            padding:17px 19px 19px;
        }

        .weekday {
            padding:6px;
            color:var(--slate-500);
            font-size:10px;
            font-weight:800;
            text-align:center;
            text-transform:uppercase;
        }

        .calendar-blank,.calendar-day {
            min-height:82px;
            border-radius:11px;
        }

        .calendar-day {
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            align-items:flex-start;
            padding:9px;
            border:1px solid var(--slate-200);
            background:#fff;
            color:var(--slate-950);
            cursor:pointer;
            text-align:left;
        }

        .calendar-day.i1 { background:#ecfdf5; }
        .calendar-day.i2 { background:#a7f3d0; }
        .calendar-day.i3 { background:#34d399; }
        .calendar-day.i4 {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .calendar-day.today {
            outline:3px solid rgba(245,158,11,.45);
        }

        .calendar-day small { font-size:10px; }

        .calendar-legend {
            display:flex;
            flex-wrap:wrap;
            gap:11px;
            padding:0 19px 18px;
            color:var(--slate-500);
            font-size:10px;
        }

        .calendar-legend span {
            display:flex;
            align-items:center;
            gap:5px;
        }

        .heat {
            width:10px;
            height:10px;
            border-radius:3px;
            background:var(--slate-100);
        }

        .h1 { background:#ecfdf5; }
        .h2 { background:#a7f3d0; }
        .h3 { background:#34d399; }
        .h4 { background:var(--green-700); }

        .table-panel { overflow:hidden; }

        .table-filter {
            display:grid;
            grid-template-columns:minmax(210px,1.5fr)
                repeat(3,minmax(125px,.7fr)) auto;
            gap:9px;
            padding:15px 18px;
            border-top:1px solid var(--slate-200);
            border-bottom:1px solid var(--slate-200);
            background:var(--slate-100);
        }

        .table-wrap { overflow-x:auto; }

        table {
            width:100%;
            min-width:1030px;
            border-collapse:collapse;
        }

        th,td {
            padding:13px 14px;
            border-bottom:1px solid var(--slate-200);
            text-align:left;
            vertical-align:middle;
        }

        th {
            background:#fbfdff;
            color:var(--slate-500);
            font-size:10px;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        td { font-size:12px; }

        .patient-name { font-weight:800; }

        .sub {
            display:block;
            margin-top:3px;
            color:var(--slate-500);
            font-size:10px;
        }

        .badge {
            display:inline-flex;
            padding:5px 8px;
            border-radius:999px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:10px;
            font-weight:800;
        }

        .badge.gray {
            background:var(--slate-100);
            color:var(--slate-700);
        }

        .badge.amber {
            background:#fef3c7;
            color:#92400e;
        }

        .chat-link {
            color:var(--green-700);
            font-weight:800;
            text-decoration:none;
        }

        .empty {
            padding:32px;
            color:var(--slate-500);
            text-align:center;
        }

        .pagination { padding:17px; }

        .pager {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .pager-links {
            display:flex;
            align-items:center;
            gap:6px;
        }

        .pager a,.pager span {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:34px;
            min-height:34px;
            padding:6px 9px;
            border:1px solid var(--slate-200);
            border-radius:8px;
            color:var(--slate-700);
            text-decoration:none;
            font-size:11px;
        }

        .pager span.current {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .pager span.disabled {
            color:var(--slate-300);
            background:var(--slate-100);
        }

        .modal-bg {
            position:fixed;
            inset:0;
            z-index:100;
            display:none;
            place-items:center;
            padding:20px;
            background:rgba(15,23,42,.58);
        }

        .modal-bg.open { display:grid; }

        .modal {
            width:min(440px,100%);
            overflow:hidden;
            border-radius:18px;
            background:#fff;
            box-shadow:0 28px 80px rgba(15,23,42,.25);
        }

        .modal-head {
            display:flex;
            justify-content:space-between;
            gap:12px;
            padding:19px;
            border-bottom:1px solid var(--slate-200);
        }

        .modal-head h3 { margin:0 0 4px; }

        .modal-head p {
            margin:0;
            color:var(--slate-500);
            font-size:12px;
        }

        .modal-close {
            width:33px;
            height:33px;
            border:0;
            border-radius:8px;
            background:var(--slate-100);
            cursor:pointer;
        }

        .modal-grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:9px;
            padding:18px;
        }

        .modal-stat {
            padding:12px;
            border-radius:11px;
            background:var(--slate-100);
        }

        .modal-stat span,.modal-stat strong { display:block; }
        .modal-stat span {
            margin-bottom:5px;
            color:var(--slate-500);
            font-size:10px;
        }

        @media (max-width:1050px) {
            .kpi-grid,.busy-grid {
                grid-template-columns:repeat(2,minmax(0,1fr));
            }

            .grid-2 { grid-template-columns:1fr; }

            .table-filter {
                grid-template-columns:repeat(2,minmax(0,1fr));
            }
        }

        @media (max-width:700px) {
            .topbar,.hero {
                align-items:flex-start;
                flex-direction:column;
            }

            .admin-actions {
                width:100%;
                justify-content:space-between;
            }

            .identity { text-align:left; }

            .custom-period,.kpi-grid,.busy-grid,.table-filter {
                grid-template-columns:1fr;
            }

            .calendar {
                gap:4px;
                padding:11px;
            }

            .calendar-blank,.calendar-day { min-height:62px; }
            .calendar-day { padding:6px; }
            .calendar-day small { display:none; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark">✚</span>
            <span>MD Farma Analytics</span>
        </a>

        <div class="admin-actions">
            <div class="identity">
                <strong>{{ auth('admin')->user()->username }}</strong>
                <small>Administrator</small>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="logout" type="submit">Logout</button>
            </form>
        </div>
    </header>

    <main class="page">
        <section class="hero">
            <div>
                <p class="eyebrow">Dashboard operasional</p>
                <h1>Analitik Konsultasi</h1>
                <p>
                    Pantau tren, waktu tersibuk, jenis layanan,
                    dan respons apoteker.
                </p>
            </div>

            <span class="period-label">
                {{ $periodLabel }} · WIB
            </span>
        </section>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        <section class="panel filter-panel">
            <div class="period-tabs">
                @foreach ([
                    'today' => 'Hari Ini',
                    'week' => 'Minggu Ini',
                    'month' => 'Bulan Ini',
                    'year' => 'Tahun Ini',
                ] as $key => $label)
                    <a
                        class="period-tab {{ $period === $key ? 'active' : '' }}"
                        href="{{ route(
                            'admin.dashboard',
                            array_merge(
                                request()->except([
                                    'period',
                                    'start_date',
                                    'end_date',
                                    'page',
                                ]),
                                ['period' => $key]
                            )
                        ) }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form
                class="custom-period"
                action="{{ route('admin.dashboard') }}"
                method="GET"
            >
                <input type="hidden" name="period" value="custom">

                <div class="field">
                    <label for="start_date">Tanggal mulai</label>
                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        value="{{ $startDate }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="end_date">Tanggal akhir</label>
                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        value="{{ $endDate }}"
                        required
                    >
                </div>

                <button class="button" type="submit">
                    Terapkan Rentang
                </button>
            </form>
        </section>

        <section class="kpi-grid">
            @foreach ([
                ['Total Konsultasi', $totalConsultation, 'Periode terpilih'],
                ['Konsultasi Aktif', $activeChat, 'Masih dalam proses'],
                ['Konsultasi Selesai', $completedChat, 'Sudah ditutup admin'],
                ['Rata-rata Respons', $averageResponseLabel, 'Balasan admin pertama'],
                ['Akses Form', $formViews, $uniqueFormSessions.' estimasi sesi unik'],
                ['Konsultasi Terbuat', $trackedConsultations, $uniqueCreatedSessions.' sesi berhasil membuat konsultasi'],
                ['Conversion Rate', $conversionRate.'%', 'Sesi form menjadi konsultasi'],
                ['Akses Chat', $chatOpens, 'Akses pasien per sesi/hari'],
            ] as [$label, $value, $meta])
                <article class="kpi">
                    <span>{{ $label }}</span>
                    <strong style="{{ is_string($value) && strlen($value) > 12 ? 'font-size:23px' : '' }}">
                        {{ $value }}
                    </strong>
                    <small>{{ $meta }}</small>
                </article>
            @endforeach
        </section>

        <section class="grid-2">
            <article class="panel">
                <header class="panel-header">
                    <div>
                        <h2>{{ $trend['title'] }}</h2>
                        <p>Jumlah konsultasi pada periode terpilih</p>
                    </div>
                </header>
                <div class="chart-wrap">
                    <canvas id="trendChart"></canvas>
                </div>
            </article>

            <article class="panel">
                <header class="panel-header">
                    <div>
                        <h2>Jenis Konsultasi</h2>
                        <p>Resep dibanding non resep</p>
                    </div>
                </header>

                @php
                    $typeTotal = max(1, $resep + $nonResep);
                    $resepPercent = round(($resep / $typeTotal) * 100, 1);
                @endphp

                <div class="donut-area">
                    <div
                        class="donut"
                        style="--share:{{ $resepPercent }}%"
                    >
                        <div class="donut-value">
                            <strong>{{ $resepPercent }}%</strong>
                            <small>Resep dokter</small>
                        </div>
                    </div>

                    <div class="legend">
                        <div class="legend-row">
                            <span class="legend-name">
                                <i class="dot"></i> Resep Dokter
                            </span>
                            <strong>{{ $resep }}</strong>
                        </div>
                        <div class="legend-row">
                            <span class="legend-name">
                                <i class="dot gray"></i> Non Resep
                            </span>
                            <strong>{{ $nonResep }}</strong>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <section class="busy-grid">
            @foreach ([
                ['Hari Tersibuk', $busyMetrics['day']],
                ['Tanggal Tersibuk', $busyMetrics['date']],
                ['Bulan Tersibuk', $busyMetrics['month']],
                ['Jam Tersibuk', $busyMetrics['hour']],
            ] as [$title, $metric])
                <article class="busy">
                    <span>{{ $title }}</span>
                    <strong>{{ $metric['label'] ?? 'Belum ada data' }}</strong>
                    <small>{{ $metric['total'] ?? 0 }} konsultasi</small>
                </article>
            @endforeach
        </section>

        <section class="panel">
            <header class="panel-header">
                <div>
                    <h2>Distribusi Jam Konsultasi</h2>
                    <p>Jam pembuatan konsultasi dalam WIB</p>
                </div>
            </header>

            @php
                $hourMax = max(
                    1,
                    collect($hourlyDistribution)->max('total')
                );
            @endphp

            <div class="hourly">
                @foreach ($hourlyDistribution as $hour)
                    <div class="hour">
                        <div class="bar-wrap">
                            <div
                                class="bar"
                                style="height:{{
                                    max(
                                        2,
                                        ($hour['total'] / $hourMax) * 100
                                    )
                                }}%"
                                data-tooltip="{{ $hour['label'] }} · {{ $hour['total'] }} konsultasi"
                            ></div>
                        </div>
                        <div class="hour-label">{{ $hour['hour'] }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="panel calendar-panel">
            <header class="panel-header">
                <div>
                    <h2>Kalender Kepadatan Konsultasi</h2>
                    <p>{{ $calendar['label'] }} · {{ $calendar['total'] }} konsultasi</p>
                </div>

                <div class="calendar-nav">
                    <a
                        aria-label="Bulan sebelumnya"
                        href="{{ route(
                            'admin.dashboard',
                            array_merge(
                                request()->except([
                                    'calendar_month',
                                    'page',
                                ]),
                                ['calendar_month' => $calendar['previous']]
                            )
                        ) }}"
                    >‹</a>
                    <a
                        aria-label="Bulan berikutnya"
                        href="{{ route(
                            'admin.dashboard',
                            array_merge(
                                request()->except([
                                    'calendar_month',
                                    'page',
                                ]),
                                ['calendar_month' => $calendar['next']]
                            )
                        ) }}"
                    >›</a>
                </div>
            </header>

            <div class="calendar">
                @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $day)
                    <div class="weekday">{{ $day }}</div>
                @endforeach

                @foreach ($calendar['cells'] as $cell)
                    @if ($cell === null)
                        <div class="calendar-blank"></div>
                    @else
                        <button
                            type="button"
                            class="calendar-day i{{ $cell['intensity'] }} {{ $cell['is_today'] ? 'today' : '' }}"
                            data-day='@json($cell)'
                        >
                            <strong>{{ $cell['day'] }}</strong>
                            <small>{{ $cell['total'] }} konsultasi</small>
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="calendar-legend">
                <span><i class="heat"></i>Tidak ada</span>
                <span><i class="heat h1"></i>Rendah</span>
                <span><i class="heat h2"></i>Sedang</span>
                <span><i class="heat h3"></i>Tinggi</span>
                <span><i class="heat h4"></i>Tertinggi</span>
            </div>
        </section>

        <section class="panel table-panel">
            <header class="panel-header" style="padding-bottom:17px">
                <div>
                    <h2>Daftar Konsultasi</h2>
                    <p>Cari dan filter data konsultasi</p>
                </div>
            </header>

            <form
                class="table-filter"
                action="{{ route('admin.dashboard') }}"
                method="GET"
            >
                <input type="hidden" name="period" value="{{ $period }}">
                @if ($period === 'custom')
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                @endif

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau nomor HP..."
                >

                <select name="type">
                    <option value="">Semua jenis</option>
                    <option value="resep" @selected($type === 'resep')>
                        Resep Dokter
                    </option>
                    <option value="non_resep" @selected($type === 'non_resep')>
                        Non Resep
                    </option>
                </select>

                <select name="status">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected($status === 'aktif')>
                        Aktif
                    </option>
                    <option value="selesai" @selected($status === 'selesai')>
                        Selesai
                    </option>
                </select>

                <select name="sort">
                    <option value="latest" @selected($sort === 'latest')>
                        Terbaru
                    </option>
                    <option value="oldest" @selected($sort === 'oldest')>
                        Terlama
                    </option>
                    <option
                        value="last_activity"
                        @selected($sort === 'last_activity')
                    >
                        Aktivitas terakhir
                    </option>
                </select>

                <button class="button" type="submit">Filter</button>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Jenis</th>
                            <th>Dibuat</th>
                            <th>Pesan Terakhir</th>
                            <th>Respons Pertama</th>
                            <th>Status</th>
                            <th>Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultations as $item)
                            @php
                                $created = $item->created_at
                                    ->copy()
                                    ->timezone($timezone);
                                $last = $item->last_message_at
                                    ?->copy()
                                    ->timezone($timezone);
                                $seconds = $item->first_admin_reply_at
                                    ? (int) $item->created_at
                                        ->diffInSeconds(
                                            $item->first_admin_reply_at
                                        )
                                    : null;
                                $response = $seconds === null
                                    ? 'Belum dibalas'
                                    : ($seconds < 60
                                        ? $seconds.' detik'
                                        : intdiv($seconds, 60).' menit');
                            @endphp
                            <tr>
                                <td>
                                    <span class="patient-name">
                                        {{ $item->nama }}
                                    </span>
                                    <span class="sub">
                                        {{ $item->no_hp }} · {{ $item->umur }} tahun
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->jenis_konsultasi === 'resep' ? '' : 'gray' }}">
                                        {{ $item->jenis_konsultasi === 'resep' ? 'Resep' : 'Non Resep' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $created->locale('id')->isoFormat('D MMM YYYY') }}
                                    <span class="sub">{{ $created->format('H.i') }} WIB</span>
                                </td>
                                <td>
                                    @if ($last)
                                        {{ $last->locale('id')->isoFormat('D MMM YYYY') }}
                                        <span class="sub">{{ $last->format('H.i') }} WIB</span>
                                    @else
                                        Belum ada pesan
                                    @endif
                                </td>
                                <td>{{ $response }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'aktif' ? 'amber' : 'gray' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->messages_count }}</td>
                                <td>
                                    <a
                                        class="chat-link"
                                        href="{{ route('chat.show', $item) }}"
                                    >
                                        Buka Chat →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty">
                                    Tidak ada konsultasi pada filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($consultations->hasPages())
                <div class="pagination">
                    <div class="pager">
                        <span>
                            Halaman {{ $consultations->currentPage() }}
                            dari {{ $consultations->lastPage() }}
                        </span>

                        <div class="pager-links">
                            @if ($consultations->onFirstPage())
                                <span class="disabled">←</span>
                            @else
                                <a href="{{ $consultations->previousPageUrl() }}">←</a>
                            @endif

                            @foreach (range(
                                max(1, $consultations->currentPage() - 2),
                                min(
                                    $consultations->lastPage(),
                                    $consultations->currentPage() + 2
                                )
                            ) as $pageNumber)
                                @if ($pageNumber === $consultations->currentPage())
                                    <span class="current">{{ $pageNumber }}</span>
                                @else
                                    <a href="{{ $consultations->url($pageNumber) }}">
                                        {{ $pageNumber }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($consultations->hasMorePages())
                                <a href="{{ $consultations->nextPageUrl() }}">→</a>
                            @else
                                <span class="disabled">→</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </main>

    <div class="modal-bg" id="calendarModal">
        <div class="modal">
            <header class="modal-head">
                <div>
                    <h3 id="modalTitle">Detail Tanggal</h3>
                    <p>Rincian kepadatan konsultasi</p>
                </div>
                <button
                    class="modal-close"
                    id="modalClose"
                    type="button"
                    aria-label="Tutup"
                >×</button>
            </header>

            <div class="modal-grid">
                <div class="modal-stat">
                    <span>Total Konsultasi</span>
                    <strong id="modalTotal">0</strong>
                </div>
                <div class="modal-stat">
                    <span>Resep Dokter</span>
                    <strong id="modalResep">0</strong>
                </div>
                <div class="modal-stat">
                    <span>Non Resep</span>
                    <strong id="modalNonResep">0</strong>
                </div>
                <div class="modal-stat">
                    <span>Jam Tersibuk</span>
                    <strong id="modalHour">-</strong>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const labels = @json($trend['labels']);
            const values = @json($trend['values']);
            const canvas = document.getElementById('trendChart');

            function drawChart() {
                const rect = canvas.getBoundingClientRect();
                const ratio = window.devicePixelRatio || 1;
                const width = Math.max(320, rect.width);
                const height = Math.max(245, rect.height);
                canvas.width = width * ratio;
                canvas.height = height * ratio;

                const ctx = canvas.getContext('2d');
                ctx.scale(ratio, ratio);
                ctx.clearRect(0, 0, width, height);

                const p = { top:20, right:18, bottom:48, left:40 };
                const chartW = width - p.left - p.right;
                const chartH = height - p.top - p.bottom;
                const max = Math.max(1, ...values);

                ctx.font = '10px system-ui';
                ctx.fillStyle = '#64748b';
                ctx.strokeStyle = '#e2e8f0';

                for (let i = 0; i <= 4; i++) {
                    const y = p.top + chartH * i / 4;
                    ctx.beginPath();
                    ctx.moveTo(p.left, y);
                    ctx.lineTo(width - p.right, y);
                    ctx.stroke();
                    ctx.fillText(
                        String(Math.round(max - max * i / 4)),
                        5,
                        y + 3
                    );
                }

                if (!values.length) return;

                const step = values.length > 1
                    ? chartW / (values.length - 1)
                    : chartW;
                const points = values.map((v, i) => ({
                    x: p.left + (values.length > 1 ? step * i : chartW / 2),
                    y: p.top + chartH - (v / max) * chartH,
                }));

                const gradient = ctx.createLinearGradient(
                    0,
                    p.top,
                    0,
                    p.top + chartH
                );
                gradient.addColorStop(0, 'rgba(5,150,105,.27)');
                gradient.addColorStop(1, 'rgba(5,150,105,0)');

                ctx.beginPath();
                ctx.moveTo(points[0].x, p.top + chartH);
                points.forEach(point => ctx.lineTo(point.x, point.y));
                ctx.lineTo(points.at(-1).x, p.top + chartH);
                ctx.closePath();
                ctx.fillStyle = gradient;
                ctx.fill();

                ctx.beginPath();
                points.forEach((point, index) => {
                    if (index === 0) ctx.moveTo(point.x, point.y);
                    else ctx.lineTo(point.x, point.y);
                });
                ctx.strokeStyle = '#047857';
                ctx.lineWidth = 3;
                ctx.lineJoin = 'round';
                ctx.stroke();

                points.forEach(point => {
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, 3.5, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    ctx.strokeStyle = '#047857';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                });

                const maxLabels = width < 650 ? 6 : 12;
                const every = Math.max(
                    1,
                    Math.ceil(labels.length / maxLabels)
                );

                labels.forEach((label, index) => {
                    if (
                        index % every !== 0
                        && index !== labels.length - 1
                    ) return;

                    ctx.save();
                    ctx.translate(points[index].x, height - 15);
                    ctx.rotate(-.35);
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'right';
                    ctx.fillText(label, 0, 0);
                    ctx.restore();
                });
            }

            let timer;
            window.addEventListener('resize', () => {
                clearTimeout(timer);
                timer = setTimeout(drawChart, 120);
            });
            drawChart();

            const modal = document.getElementById('calendarModal');
            const close = () => modal.classList.remove('open');

            document.querySelectorAll('[data-day]').forEach(button => {
                button.addEventListener('click', () => {
                    const data = JSON.parse(button.dataset.day);
                    document.getElementById('modalTitle').textContent =
                        data.date_label;
                    document.getElementById('modalTotal').textContent =
                        data.total;
                    document.getElementById('modalResep').textContent =
                        data.resep;
                    document.getElementById('modalNonResep').textContent =
                        data.non_resep;
                    document.getElementById('modalHour').textContent =
                        data.busiest_hour;
                    modal.classList.add('open');
                });
            });

            document.getElementById('modalClose')
                .addEventListener('click', close);

            modal.addEventListener('click', event => {
                if (event.target === modal) close();
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') close();
            });
        })();
    </script>
</body>
</html>
