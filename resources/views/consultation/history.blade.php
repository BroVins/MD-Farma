<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Riwayat Konsultasi — MD Farma</title>

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

        body {
            min-height:100vh;
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:
                radial-gradient(circle at 92% 0%,rgba(16,185,129,.12),transparent 27%),
                linear-gradient(180deg,#f8fafc 0%,#f3f7f5 100%);
        }

        a,
        button { -webkit-tap-highlight-color:transparent; }

        .topbar {
            position:sticky;
            top:0;
            z-index:20;
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(16px);
        }

        nav {
            width:min(1080px,92%);
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
            width:min(1080px,92%);
            margin:32px auto 72px;
        }

        .hero {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:24px;
            align-items:end;
            padding:30px;
            border-radius:25px;
            color:#fff;
            background:
                radial-gradient(circle at 95% 5%,rgba(255,255,255,.15),transparent 29%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 22px 62px rgba(6,78,59,.18);
        }

        .eyebrow {
            margin:0 0 9px;
            color:var(--green-200);
            font-size:11px;
            font-weight:900;
            letter-spacing:.1em;
            text-transform:uppercase;
        }

        .hero h1 {
            margin:0;
            font-size:clamp(29px,5vw,44px);
            line-height:1.06;
            letter-spacing:-.04em;
        }

        .hero-copy > p:last-child {
            max-width:650px;
            margin:14px 0 0;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .button {
            min-height:47px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:0 18px;
            border:1px solid transparent;
            border-radius:13px;
            text-decoration:none;
            font-size:13px;
            font-weight:900;
            transition:.18s ease;
        }

        .button:hover { transform:translateY(-1px); }

        .button.primary {
            background:#fff;
            color:var(--green-900);
            box-shadow:0 12px 28px rgba(0,0,0,.12);
        }

        .summary {
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:13px;
            margin-top:20px;
        }

        .summary-card {
            padding:18px 19px;
            border:1px solid var(--slate-200);
            border-radius:17px;
            background:#fff;
            box-shadow:0 12px 35px rgba(15,23,42,.05);
        }

        .summary-card span {
            display:block;
            color:var(--slate-500);
            font-size:11px;
            font-weight:800;
        }

        .summary-card strong {
            display:block;
            margin-top:5px;
            font-size:27px;
            letter-spacing:-.035em;
        }

        .history-panel {
            margin-top:20px;
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:22px;
            background:#fff;
            box-shadow:0 16px 48px rgba(15,23,42,.06);
        }

        .panel-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
            padding:21px 22px;
            border-bottom:1px solid var(--slate-100);
        }

        .panel-head h2 {
            margin:0;
            font-size:18px;
            letter-spacing:-.02em;
        }

        .panel-head p {
            margin:5px 0 0;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .filters {
            display:flex;
            flex-wrap:wrap;
            gap:8px;
        }

        .filter {
            min-height:36px;
            display:inline-flex;
            align-items:center;
            padding:0 13px;
            border:1px solid var(--slate-200);
            border-radius:999px;
            color:var(--slate-600);
            background:#fff;
            text-decoration:none;
            font-size:11px;
            font-weight:900;
        }

        .filter.active {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .history-list { display:grid; }

        .history-item {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:20px;
            align-items:center;
            padding:21px 22px;
            border-bottom:1px solid var(--slate-100);
        }

        .history-item:last-child { border-bottom:0; }

        .history-main { min-width:0; }

        .topline {
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:8px;
            margin-bottom:8px;
        }

        .topline strong {
            font-size:14px;
            overflow-wrap:anywhere;
        }

        .badge {
            min-height:24px;
            display:inline-flex;
            align-items:center;
            padding:0 9px;
            border-radius:999px;
            font-size:10px;
            font-weight:900;
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

        .badge.archived {
            border:1px solid var(--slate-300);
            background:var(--slate-100);
            color:var(--slate-600);
        }

        .meta {
            display:flex;
            flex-wrap:wrap;
            gap:6px 13px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .meta span {
            display:inline-flex;
            align-items:center;
            gap:5px;
        }

        .open-link {
            min-height:40px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 15px;
            border:1px solid var(--slate-200);
            border-radius:11px;
            color:var(--green-800);
            background:#fff;
            text-decoration:none;
            font-size:11px;
            font-weight:900;
            white-space:nowrap;
            transition:.18s ease;
        }

        .open-link:hover {
            border-color:var(--green-500);
            background:var(--green-50);
        }

        .open-link.disabled {
            border-color:var(--slate-200);
            background:var(--slate-100);
            color:var(--slate-400);
            cursor:not-allowed;
            pointer-events:none;
        }

        .page-alert {
            margin-top:18px;
            padding:14px 16px;
            border:1px solid #fde68a;
            border-radius:14px;
            color:#92400e;
            background:#fffbeb;
            font-size:12px;
            line-height:1.6;
        }

        .empty {
            padding:48px 22px;
            text-align:center;
        }

        .empty-icon {
            width:54px;
            height:54px;
            margin:0 auto 13px;
            display:grid;
            place-items:center;
            border-radius:17px;
            background:var(--green-100);
            color:var(--green-800);
            font-size:25px;
        }

        .empty strong {
            display:block;
            font-size:14px;
        }

        .empty p {
            max-width:460px;
            margin:7px auto 0;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.6;
        }

        .pagination {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            padding:17px 22px;
            border-top:1px solid var(--slate-100);
            background:var(--slate-50);
        }

        .pagination span {
            color:var(--slate-500);
            font-size:11px;
            font-weight:800;
            text-align:center;
        }

        .page-link {
            min-height:37px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 13px;
            border:1px solid var(--slate-200);
            border-radius:10px;
            color:var(--slate-700);
            background:#fff;
            text-decoration:none;
            font-size:11px;
            font-weight:900;
        }

        .page-link.disabled {
            color:var(--slate-400);
            background:var(--slate-100);
            pointer-events:none;
        }

        .privacy-note {
            margin-top:18px;
            padding:15px 17px;
            border:1px solid var(--green-200);
            border-radius:15px;
            color:var(--green-900);
            background:var(--green-50);
            font-size:11px;
            line-height:1.6;
        }

        @media (max-width:720px) {
            .hero { grid-template-columns:1fr; padding:25px 21px; }
            .button.primary { width:100%; }
            .panel-head { align-items:flex-start; flex-direction:column; }
            .summary { gap:8px; }
            .summary-card { padding:15px 13px; }
            .summary-card strong { font-size:23px; }
            .history-item { grid-template-columns:1fr; padding:19px 18px; }
            .open-link { width:100%; }
            .pagination { padding:15px 18px; }
        }

        @media (max-width:430px) {
            nav { width:94%; }
            .brand span:last-child { display:none; }
            .back { font-size:11px; }
            .page { width:94%; margin-top:20px; }
            .summary { grid-template-columns:1fr; }
            .summary-card {
                display:flex;
                align-items:center;
                justify-content:space-between;
            }
            .summary-card strong { margin:0; }
            .pagination { flex-wrap:wrap; }
            .pagination span { order:-1; width:100%; }
            .page-link { flex:1; }
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
            <a class="back" href="{{ route('consultation.entry') }}">
                ← Dashboard konsultasi
            </a>
        </nav>
    </header>

    <main class="page">
        <section class="hero" aria-labelledby="history-title">
            <div class="hero-copy">
                <p class="eyebrow">Riwayat konsultasi</p>
                <h1 id="history-title">
                    Seluruh konsultasi Anda.
                </h1>
                <p>
                    Daftar ini hanya menampilkan ringkasan. Isi pesan dan
                    lampiran baru terlihat setelah Anda membuka konsultasi.
                </p>
            </div>
            <a
                class="button primary"
                href="{{ route('consultation.create') }}"
            >
                + Konsultasi baru
            </a>
        </section>

        @if (session('warning'))
            <div class="page-alert" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        <div class="privacy-note">
            Isi pesan dan lampiran konsultasi selesai dapat diakses pasien
            selama <strong>{{ $patientHistoryDays }} hari</strong> sejak
            konsultasi ditutup. Setelah itu, riwayat tidak lagi dapat dibuka
            dari dashboard pasien dan tetap disimpan sebagai arsip internal
            MD Farma sesuai kebijakan retensi.
        </div>

        <section class="summary" aria-label="Ringkasan riwayat">
            <article class="summary-card">
                <span>Semua konsultasi</span>
                <strong>{{ $consultationTotal }}</strong>
            </article>
            <article class="summary-card">
                <span>Masih aktif</span>
                <strong>{{ $activeTotal }}</strong>
            </article>
            <article class="summary-card">
                <span>Sudah selesai</span>
                <strong>{{ $completedTotal }}</strong>
            </article>
            <article class="summary-card">
                <span>Diarsipkan</span>
                <strong>{{ $archivedTotal }}</strong>
            </article>
        </section>

        <section class="history-panel" aria-labelledby="list-title">
            <header class="panel-head">
                <div>
                    <h2 id="list-title">Daftar konsultasi</h2>
                    <p>
                        Diurutkan berdasarkan aktivitas terbaru.
                    </p>
                </div>

                <div class="filters" aria-label="Filter status konsultasi">
                    <a
                        class="filter {{ $selectedStatus === 'semua' ? 'active' : '' }}"
                        href="{{ route('consultation.history', ['status' => 'semua']) }}"
                    >
                        Semua
                    </a>
                    <a
                        class="filter {{ $selectedStatus === 'aktif' ? 'active' : '' }}"
                        href="{{ route('consultation.history', ['status' => 'aktif']) }}"
                    >
                        Aktif
                    </a>
                    <a
                        class="filter {{ $selectedStatus === 'selesai' ? 'active' : '' }}"
                        href="{{ route('consultation.history', ['status' => 'selesai']) }}"
                    >
                        Selesai
                    </a>
                </div>
            </header>

            @if ($consultations->isEmpty())
                <div class="empty">
                    <div class="empty-icon" aria-hidden="true">↺</div>
                    <strong>Belum ada konsultasi pada filter ini.</strong>
                    <p>
                        Pilih filter lain atau mulai konsultasi baru ketika
                        Anda membutuhkan bantuan dari MD Farma.
                    </p>
                </div>
            @else
                <div class="history-list">
                    @foreach ($consultations as $consultation)
                        @php
                            $activityAt = $consultation->last_message_at
                                ?? $consultation->created_at;
                            $isActive = $consultation->status === 'aktif';
                            $isArchived = $consultation
                                ->isPatientHistoryArchived();
                            $availableUntil = $consultation
                                ->patientHistoryAvailableUntil();
                            $waitingForAdmin = $isActive
                                && $consultation->last_message_sender === 'patient';
                            $statusLabel = $isActive
                                ? ($waitingForAdmin
                                    ? 'Menunggu apoteker'
                                    : ($consultation->last_message_sender === 'admin'
                                        ? 'Menunggu Anda'
                                        : 'Aktif'))
                                : ($isArchived ? 'Diarsipkan' : 'Selesai');
                            $statusClass = $isArchived
                                ? 'archived'
                                : (! $isActive
                                    ? 'done'
                                    : ($waitingForAdmin ? 'waiting' : 'active'));
                            $typeLabel = $consultation->jenis_konsultasi === 'resep'
                                ? 'Dengan resep'
                                : 'Tanpa resep';
                        @endphp

                        <article class="history-item">
                            <div class="history-main">
                                <div class="topline">
                                    <strong>{{ $consultation->nama }}</strong>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="meta">
                                    <span>{{ $typeLabel }}</span>
                                    <span>Usia {{ $consultation->umur }} tahun</span>
                                    <span>
                                        Aktivitas
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
                                                ->format('d M Y, H.i') }} WIB
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if ($isArchived)
                                <span
                                    class="open-link disabled"
                                    aria-disabled="true"
                                >
                                    Arsip internal
                                </span>
                            @else
                                <a
                                    class="open-link"
                                    href="{{ route('chat.show', $consultation) }}"
                                >
                                    {{ $isActive ? 'Buka chat' : 'Buka riwayat' }}
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($consultations->hasPages())
                    <nav class="pagination" aria-label="Navigasi halaman riwayat">
                        @if ($consultations->onFirstPage())
                            <span class="page-link disabled">← Sebelumnya</span>
                        @else
                            <a
                                class="page-link"
                                href="{{ $consultations->previousPageUrl() }}"
                            >
                                ← Sebelumnya
                            </a>
                        @endif

                        <span>
                            Halaman {{ $consultations->currentPage() }}
                            dari {{ $consultations->lastPage() }}
                        </span>

                        @if ($consultations->hasMorePages())
                            <a
                                class="page-link"
                                href="{{ $consultations->nextPageUrl() }}"
                            >
                                Berikutnya →
                            </a>
                        @else
                            <span class="page-link disabled">Berikutnya →</span>
                        @endif
                    </nav>
                @endif
            @endif
        </section>

        <div class="privacy-note">
            Ringkasan tidak menampilkan isi percakapan, nama obat, keluhan,
            atau lampiran. Riwayat berstatus <strong>Diarsipkan</strong>
            tidak lagi dapat dibuka pasien, tetapi tetap tersimpan untuk
            kebutuhan internal MD Farma sesuai kebijakan retensi dan
            kewenangan akses.
        </div>
    </main>
</body>
</html>
