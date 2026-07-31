<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Pilih Chat Konsultasi — MD Farma</title>

    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --slate-950:#0f172a;
            --slate-700:#334155;
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
                radial-gradient(circle at 90% 4%,rgba(16,185,129,.17),transparent 24%),
                #f8fafc;
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.7);
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(920px,92%);
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
            font-weight:800;
        }

        .page {
            width:min(720px,92%);
            margin:54px auto 70px;
        }

        .chat-entry {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:25px;
            background:#fff;
            box-shadow:0 24px 70px rgba(15,23,42,.1);
        }

        .chat-head {
            padding:29px 30px;
            color:#fff;
            background:
                radial-gradient(circle at 100% 0%,rgba(255,255,255,.14),transparent 38%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
        }

        .eyebrow {
            margin:0 0 8px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        .chat-head h1 {
            margin:0;
            font-size:clamp(25px,4vw,35px);
            letter-spacing:-.035em;
        }

        .chat-head p:last-child {
            max-width:560px;
            margin:12px 0 0;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .choices {
            display:grid;
            gap:15px;
            padding:26px 30px 30px;
        }

        .choice {
            display:flex;
            align-items:center;
            gap:16px;
            padding:19px;
            border:1px solid var(--slate-200);
            border-radius:17px;
            color:inherit;
            text-decoration:none;
            transition:.18s ease;
        }

        .choice:hover {
            transform:translateY(-1px);
            border-color:var(--green-600);
            box-shadow:0 12px 28px rgba(15,23,42,.08);
        }

        .choice.primary {
            border-color:#a7f3d0;
            background:var(--green-50);
        }

        .choice-icon {
            flex:0 0 auto;
            width:48px;
            height:48px;
            display:grid;
            place-items:center;
            border-radius:14px;
            background:var(--green-700);
            color:#fff;
        }

        .choice.secondary .choice-icon {
            background:var(--slate-100);
            color:var(--green-800);
        }

        .choice-icon svg {
            width:23px;
            height:23px;
        }

        .choice-copy {
            min-width:0;
            flex:1;
        }

        .choice-copy strong {
            display:block;
            margin-bottom:5px;
            font-size:15px;
        }

        .choice-copy span {
            display:block;
            overflow:hidden;
            color:var(--slate-500);
            font-size:12px;
            line-height:1.5;
            text-overflow:ellipsis;
            white-space:nowrap;
        }

        .choice-arrow {
            color:var(--green-700);
            font-size:22px;
            font-weight:900;
        }

        .privacy-note {
            margin:0 30px 28px;
            padding:13px 15px;
            border-radius:12px;
            background:var(--slate-100);
            color:var(--slate-500);
            font-size:11px;
            line-height:1.55;
        }

        @media (max-width:570px) {
            .page { margin-top:28px; }
            .chat-head { padding:25px 22px; }
            .choices { padding:22px; }
            .privacy-note { margin:0 22px 22px; }
            .choice { padding:16px; }
            .choice-copy span { white-space:normal; }
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
        <section class="chat-entry" aria-labelledby="chat-entry-title">
            <header class="chat-head">
                <p class="eyebrow">Ruang konsultasi</p>
                <h1 id="chat-entry-title">Pilih cara melanjutkan chat.</h1>
                <p>
                    Buka kembali percakapan terakhir beserta riwayatnya,
                    atau buat ruang konsultasi baru.
                </p>
            </header>

            <div class="choices">
                <a
                    class="choice primary"
                    href="{{ route('chat.show', $latestConsultation) }}"
                >
                    <span class="choice-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            <path d="M8 10h8M8 14h5" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="choice-copy">
                        <strong>Lanjutkan Chat Terakhir</strong>
                        <span>
                            {{ $latestConsultation->nama }} ·
                            {{ $latestConsultation->created_at
                                ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                ->format('d M Y, H.i') }} WIB
                            @if ($latestConsultation->lastMessage)
                                · {{ \Illuminate\Support\Str::limit(
                                    $latestConsultation->lastMessage->message
                                        ?: 'Lampiran terakhir',
                                    52
                                ) }}
                            @endif
                        </span>
                    </span>
                    <span class="choice-arrow" aria-hidden="true">›</span>
                </a>

                <a
                    class="choice secondary"
                    href="{{ route('consultation.create') }}"
                >
                    <span class="choice-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="choice-copy">
                        <strong>Konsultasi Baru</strong>
                        <span>
                            Isi data pasien untuk membuat ruang chat baru.
                        </span>
                    </span>
                    <span class="choice-arrow" aria-hidden="true">›</span>
                </a>
            </div>

            <p class="privacy-note">
                Riwayat hanya dapat dibuka dari browser yang sama selama
                akses pasien masih berlaku. Pesan tetap dimuat dari database.
            </p>
        </section>
    </main>
</body>
</html>
