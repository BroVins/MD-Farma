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
            --green-950:#1f2937;
            --green-900:#172554;
            --green-800:#1e3a8a;
            --green-700:#1238cc;
            --green-600:#2a55df;
            --green-500:#3b82f6;
            --green-200:#bfdbfe;
            --green-100:#dbeafe;
            --green-50:#eff6ff;
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
                radial-gradient(circle at 92% 0%,rgba(59, 130, 246, .12),transparent 27%),
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
            flex-wrap:wrap;
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
            box-shadow:0 8px 20px rgba(18, 56, 204, .2);
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
            box-shadow:0 22px 62px rgba(23, 37, 84, .18);
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
            color:#dbeafe;
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


        .profile-filter {
            display:flex;
            align-items:center;
            gap:8px;
        }

        .profile-filter label {
            margin:0;
            color:var(--slate-500);
            font-size:10px;
            font-weight:900;
        }

        .profile-filter select {
            min-height:36px;
            padding:0 34px 0 12px;
            border:1px solid var(--slate-200);
            border-radius:999px;
            background:#fff;
            color:var(--slate-700);
            font:inherit;
            font-size:11px;
            font-weight:800;
            outline:none;
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

        .archive-actions {
            min-width:180px;
            display:grid;
            justify-items:end;
            gap:8px;
        }

        .request-status {
            display:inline-flex;
            align-items:center;
            padding:6px 9px;
            border-radius:999px;
            font-size:10px;
            font-weight:900;
            text-align:center;
        }

        .request-status.pending {
            color:#92400e;
            background:#fef3c7;
        }

        .request-status.verifying {
            color:#1e40af;
            background:#dbeafe;
        }

        .request-status.approved {
            color:var(--green-900);
            background:var(--green-100);
        }

        .request-status.rejected {
            color:#991b1b;
            background:#fee2e2;
        }

        .request-status.completed {
            color:var(--slate-700);
            background:var(--slate-100);
        }

        .archive-request-details {
            grid-column:1 / -1;
            width:100%;
            border:1px solid var(--slate-200);
            border-radius:15px;
            background:var(--slate-50);
        }

        .archive-request-details summary {
            min-height:43px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:0 15px;
            color:var(--green-800);
            cursor:pointer;
            font-size:11px;
            font-weight:900;
            list-style:none;
        }

        .archive-request-details summary::-webkit-details-marker {
            display:none;
        }

        .archive-request-details summary::after {
            content:'+';
            font-size:18px;
            line-height:1;
        }

        .archive-request-details[open] summary::after {
            content:'−';
        }

        .archive-request-form {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:13px;
            padding:16px;
            border-top:1px solid var(--slate-200);
            background:#fff;
            border-radius:0 0 15px 15px;
        }

        .archive-field.full { grid-column:1 / -1; }

        .archive-field label {
            display:block;
            margin-bottom:6px;
            color:var(--slate-700);
            font-size:10px;
            font-weight:900;
        }

        .archive-field input,
        .archive-field select,
        .archive-field textarea {
            width:100%;
            border:1px solid var(--slate-300);
            border-radius:10px;
            background:#fff;
            color:var(--slate-950);
            font:inherit;
            outline:none;
        }

        .archive-field input,
        .archive-field select {
            min-height:42px;
            padding:0 11px;
        }

        .archive-field textarea {
            min-height:105px;
            padding:10px 11px;
            resize:vertical;
            line-height:1.55;
        }

        .archive-field input:focus,
        .archive-field select:focus,
        .archive-field textarea:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 3px rgba(42, 85, 223, .12);
        }

        .archive-confirmation {
            grid-column:1 / -1;
            display:flex;
            align-items:flex-start;
            gap:9px;
            color:var(--slate-600);
            font-size:10px;
            line-height:1.55;
        }

        .archive-confirmation input {
            width:16px;
            height:16px;
            margin-top:1px;
            flex:0 0 auto;
        }

        .archive-form-note {
            grid-column:1 / -1;
            margin:0;
            padding:11px 12px;
            border-radius:10px;
            color:var(--slate-600);
            background:var(--slate-100);
            font-size:10px;
            line-height:1.55;
        }

        .archive-submit {
            grid-column:1 / -1;
            min-height:43px;
            border:0;
            border-radius:11px;
            color:#fff;
            background:var(--green-700);
            font:inherit;
            font-size:11px;
            font-weight:900;
            cursor:pointer;
        }

        .field-error {
            display:block;
            margin-top:5px;
            color:#b91c1c;
            font-size:10px;
            font-weight:700;
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

        .page-alert.success {
            border-color:var(--green-200);
            color:var(--green-900);
            background:var(--green-50);
        }

        .page-alert.error {
            border-color:#fecaca;
            color:#991b1b;
            background:#fff5f5;
        }

        .page-alert ul {
            margin:7px 0 0;
            padding-left:18px;
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
            .archive-actions { width:100%; justify-items:stretch; }
            .request-status { justify-content:center; }
            .archive-request-form { grid-template-columns:1fr; }
            .archive-field.full,
            .archive-confirmation,
            .archive-form-note,
            .archive-submit { grid-column:1; }
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
    <link rel="icon" type="image/jpeg" href="{{ asset('images/md-farma-logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/md-farma-logo-theme.css') }}">
</head>
<body>
    <header class="topbar">
        <nav>
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/md-farma-logo.jpeg') }}" alt="" width="52" height="52"></span>
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

        @if (session('status'))
            <div class="page-alert success" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="page-alert error" role="alert">
                <strong>Periksa kembali pengajuan salinan arsip:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                        href="{{ route('consultation.history', ['status' => 'semua', 'profil' => $selectedProfile]) }}"
                    >
                        Semua
                    </a>
                    <a
                        class="filter {{ $selectedStatus === 'aktif' ? 'active' : '' }}"
                        href="{{ route('consultation.history', ['status' => 'aktif', 'profil' => $selectedProfile]) }}"
                    >
                        Aktif
                    </a>
                    <a
                        class="filter {{ $selectedStatus === 'selesai' ? 'active' : '' }}"
                        href="{{ route('consultation.history', ['status' => 'selesai', 'profil' => $selectedProfile]) }}"
                    >
                        Selesai
                    </a>
                </div>

                @if ($profiles->isNotEmpty())
                    <form
                        class="profile-filter"
                        method="GET"
                        action="{{ route('consultation.history') }}"
                    >
                        <input type="hidden" name="status" value="{{ $selectedStatus }}">
                        <label for="history-profile">Pasien</label>
                        <select
                            id="history-profile"
                            name="profil"
                            onchange="this.form.submit()"
                        >
                            <option value="semua" @selected($selectedProfile === 'semua')>
                                Semua profil
                            </option>
                            @foreach ($profiles as $profile)
                                <option
                                    value="{{ $profile->public_id }}"
                                    @selected($selectedProfile === $profile->public_id)
                                >
                                    {{ $profile->name }} — {{ $profile->relationshipLabel() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
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
                            $archiveRequest = $consultation
                                ->latestArchiveCopyRequest;
                            $requestStatusLabel = $archiveRequest
                                ? match ($archiveRequest->status) {
                                    'pending' => 'Permintaan dikirim',
                                    'verifying' => 'Sedang diverifikasi',
                                    'approved' => 'Permintaan disetujui',
                                    'rejected' => 'Permintaan ditolak',
                                    'completed' => 'Permintaan selesai',
                                    default => $archiveRequest->statusLabel(),
                                }
                                : null;
                            $canRequestCopy = ! $archiveRequest
                                || ! $archiveRequest->isActiveRequest();
                            $reopenArchiveForm = old('consultation_public_id')
                                === $consultation->public_id;
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
                                    @if ($consultation->patientProfile)
                                        <span>{{ $consultation->patientProfile->relationshipLabel() }}</span>
                                    @endif
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
                                <div class="archive-actions">
                                    <span
                                        class="open-link disabled"
                                        aria-disabled="true"
                                    >
                                        Arsip internal
                                    </span>

                                    @if ($archiveRequest)
                                        <span class="request-status {{ $archiveRequest->status }}">
                                            {{ $requestStatusLabel }}
                                        </span>
                                    @endif
                                </div>

                                @if ($canRequestCopy)
                                    <details
                                        class="archive-request-details"
                                        @if ($reopenArchiveForm) open @endif
                                    >
                                        <summary>
                                            {{ $archiveRequest
                                                ? 'Ajukan salinan kembali'
                                                : 'Ajukan salinan riwayat' }}
                                        </summary>

                                        <form
                                            class="archive-request-form"
                                            method="POST"
                                            action="{{ route('consultation.archive-copy.store', $consultation) }}"
                                        >
                                            @csrf
                                            <input
                                                type="hidden"
                                                name="consultation_public_id"
                                                value="{{ $consultation->public_id }}"
                                            >
                                            <div class="archive-field full">
                                                <label for="reason-{{ $consultation->public_id }}">
                                                    Alasan meminta salinan
                                                </label>
                                                <textarea
                                                    id="reason-{{ $consultation->public_id }}"
                                                    name="reason"
                                                    required
                                                    maxlength="1000"
                                                    placeholder="Contoh: diperlukan untuk konsultasi lanjutan atau dokumentasi pribadi."
                                                >{{ $reopenArchiveForm ? old('reason') : '' }}</textarea>
                                                @if ($reopenArchiveForm)
                                                    @error('reason')
                                                        <span class="field-error">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="archive-field">
                                                <label for="contact-method-{{ $consultation->public_id }}">
                                                    Metode tindak lanjut
                                                </label>
                                                <select
                                                    id="contact-method-{{ $consultation->public_id }}"
                                                    name="contact_method"
                                                    required
                                                >
                                                    <option value="whatsapp" @selected($reopenArchiveForm && old('contact_method') === 'whatsapp')>WhatsApp</option>
                                                    <option value="telepon" @selected($reopenArchiveForm && old('contact_method') === 'telepon')>Telepon</option>
                                                    <option value="ambil_apotek" @selected($reopenArchiveForm && old('contact_method') === 'ambil_apotek')>Ambil di apotek</option>
                                                </select>
                                            </div>

                                            <div class="archive-field">
                                                <label for="contact-value-{{ $consultation->public_id }}">
                                                    Nomor kontak
                                                </label>
                                                <input
                                                    id="contact-value-{{ $consultation->public_id }}"
                                                    name="contact_value"
                                                    type="tel"
                                                    value="{{ $reopenArchiveForm ? old('contact_value', $consultation->no_hp) : $consultation->no_hp }}"
                                                    required
                                                    maxlength="120"
                                                    autocomplete="tel"
                                                >
                                                @if ($reopenArchiveForm)
                                                    @error('contact_value')
                                                        <span class="field-error">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="archive-field full">
                                                <label for="history-password-{{ $consultation->public_id }}">
                                                    Password Riwayat
                                                </label>
                                                <input
                                                    id="history-password-{{ $consultation->public_id }}"
                                                    name="history_password"
                                                    type="password"
                                                    required
                                                    maxlength="128"
                                                    autocomplete="current-password"
                                                    placeholder="Masukkan kembali Password Riwayat"
                                                >
                                                @if ($reopenArchiveForm)
                                                    @error('history_password')
                                                        <span class="field-error">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>

                                            <label class="archive-confirmation">
                                                <input
                                                    type="checkbox"
                                                    name="privacy_confirmation"
                                                    value="1"
                                                    required
                                                    @checked($reopenArchiveForm && old('privacy_confirmation'))
                                                >
                                                <span>
                                                    Saya memahami bahwa permintaan akan diverifikasi
                                                    secara manual dan salinan tidak dikirim otomatis.
                                                </span>
                                            </label>

                                            <p class="archive-form-note">
                                                MD Farma dapat meminta verifikasi tambahan sebelum
                                                menyerahkan arsip untuk mencegah data diberikan kepada
                                                pihak yang tidak berwenang.
                                            </p>

                                            <button class="archive-submit" type="submit">
                                                Kirim permintaan salinan
                                            </button>
                                        </form>
                                    </details>
                                @endif
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
