<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="description"
        content="Hubungi WhatsApp resmi Apotek MD Farma untuk informasi kerja sama."
    >
    <meta name="theme-color" content="#198754">

    <title>Kerja Sama | Apotek MD Farma</title>

    <style>
        :root {
            --brand: #198754;
            --brand-dark: #0f6840;
            --brand-deep: #0a4f34;
            --brand-soft: #eaf8f1;
            --teal: #18b7a5;
            --surface: #ffffff;
            --canvas: #f6faf8;
            --slate-950: #15201b;
            --slate-800: #26342e;
            --slate-700: #405148;
            --slate-600: #5d6d65;
            --slate-300: #cfdcd5;
            --slate-200: #dee8e3;
            --danger-soft: #fff2f2;
            --danger: #a12d2d;
            --shadow-sm: 0 8px 24px rgba(15, 45, 31, .07);
            --shadow-md: 0 20px 60px rgba(15, 45, 31, .12);
        }

        * {
            box-sizing: border-box;
        }

        html {
            color-scheme: light;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--slate-950);
            background:
                radial-gradient(
                    circle at 86% 8%,
                    rgba(24, 183, 165, .15),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 8% 88%,
                    rgba(25, 135, 84, .09),
                    transparent 24%
                ),
                linear-gradient(135deg, #fbfdfc 0%, #eaf8f1 100%);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a,
        button {
            font: inherit;
            -webkit-tap-highlight-color: transparent;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid rgba(24, 183, 165, .38);
            outline-offset: 3px;
        }

        .container {
            width: min(1120px, calc(100% - 40px));
            margin-inline: auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(207, 220, 213, .82);
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(16px);
        }

        .nav {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            color: var(--slate-950);
            text-decoration: none;
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -.025em;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
            box-shadow: 0 10px 24px rgba(25, 135, 84, .24);
        }

        .brand-mark svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            border: 1px solid var(--slate-300);
            border-radius: 11px;
            color: var(--slate-700);
            background: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
        }

        .nav-link.primary {
            border-color: var(--brand);
            color: #fff;
            background: var(--brand);
        }

        .nav-link,
        .whatsapp-button,
        .copy-button {
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease,
                background .18s ease;
        }

        .nav-link:hover,
        .whatsapp-button:hover,
        .copy-button:hover {
            transform: translateY(-1px);
        }

        main {
            padding: 64px 0 72px;
        }

        .page-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(360px, 480px);
            align-items: center;
            gap: 64px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 18px;
            color: var(--brand-deep);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 8px;
            height: 8px;
            content: "";
            border-radius: 50%;
            background: var(--brand);
            box-shadow: 0 0 0 5px rgba(25, 135, 84, .11);
        }

        h1 {
            max-width: 700px;
            margin: 0;
            font-size: clamp(40px, 5.4vw, 66px);
            line-height: 1.04;
            letter-spacing: -.052em;
        }

        .accent {
            color: var(--brand);
        }

        .lead {
            max-width: 650px;
            margin: 24px 0 0;
            color: var(--slate-600);
            font-size: 17px;
            line-height: 1.75;
        }

        .steps {
            display: grid;
            gap: 14px;
            margin: 32px 0 0;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            color: var(--slate-700);
            font-size: 14px;
            line-height: 1.55;
        }

        .step-number {
            width: 30px;
            height: 30px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: var(--brand-deep);
            background: var(--brand-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .trust-note {
            max-width: 650px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 28px;
            padding: 14px 16px;
            border: 1px solid rgba(25, 135, 84, .14);
            border-radius: 14px;
            color: var(--slate-600);
            background: rgba(255, 255, 255, .68);
            font-size: 12px;
            line-height: 1.55;
        }

        .trust-note svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
            color: var(--brand);
            stroke: currentColor;
        }

        .contact-card {
            padding: 28px;
            border: 1px solid rgba(207, 220, 213, .88);
            border-radius: 26px;
            background: rgba(255, 255, 255, .95);
            box-shadow: var(--shadow-md);
        }

        .card-head {
            text-align: center;
        }

        .card-head h2 {
            margin: 0;
            font-size: 24px;
            letter-spacing: -.035em;
        }

        .card-head p {
            margin: 9px auto 0;
            color: var(--slate-600);
            font-size: 13px;
            line-height: 1.6;
        }

        .qr-shell {
            width: min(100%, 328px);
            min-height: 328px;
            display: grid;
            place-items: center;
            margin: 24px auto 0;
            padding: 23px;
            border: 1px solid var(--slate-200);
            border-radius: 22px;
            background: #fff;
            box-shadow: var(--shadow-sm);
        }

        #qrcode {
            width: 280px;
            height: 280px;
            display: grid;
            place-items: center;
        }

        #qrcode img,
        #qrcode canvas {
            max-width: 100%;
            height: auto !important;
        }

        .qr-loading,
        .qr-fallback {
            color: var(--slate-600);
            text-align: center;
            font-size: 13px;
            line-height: 1.55;
        }

        .qr-fallback {
            display: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: var(--slate-600);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            height: 1px;
            flex: 1;
            content: "";
            background: var(--slate-200);
        }

        .whatsapp-button,
        .copy-button {
            width: 100%;
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 13px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
        }

        .whatsapp-button {
            border: 1px solid var(--brand);
            color: #fff;
            background: linear-gradient(135deg, var(--brand), #0b9b68);
            box-shadow: 0 14px 30px rgba(25, 135, 84, .22);
        }

        .whatsapp-button svg,
        .copy-button svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
        }

        .copy-button {
            margin-top: 10px;
            border: 1px solid var(--slate-300);
            color: var(--slate-700);
            background: #fff;
        }

        .number {
            margin: 15px 0 0;
            color: var(--slate-600);
            text-align: center;
            font-size: 12px;
        }

        .copy-status {
            min-height: 18px;
            margin: 8px 0 0;
            color: var(--brand-deep);
            text-align: center;
            font-size: 12px;
            font-weight: 750;
        }

        .configuration-warning {
            padding: 20px;
            border: 1px solid #efc5c5;
            border-radius: 16px;
            color: var(--danger);
            background: var(--danger-soft);
            font-size: 13px;
            line-height: 1.65;
        }

        .configuration-warning strong {
            display: block;
            margin-bottom: 5px;
        }

        .configuration-warning code {
            padding: 2px 5px;
            border-radius: 5px;
            background: rgba(161, 45, 45, .08);
        }

        footer {
            padding: 22px 0;
            border-top: 1px solid rgba(207, 220, 213, .82);
            color: var(--slate-600);
            background: rgba(255, 255, 255, .72);
            text-align: center;
            font-size: 12px;
        }

        @media (max-width: 880px) {
            main {
                padding-top: 44px;
            }

            .page-grid {
                grid-template-columns: 1fr;
                gap: 42px;
            }

            .page-copy {
                text-align: center;
            }

            .lead,
            .trust-note {
                margin-inline: auto;
            }

            .steps {
                max-width: 620px;
                margin-inline: auto;
                text-align: left;
            }

            .contact-card {
                width: min(100%, 520px);
                margin-inline: auto;
            }
        }

        @media (max-width: 560px) {
            .container {
                width: min(100% - 28px, 1120px);
            }

            .nav {
                min-height: 66px;
            }

            .brand {
                font-size: 15px;
            }

            .brand-mark {
                width: 37px;
                height: 37px;
            }

            .nav-link:not(.primary) {
                display: none;
            }

            main {
                padding: 34px 0 48px;
            }

            h1 {
                font-size: clamp(37px, 12vw, 50px);
            }

            .lead {
                font-size: 15px;
            }

            .contact-card {
                padding: 20px;
                border-radius: 21px;
            }

            .qr-shell {
                min-height: auto;
                padding: 16px;
            }

            #qrcode {
                width: min(250px, 72vw);
                height: min(250px, 72vw);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="nav container" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 3v18M3 12h18"/>
                    </svg>
                </span>
                <span>Apotek MD Farma</span>
            </a>

            <div class="nav-actions">
                <a class="nav-link" href="{{ route('home') }}">
                    Halaman Utama
                </a>
                <a
                    class="nav-link primary"
                    href="{{ route('consultation.entry') }}"
                >
                    Konsultasi
                </a>
            </div>
        </nav>
    </header>

    <main>
        <div class="page-grid container">
            <section class="page-copy" aria-labelledby="page-title">
                <p class="eyebrow">Kontak resmi MD Farma</p>
                <h1 id="page-title">
                    Kerja Sama dengan
                    <span class="accent">Apotek MD Farma</span>
                </h1>
                <p class="lead">
                    Hubungi kanal WhatsApp resmi Apotek MD Farma untuk
                    menyampaikan kebutuhan, profil singkat, dan rencana
                    kolaborasi Anda. Gunakan kode QR atau tombol langsung
                    yang tersedia.
                </p>

                <div class="steps" aria-label="Cara menghubungi MD Farma">
                    <div class="step">
                        <span class="step-number">1</span>
                        <span>
                            Scan kode QR menggunakan kamera atau pemindai QR
                            pada ponsel Anda.
                        </span>
                    </div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <span>
                            Jika halaman ini dibuka melalui ponsel, klik tombol
                            <strong>Hubungi melalui WhatsApp</strong>.
                        </span>
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span>
                            Sampaikan kebutuhan atau rencana kerja sama Anda
                            melalui akun WhatsApp resmi Apotek MD Farma.
                        </span>
                    </div>
                </div>

                <div class="trust-note">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M12 3l7 3v5c0 5-3.4 8.4-7 10-3.6-1.6-7-5-7-10V6l7-3z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    <span>
                        Pastikan akun tujuan menampilkan identitas Apotek MD
                        Farma sebelum Anda mengirimkan informasi.
                    </span>
                </div>
            </section>

            <section class="contact-card" aria-labelledby="contact-title">
                @if ($isConfigured && $whatsappUrl)
                    <div class="card-head">
                        <h2 id="contact-title">Scan kode QR WhatsApp</h2>
                        <p>
                            Arahkan kamera ponsel ke kode QR berikut.
                        </p>
                    </div>

                    <div class="qr-shell">
                        <div
                            id="qrcode"
                            aria-label="Kode QR WhatsApp resmi Apotek MD Farma"
                        >
                            <span class="qr-loading">Menyiapkan kode QR…</span>
                        </div>
                        <p class="qr-fallback" id="qr-fallback">
                            Kode QR tidak dapat dimuat. Gunakan tombol WhatsApp
                            di bawah ini.
                        </p>
                    </div>

                    <div class="divider"><span>atau</span></div>

                    <a
                        class="whatsapp-button"
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka WhatsApp resmi Apotek MD Farma"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5z"/>
                            <path d="M9 9.5c.8 2 2.2 3.4 4.4 4.3"/>
                        </svg>
                        Hubungi melalui WhatsApp
                    </a>

                    <button
                        class="copy-button"
                        id="copy-link"
                        type="button"
                        data-link="{{ $whatsappUrl }}"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <rect x="8" y="8" width="11" height="11" rx="2"/>
                            <path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/>
                        </svg>
                        Salin link WhatsApp
                    </button>

                    <p class="number">
                        Nomor resmi: {{ $displayNumber }}
                    </p>
                    <p
                        class="copy-status"
                        id="copy-status"
                        aria-live="polite"
                    ></p>
                @else
                    <div class="configuration-warning" role="status">
                        <strong>Kanal WhatsApp sedang disiapkan.</strong>
                        Kontak kerja sama belum dapat dibuka saat ini. Silakan
                        kembali lagi atau hubungi MD Farma melalui kanal resmi
                        lain yang tersedia pada halaman utama.
                    </div>
                @endif
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} Apotek MD Farma. Kontak kerja sama resmi.
        </div>
    </footer>

    @if ($isConfigured && $whatsappUrl)
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
            referrerpolicy="no-referrer"
        ></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const whatsappUrl = @json($whatsappUrl);
                const qrElement = document.getElementById('qrcode');
                const qrFallback = document.getElementById('qr-fallback');
                const copyButton = document.getElementById('copy-link');
                const copyStatus = document.getElementById('copy-status');

                if (
                    qrElement
                    && whatsappUrl
                    && typeof window.QRCode !== 'undefined'
                ) {
                    qrElement.innerHTML = '';

                    new window.QRCode(qrElement, {
                        text: whatsappUrl,
                        width: 280,
                        height: 280,
                        colorDark: '#15201b',
                        colorLight: '#ffffff',
                        correctLevel: window.QRCode.CorrectLevel.M,
                    });
                } else if (qrElement && qrFallback) {
                    qrElement.style.display = 'none';
                    qrFallback.style.display = 'block';
                }

                if (copyButton && copyStatus) {
                    copyButton.addEventListener('click', async function () {
                        const link = copyButton.dataset.link || '';

                        if (!link) {
                            copyStatus.textContent = 'Link belum tersedia.';
                            return;
                        }

                        try {
                            await navigator.clipboard.writeText(link);
                            copyStatus.textContent =
                                'Link WhatsApp berhasil disalin.';
                        } catch (error) {
                            const temporaryInput =
                                document.createElement('textarea');

                            temporaryInput.value = link;
                            temporaryInput.setAttribute('readonly', '');
                            temporaryInput.style.position = 'fixed';
                            temporaryInput.style.opacity = '0';

                            document.body.appendChild(temporaryInput);
                            temporaryInput.select();

                            const copied = document.execCommand('copy');
                            temporaryInput.remove();

                            copyStatus.textContent = copied
                                ? 'Link WhatsApp berhasil disalin.'
                                : 'Salin link melalui alamat pada tombol WhatsApp.';
                        }
                    });
                }
            });
        </script>
    @endif
</body>
</html>
