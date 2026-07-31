<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Dashboard Konsultasi — MD Farma</title>

    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-500:#10b981;
            --green-200:#a7f3d0;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --amber-700:#b45309;
            --amber-100:#fef3c7;
            --amber-50:#fffbeb;
            --slate-950:#0f172a;
            --slate-800:#1e293b;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-400:#94a3b8;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --slate-50:#f8fafc;
            --white:#fff;
        }

        * { box-sizing:border-box; }

        html { scroll-behavior:smooth; }

        body {
            min-height:100vh;
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:
                radial-gradient(circle at 91% 2%,rgba(16,185,129,.14),transparent 26%),
                linear-gradient(180deg,#f8fafc 0%,#f4f8f6 100%);
        }

        button,
        a { -webkit-tap-highlight-color:transparent; }

        .topbar {
            position:sticky;
            top:0;
            z-index:20;
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(16px);
        }

        nav {
            width:min(1180px,92%);
            min-height:70px;
            margin:auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
        }

        nav a { text-decoration:none; }

        .brand {
            display:flex;
            align-items:center;
            gap:10px;
            color:var(--slate-950);
            font-weight:900;
        }

        .brand-mark {
            width:36px;
            height:36px;
            display:grid;
            place-items:center;
            border-radius:11px;
            background:var(--green-700);
            color:#fff;
            font-size:21px;
            box-shadow:0 8px 20px rgba(4,120,87,.2);
        }

        .back {
            color:var(--slate-700);
            font-size:13px;
            font-weight:800;
        }

        .page {
            width:min(1180px,92%);
            margin:34px auto 72px;
        }

        .hero {
            position:relative;
            overflow:hidden;
            display:grid;
            grid-template-columns:minmax(0,1.35fr) minmax(250px,.65fr);
            gap:34px;
            align-items:end;
            padding:34px;
            border-radius:27px;
            color:#fff;
            background:
                radial-gradient(circle at 95% 8%,rgba(255,255,255,.15),transparent 30%),
                radial-gradient(circle at 70% 120%,rgba(16,185,129,.3),transparent 37%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 25px 70px rgba(6,78,59,.2);
        }

        .hero::after {
            content:"";
            position:absolute;
            right:-80px;
            top:-110px;
            width:250px;
            height:250px;
            border:1px solid rgba(255,255,255,.13);
            border-radius:50%;
        }

        .hero-copy,
        .hero-actions { position:relative; z-index:1; }

        .eyebrow {
            margin:0 0 10px;
            color:var(--green-200);
            font-size:11px;
            font-weight:900;
            letter-spacing:.1em;
            text-transform:uppercase;
        }

        .hero h1 {
            max-width:700px;
            margin:0;
            font-size:clamp(30px,5vw,48px);
            line-height:1.04;
            letter-spacing:-.045em;
        }

        .hero-copy > p:last-child {
            max-width:640px;
            margin:16px 0 0;
            color:#d1fae5;
            font-size:14px;
            line-height:1.7;
        }

        .hero-actions {
            display:grid;
            gap:11px;
        }

        .button {
            min-height:48px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:9px;
            padding:0 18px;
            border:1px solid transparent;
            border-radius:13px;
            text-decoration:none;
            font:inherit;
            font-size:13px;
            font-weight:900;
            cursor:pointer;
            transition:.18s ease;
        }

        .button:hover { transform:translateY(-1px); }

        .button.primary {
            background:#fff;
            color:var(--green-900);
            box-shadow:0 12px 30px rgba(0,0,0,.12);
        }

        .button.ghost {
            border-color:rgba(255,255,255,.24);
            background:rgba(255,255,255,.08);
            color:#fff;
        }

        .button.outline {
            border-color:var(--slate-300);
            background:#fff;
            color:var(--slate-700);
        }

        .button.danger-soft {
            border-color:#fecaca;
            background:#fff;
            color:#991b1b;
        }

        .notice {
            margin:18px 0 0;
            padding:13px 15px;
            border:1px solid var(--green-200);
            border-radius:13px;
            background:var(--green-50);
            color:var(--green-900);
            font-size:12px;
            line-height:1.55;
        }

        .notice.warning {
            border-color:#fde68a;
            background:#fffbeb;
            color:#92400e;
        }

        .dashboard-grid {
            display:grid;
            grid-template-columns:minmax(0,1.55fr) minmax(285px,.75fr);
            gap:24px;
            margin-top:24px;
            align-items:start;
        }

        .main-column,
        .side-column {
            display:grid;
            gap:24px;
        }

        .stats {
            display:grid;
            grid-template-columns:repeat(3,minmax(0,1fr));
            gap:14px;
        }

        .stat,
        .panel {
            border:1px solid var(--slate-200);
            background:#fff;
            box-shadow:0 14px 45px rgba(15,23,42,.06);
        }

        .stat {
            padding:19px;
            border-radius:18px;
        }

        .stat span {
            display:block;
            color:var(--slate-500);
            font-size:11px;
            font-weight:800;
        }

        .stat strong {
            display:block;
            margin-top:6px;
            font-size:28px;
            letter-spacing:-.035em;
        }

        .panel {
            overflow:hidden;
            border-radius:22px;
        }

        .panel-head {
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:18px;
            padding:22px 23px 17px;
            border-bottom:1px solid var(--slate-100);
        }

        .panel-head h2 {
            margin:0;
            font-size:18px;
            letter-spacing:-.02em;
        }

        .panel-head p {
            margin:6px 0 0;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.5;
        }

        .panel-link {
            flex:0 0 auto;
            color:var(--green-700);
            text-decoration:none;
            font-size:12px;
            font-weight:900;
        }

        .consultation-list {
            display:grid;
        }

        .consultation-item {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:17px;
            align-items:center;
            padding:19px 23px;
            border-bottom:1px solid var(--slate-100);
        }

        .consultation-item:last-child { border-bottom:0; }

        .consultation-main { min-width:0; }

        .item-topline {
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:8px;
            margin-bottom:7px;
        }

        .item-topline strong {
            font-size:14px;
        }

        .badge {
            display:inline-flex;
            align-items:center;
            min-height:24px;
            padding:0 9px;
            border-radius:999px;
            font-size:10px;
            font-weight:900;
            letter-spacing:.02em;
        }

        .badge.active {
            background:var(--green-100);
            color:var(--green-900);
        }

        .badge.waiting {
            background:var(--amber-100);
            color:var(--amber-700);
        }

        .badge.done {
            background:var(--slate-100);
            color:var(--slate-600);
        }

        .item-meta {
            display:flex;
            flex-wrap:wrap;
            gap:6px 12px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .item-meta span {
            display:inline-flex;
            align-items:center;
            gap:5px;
        }

        .item-action {
            min-height:39px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 14px;
            border:1px solid var(--slate-200);
            border-radius:11px;
            background:#fff;
            color:var(--green-800);
            text-decoration:none;
            font-size:11px;
            font-weight:900;
            white-space:nowrap;
            transition:.18s ease;
        }

        .item-action:hover {
            border-color:var(--green-500);
            background:var(--green-50);
        }

        .item-action.disabled {
            border-color:var(--slate-200);
            background:var(--slate-100);
            color:var(--slate-400);
            cursor:not-allowed;
            pointer-events:none;
        }

        .empty-state {
            padding:30px 23px;
            text-align:center;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.6;
        }

        .quick-actions {
            display:grid;
            gap:12px;
            padding:19px;
        }

        .quick-action {
            display:flex;
            align-items:center;
            gap:13px;
            padding:15px;
            border:1px solid var(--slate-200);
            border-radius:15px;
            color:inherit;
            text-decoration:none;
            transition:.18s ease;
        }

        .quick-action:hover {
            transform:translateY(-1px);
            border-color:var(--green-500);
            background:var(--green-50);
        }

        .quick-icon {
            flex:0 0 auto;
            width:42px;
            height:42px;
            display:grid;
            place-items:center;
            border-radius:12px;
            background:var(--green-100);
            color:var(--green-800);
        }

        .quick-icon svg {
            width:20px;
            height:20px;
        }

        .quick-copy { min-width:0; flex:1; }

        .quick-copy strong {
            display:block;
            font-size:13px;
        }

        .quick-copy span {
            display:block;
            margin-top:3px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.4;
        }

        .quick-arrow {
            color:var(--green-700);
            font-size:20px;
            font-weight:900;
        }

        .security-body {
            display:grid;
            gap:15px;
            padding:20px 22px 22px;
        }

        .security-row {
            display:flex;
            align-items:flex-start;
            gap:11px;
        }

        .security-dot {
            flex:0 0 auto;
            width:9px;
            height:9px;
            margin-top:5px;
            border-radius:50%;
            background:var(--green-500);
            box-shadow:0 0 0 4px var(--green-100);
        }

        .security-row strong {
            display:block;
            font-size:12px;
        }

        .security-row span {
            display:block;
            margin-top:3px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .security-note {
            padding:13px 14px;
            border-radius:12px;
            background:var(--slate-100);
            color:var(--slate-600);
            font-size:11px;
            line-height:1.55;
        }

        .lock-form { margin:0; }
        .lock-form .button { width:100%; }

        .privacy-card {
            padding:20px 22px;
            border:1px solid #fde68a;
            border-radius:20px;
            background:var(--amber-50);
            color:#78350f;
        }

        .privacy-card strong {
            display:block;
            margin-bottom:7px;
            font-size:13px;
        }

        .privacy-card p {
            margin:0;
            font-size:11px;
            line-height:1.6;
        }

        @media (max-width:900px) {
            .hero { grid-template-columns:1fr; }
            .hero-actions {
                grid-template-columns:repeat(2,minmax(0,1fr));
            }
            .dashboard-grid { grid-template-columns:1fr; }
            .side-column { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .privacy-card { grid-column:1 / -1; }
        }

        @media (max-width:650px) {
            .page { width:min(94%,1180px); margin-top:20px; }
            .hero { padding:25px 21px; border-radius:22px; }
            .hero-actions { grid-template-columns:1fr; }
            .stats { grid-template-columns:repeat(3,minmax(0,1fr)); gap:8px; }
            .stat { padding:15px 12px; border-radius:15px; }
            .stat strong { font-size:23px; }
            .consultation-item {
                grid-template-columns:1fr;
                padding:18px;
            }
            .item-action { width:100%; }
            .panel-head { padding:20px 18px 15px; }
            .panel-link { display:none; }
            .side-column { grid-template-columns:1fr; }
            .privacy-card { grid-column:auto; }
        }

        @media (max-width:410px) {
            nav { width:94%; }
            .back { font-size:11px; }
            .brand span:last-child { display:none; }
            .stats { grid-template-columns:1fr; }
            .stat {
                display:flex;
                align-items:center;
                justify-content:space-between;
            }
            .stat strong { margin:0; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <nav>
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">+</span>
                <span>MD Farma</span>
            </a>
            <a class="back" href="{{ route('home') }}">
                ← Kembali ke Beranda
            </a>
        </nav>
    </header>

    <main class="page">
        <section class="hero" aria-labelledby="dashboard-title">
            <div class="hero-copy">
                <p class="eyebrow">Dashboard konsultasi</p>
                <h1 id="dashboard-title">
                    Kelola konsultasi Anda dalam satu tempat.
                </h1>
                <p>
                    Lanjutkan percakapan aktif, buat konsultasi baru,
                    atau buka kembali konsultasi yang pernah dilakukan
                    melalui perangkat ini.
                </p>
            </div>

            <div class="hero-actions">
                @if ($activeConsultations->isNotEmpty())
                    <a
                        class="button primary"
                        href="{{ route('chat.show', $activeConsultations->first()) }}"
                    >
                        Lanjutkan chat aktif
                    </a>
                @else
                    <a
                        class="button primary"
                        href="{{ route('consultation.create') }}"
                    >
                        Mulai konsultasi
                    </a>
                @endif

                <a class="button ghost" href="{{ route('consultation.history') }}">
                    Lihat semua riwayat
                </a>
            </div>
        </section>

        @if (session('status'))
            <div class="notice" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="notice warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        <div class="dashboard-grid">
            <div class="main-column">
                <section class="stats" aria-label="Ringkasan konsultasi">
                    <article class="stat">
                        <span>Total konsultasi</span>
                        <strong>{{ $consultationTotal }}</strong>
                    </article>
                    <article class="stat">
                        <span>Masih aktif</span>
                        <strong>{{ $activeTotal }}</strong>
                    </article>
                    <article class="stat">
                        <span>Sudah selesai</span>
                        <strong>{{ $completedTotal }}</strong>
                    </article>
                </section>

                <section class="panel" aria-labelledby="active-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="active-title">Konsultasi aktif</h2>
                            <p>Percakapan yang masih dapat dilanjutkan.</p>
                        </div>
                    </header>

                    @if ($activeConsultations->isEmpty())
                        <div class="empty-state">
                            Tidak ada konsultasi aktif saat ini.<br>
                            Mulai konsultasi baru ketika Anda memerlukan bantuan.
                        </div>
                    @else
                        <div class="consultation-list">
                            @foreach ($activeConsultations as $consultation)
                                @php
                                    $activityAt = $consultation->last_message_at
                                        ?? $consultation->created_at;
                                    $waitingForAdmin = $consultation->last_message_sender
                                        === 'patient';
                                    $statusLabel = $waitingForAdmin
                                        ? 'Menunggu apoteker'
                                        : ($consultation->last_message_sender === 'admin'
                                            ? 'Menunggu Anda'
                                            : 'Baru');
                                    $typeLabel = $consultation->jenis_konsultasi === 'resep'
                                        ? 'Dengan resep'
                                        : 'Tanpa resep';
                                @endphp

                                <article class="consultation-item">
                                    <div class="consultation-main">
                                        <div class="item-topline">
                                            <strong>{{ $consultation->nama }}</strong>
                                            <span class="badge {{ $waitingForAdmin ? 'waiting' : 'active' }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="item-meta">
                                            <span>{{ $typeLabel }}</span>
                                            <span>
                                                Aktivitas {{ $activityAt
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y, H.i') }} WIB
                                            </span>
                                        </div>
                                    </div>
                                    <a
                                        class="item-action"
                                        href="{{ route('chat.show', $consultation) }}"
                                    >
                                        Buka chat
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section
                    class="panel"
                    id="riwayat-terbaru"
                    aria-labelledby="recent-title"
                >
                    <header class="panel-head">
                        <div>
                            <h2 id="recent-title">Riwayat terbaru</h2>
                            <p>Enam konsultasi terakhir pada akses riwayat Anda.</p>
                        </div>
                        <a
                            class="panel-link"
                            href="{{ route('consultation.history') }}"
                        >
                            Lihat semua
                        </a>
                    </header>

                    <div class="consultation-list">
                        @foreach ($recentConsultations as $consultation)
                            @php
                                $activityAt = $consultation->last_message_at
                                    ?? $consultation->created_at;
                                $isActive = $consultation->status === 'aktif';
                                $isArchived = $consultation
                                    ->isPatientHistoryArchived();
                                $availableUntil = $consultation
                                    ->patientHistoryAvailableUntil();
                                $typeLabel = $consultation->jenis_konsultasi === 'resep'
                                    ? 'Dengan resep'
                                    : 'Tanpa resep';
                                $statusLabel = $isActive
                                    ? 'Aktif'
                                    : ($isArchived ? 'Diarsipkan' : 'Selesai');
                            @endphp

                            <article class="consultation-item">
                                <div class="consultation-main">
                                    <div class="item-topline">
                                        <strong>{{ $consultation->nama }}</strong>
                                        <span class="badge {{ $isActive ? 'active' : 'done' }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <div class="item-meta">
                                        <span>{{ $typeLabel }}</span>
                                        <span>
                                            {{ $activityAt
                                                ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                ->format('d M Y, H.i') }} WIB
                                        </span>
                                        @if (! $isActive && $availableUntil)
                                            <span>
                                                {{ $isArchived
                                                    ? 'Akses pasien berakhir'
                                                    : 'Tersedia sampai' }}
                                                {{ $availableUntil
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if ($isArchived)
                                    <span
                                        class="item-action disabled"
                                        aria-disabled="true"
                                    >
                                        Arsip internal
                                    </span>
                                @else
                                    <a
                                        class="item-action"
                                        href="{{ route('chat.show', $consultation) }}"
                                    >
                                        {{ $isActive ? 'Buka chat' : 'Buka riwayat' }}
                                    </a>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="side-column">
                <section class="panel" aria-labelledby="quick-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="quick-title">Menu utama</h2>
                            <p>Akses cepat untuk kebutuhan konsultasi.</p>
                        </div>
                    </header>

                    <div class="quick-actions">
                        <a
                            class="quick-action"
                            href="{{ route('consultation.create') }}"
                        >
                            <span class="quick-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="quick-copy">
                                <strong>Konsultasi baru</strong>
                                <span>Buat ruang chat untuk kebutuhan baru.</span>
                            </span>
                            <span class="quick-arrow" aria-hidden="true">›</span>
                        </a>

                        <a
                            class="quick-action"
                            href="{{ route('consultation.history') }}"
                        >
                            <span class="quick-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                                    <path d="M3 4v6h6M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="quick-copy">
                                <strong>Riwayat konsultasi</strong>
                                <span>Lihat seluruh konsultasi aktif dan selesai.</span>
                            </span>
                            <span class="quick-arrow" aria-hidden="true">›</span>
                        </a>
                    </div>
                </section>

                <section class="panel" aria-labelledby="security-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="security-title">Keamanan akses</h2>
                            <p>Status perlindungan pada browser ini.</p>
                        </div>
                    </header>

                    <div class="security-body">
                        <div class="security-row">
                            <span class="security-dot" aria-hidden="true"></span>
                            <div>
                                <strong>Password Riwayat aktif</strong>
                                <span>
                                    Isi konsultasi hanya terbuka setelah password diverifikasi.
                                </span>
                            </div>
                        </div>

                        <div class="security-row">
                            <span class="security-dot" aria-hidden="true"></span>
                            <div>
                                <strong>Perangkat ini dikenali</strong>
                                <span>
                                    @if ($deviceExpiresAt)
                                        Akses perangkat berlaku sampai
                                        {{ $deviceExpiresAt
                                            ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                            ->format('d M Y, H.i') }} WIB.
                                    @else
                                        Masa akses perangkat mengikuti kebijakan MD Farma.
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="security-note">
                            Kunci riwayat setelah selesai apabila perangkat ini
                            digunakan bersama orang lain. Cookie perangkat tetap
                            tersimpan, tetapi password akan diminta kembali.
                        </div>

                        <form
                            class="lock-form"
                            method="POST"
                            action="{{ route('consultation.history.lock') }}"
                        >
                            @csrf
                            <button class="button danger-soft" type="submit">
                                Kunci riwayat sekarang
                            </button>
                        </form>
                    </div>
                </section>

                <section class="privacy-card" aria-label="Informasi privasi">
                    <strong>Jaga privasi konsultasi</strong>
                    <p>
                        Jangan membagikan Password Riwayat kepada orang lain.
                        Hindari menyimpan akses pada komputer umum atau browser
                        yang digunakan bersama. Isi chat konsultasi selesai dapat
                        dibuka selama {{ $patientHistoryDays }} hari, kemudian hanya
                        tersedia sebagai arsip internal MD Farma.
                    </p>
                </section>
            </aside>
        </div>
    </main>
</body>
</html>
