<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Keamanan Perangkat — MD Farma</title>

    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --red-800:#991b1b;
            --red-700:#b91c1c;
            --red-100:#fee2e2;
            --red-50:#fef2f2;
            --amber-900:#78350f;
            --amber-100:#fef3c7;
            --amber-50:#fffbeb;
            --slate-950:#0f172a;
            --slate-800:#1e293b;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
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
                radial-gradient(circle at 88% 4%,rgba(16,185,129,.16),transparent 25%),
                #f8fafc;
        }

        button,
        a { -webkit-tap-highlight-color:transparent; }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.72);
            background:rgba(255,255,255,.91);
            backdrop-filter:blur(14px);
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
        }

        .back {
            color:var(--slate-700);
            font-size:13px;
            font-weight:850;
        }

        .page {
            width:min(1080px,92%);
            margin:36px auto 70px;
        }

        .hero {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            align-items:center;
            gap:28px;
            padding:31px 33px;
            overflow:hidden;
            border-radius:25px;
            color:#fff;
            background:
                radial-gradient(circle at 95% 0%,rgba(255,255,255,.14),transparent 36%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 22px 65px rgba(6,78,59,.19);
        }

        .eyebrow {
            margin:0 0 9px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.09em;
            text-transform:uppercase;
        }

        h1 {
            margin:0;
            font-size:clamp(29px,5vw,43px);
            line-height:1.05;
            letter-spacing:-.045em;
        }

        .hero p:last-child {
            max-width:690px;
            margin:14px 0 0;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .hero-stat {
            min-width:150px;
            padding:19px 20px;
            border:1px solid rgba(255,255,255,.17);
            border-radius:18px;
            background:rgba(255,255,255,.08);
            text-align:center;
        }

        .hero-stat strong {
            display:block;
            font-size:34px;
            line-height:1;
        }

        .hero-stat span {
            display:block;
            margin-top:8px;
            color:#d1fae5;
            font-size:11px;
            font-weight:800;
        }

        .notice {
            margin-top:18px;
            padding:13px 16px;
            border:1px solid #a7f3d0;
            border-radius:13px;
            background:var(--green-50);
            color:var(--green-900);
            font-size:12px;
            line-height:1.55;
        }

        .notice.warning {
            border-color:#fde68a;
            background:var(--amber-50);
            color:#92400e;
        }

        .layout {
            display:grid;
            grid-template-columns:minmax(0,1.35fr) minmax(285px,.65fr);
            gap:24px;
            align-items:start;
            margin-top:24px;
        }

        .column { display:grid; gap:24px; }

        .panel {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:22px;
            background:#fff;
            box-shadow:0 14px 45px rgba(15,23,42,.06);
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
            letter-spacing:-.025em;
        }

        .panel-head p {
            margin:6px 0 0;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.55;
        }

        .device-list { display:grid; }

        .device-card {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:18px;
            align-items:center;
            padding:21px 23px;
            border-bottom:1px solid var(--slate-100);
        }

        .device-card:last-child { border-bottom:0; }

        .device-main { min-width:0; }

        .device-title {
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:8px;
        }

        .device-title strong {
            font-size:14px;
            letter-spacing:-.01em;
        }

        .badge {
            display:inline-flex;
            align-items:center;
            min-height:23px;
            padding:0 9px;
            border-radius:999px;
            font-size:10px;
            font-weight:900;
        }

        .badge.current,
        .badge.active {
            background:var(--green-100);
            color:var(--green-800);
        }

        .badge.inactive {
            background:var(--slate-100);
            color:var(--slate-600);
        }

        .device-meta {
            display:flex;
            flex-wrap:wrap;
            gap:7px 14px;
            margin-top:9px;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        form { margin:0; }

        .button {
            min-height:41px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 15px;
            border:0;
            border-radius:11px;
            text-decoration:none;
            font:inherit;
            font-size:11px;
            font-weight:900;
            cursor:pointer;
        }

        .button.danger-soft {
            border:1px solid var(--red-100);
            background:var(--red-50);
            color:var(--red-700);
        }

        .button.danger {
            background:var(--red-700);
            color:#fff;
        }

        .button.neutral {
            border:1px solid var(--slate-200);
            background:#fff;
            color:var(--slate-700);
        }

        .button:focus-visible,
        a:focus-visible {
            outline:3px solid rgba(16,185,129,.28);
            outline-offset:3px;
        }

        .info-body,
        .danger-body { padding:21px 23px 23px; }

        .info-list {
            display:grid;
            gap:14px;
            margin:0;
            padding:0;
            list-style:none;
        }

        .info-list li {
            display:grid;
            grid-template-columns:8px minmax(0,1fr);
            gap:11px;
            color:var(--slate-600);
            font-size:11px;
            line-height:1.6;
        }

        .dot {
            width:8px;
            height:8px;
            margin-top:5px;
            border-radius:50%;
            background:var(--green-600);
        }

        .danger-zone {
            border-color:#fecaca;
        }

        .danger-zone .panel-head {
            background:var(--red-50);
        }

        .danger-body p {
            margin:0 0 16px;
            color:var(--slate-600);
            font-size:11px;
            line-height:1.6;
        }

        .danger-actions {
            display:grid;
            gap:10px;
        }

        .danger-actions .button { width:100%; }

        .audit-list {
            max-height:390px;
            overflow:auto;
        }

        .audit-item {
            padding:16px 23px;
            border-bottom:1px solid var(--slate-100);
        }

        .audit-item:last-child { border-bottom:0; }

        .audit-item strong {
            display:block;
            font-size:12px;
        }

        .audit-item span {
            display:block;
            margin-top:5px;
            color:var(--slate-500);
            font-size:10px;
            line-height:1.55;
        }

        .empty {
            padding:25px 23px;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.6;
        }

        @media (max-width:850px) {
            .layout { grid-template-columns:1fr; }
        }

        @media (max-width:640px) {
            .page { width:94%; margin-top:20px; }
            .hero {
                grid-template-columns:1fr;
                padding:25px 21px;
                border-radius:21px;
            }
            .hero-stat {
                width:100%;
                display:flex;
                align-items:center;
                justify-content:space-between;
                text-align:left;
            }
            .hero-stat span { margin:0; }
            .device-card {
                grid-template-columns:1fr;
                padding:19px;
            }
            .device-card form,
            .device-card .button { width:100%; }
            .panel-head { padding:20px 19px 15px; }
            .info-body,.danger-body { padding:19px; }
            .audit-item { padding:15px 19px; }
        }

        @media (max-width:420px) {
            nav { width:94%; }
            .brand span:last-child { display:none; }
            .back { font-size:11px; }
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
                ← Kembali ke Dashboard
            </a>
        </nav>
    </header>

    <main class="page">
        <section class="hero" aria-labelledby="device-title">
            <div>
                <p class="eyebrow">Keamanan riwayat</p>
                <h1 id="device-title">Kelola perangkat yang terhubung.</h1>
                <p>
                    Tinjau perangkat yang dapat membuka riwayat konsultasi Anda.
                    Cabut perangkat lama atau perangkat yang tidak lagi dikenali.
                </p>
            </div>
            <div class="hero-stat" aria-label="Jumlah perangkat aktif">
                <strong>{{ $activeDeviceTotal }}</strong>
                <span>perangkat aktif</span>
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

        <div class="layout">
            <div class="column">
                <section class="panel" aria-labelledby="connected-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="connected-title">Perangkat terhubung</h2>
                            <p>
                                Perangkat aktif dapat mengakses halaman password,
                                lalu membuka riwayat setelah verifikasi berhasil.
                            </p>
                        </div>
                    </header>

                    <div class="device-list">
                        @foreach ($devices as $device)
                            @php
                                $isCurrent = (int) $device->id
                                    === (int) $currentDevice->id;
                                $isActive = $device->isActiveDevice();
                                $label = $device->device_label
                                    ?: 'Perangkat tepercaya';
                                $firstSeen = $device->first_seen_at
                                    ?? $device->created_at;
                            @endphp

                            <article class="device-card">
                                <div class="device-main">
                                    <div class="device-title">
                                        <strong>{{ $label }}</strong>
                                        @if ($isCurrent)
                                            <span class="badge current">Perangkat ini</span>
                                        @endif
                                        <span class="badge {{ $isActive ? 'active' : 'inactive' }}">
                                            {{ $isActive
                                                ? 'Aktif'
                                                : ($device->revoked_at ? 'Dicabut' : 'Kedaluwarsa') }}
                                        </span>
                                    </div>

                                    <div class="device-meta">
                                        <span>
                                            Ditambahkan
                                            {{ $firstSeen
                                                ? $firstSeen
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y, H.i').' WIB'
                                                : '—' }}
                                        </span>
                                        <span>
                                            Terakhir aktif
                                            {{ $device->last_seen_at
                                                ? $device->last_seen_at
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y, H.i').' WIB'
                                                : 'belum tercatat' }}
                                        </span>
                                        @if ($isActive && $device->expires_at)
                                            <span>
                                                Berlaku sampai
                                                {{ $device->expires_at
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y, H.i') }} WIB
                                            </span>
                                        @elseif ($device->revoked_at)
                                            <span>
                                                Dicabut
                                                {{ $device->revoked_at
                                                    ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                                    ->format('d M Y, H.i') }} WIB
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if (! $isCurrent && $isActive)
                                    <form
                                        method="POST"
                                        action="{{ route('consultation.devices.revoke', $device) }}"
                                        onsubmit="return confirm('Cabut akses perangkat ini? Perangkat tersebut harus menjalani pemulihan untuk terhubung kembali.');"
                                    >
                                        @csrf
                                        <button class="button danger-soft" type="submit">
                                            Cabut akses
                                        </button>
                                    </form>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="panel" aria-labelledby="audit-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="audit-title">Aktivitas pencabutan</h2>
                            <p>Dua puluh tindakan keamanan terbaru pada riwayat ini.</p>
                        </div>
                    </header>

                    @if ($revocations->isEmpty())
                        <div class="empty">
                            Belum ada perangkat yang dicabut.
                        </div>
                    @else
                        <div class="audit-list">
                            @foreach ($revocations as $revocation)
                                @php
                                    $actionLabel = match ($revocation->action) {
                                        'all_others' => 'Dicabut melalui tindakan cabut semua perangkat lain',
                                        'current' => 'Akses dihapus dari perangkat yang sedang digunakan',
                                        default => 'Akses satu perangkat dicabut',
                                    };
                                    $targetLabel = $revocation
                                        ->targetDevice?->device_label
                                        ?: 'Perangkat yang terhubung';
                                    $actorLabel = $revocation
                                        ->revokedByDevice?->device_label
                                        ?: 'Perangkat terverifikasi';
                                @endphp
                                <article class="audit-item">
                                    <strong>{{ $targetLabel }}</strong>
                                    <span>{{ $actionLabel }}.</span>
                                    <span>
                                        Oleh {{ $actorLabel }} ·
                                        {{ $revocation->revoked_at
                                            ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                            ->format('d M Y, H.i') }} WIB
                                    </span>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <aside class="column">
                <section class="panel" aria-labelledby="info-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="info-title">Cara kerja akses</h2>
                            <p>Cookie hanya mengenali perangkat, bukan membuktikan identitas orang.</p>
                        </div>
                    </header>
                    <div class="info-body">
                        <ul class="info-list">
                            <li>
                                <span class="dot" aria-hidden="true"></span>
                                <span>Password Riwayat tetap diminta ketika sesi terkunci.</span>
                            </li>
                            <li>
                                <span class="dot" aria-hidden="true"></span>
                                <span>Perangkat yang dicabut langsung kehilangan akses ke chat, lampiran, dan riwayat.</span>
                            </li>
                            <li>
                                <span class="dot" aria-hidden="true"></span>
                                <span>Pencabutan perangkat tidak menghapus data konsultasi dari MD Farma.</span>
                            </li>
                            <li>
                                <span class="dot" aria-hidden="true"></span>
                                <span>Perangkat dapat ditautkan kembali melalui proses pemulihan riwayat.</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <section class="panel danger-zone" aria-labelledby="danger-title">
                    <header class="panel-head">
                        <div>
                            <h2 id="danger-title">Tindakan keamanan</h2>
                            <p>Tindakan ini langsung membatalkan token perangkat.</p>
                        </div>
                    </header>
                    <div class="danger-body">
                        <p>
                            Cabut semua perangkat lain apabila Anda tidak mengenali
                            perangkat lama atau pernah menggunakan browser bersama.
                        </p>
                        <div class="danger-actions">
                            <form
                                method="POST"
                                action="{{ route('consultation.devices.revoke-others') }}"
                                onsubmit="return confirm('Cabut seluruh perangkat lain dan pertahankan hanya perangkat ini?');"
                            >
                                @csrf
                                <button
                                    class="button danger-soft"
                                    type="submit"
                                    {{ $activeDeviceTotal <= 1 ? 'disabled' : '' }}
                                >
                                    Cabut semua perangkat lain
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('consultation.devices.revoke-current') }}"
                                onsubmit="return confirm('Hapus akses riwayat dari perangkat ini? Anda perlu melakukan pemulihan untuk menghubungkannya kembali.');"
                            >
                                @csrf
                                <button class="button danger" type="submit">
                                    Hapus akses perangkat ini
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                <a class="button neutral" href="{{ route('consultation.entry') }}">
                    Kembali ke Dashboard Konsultasi
                </a>
            </aside>
        </div>
    </main>
</body>
</html>
