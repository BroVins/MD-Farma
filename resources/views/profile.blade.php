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
        content="Profil Apotek MD Farma, layanan konsultasi farmasi, jam operasional, dan lokasi apotek di Warakas, Jakarta Utara."
    >
    <meta name="theme-color" content="#198754">

    <title>Profil Apotek MD Farma</title>

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
            --slate-500: #738178;
            --slate-300: #cfdcd5;
            --slate-200: #dee8e3;
            --slate-100: #edf3f0;
            --shadow-sm: 0 8px 24px rgba(15, 45, 31, .06);
            --shadow-md: 0 18px 50px rgba(15, 45, 31, .11);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--slate-950);
            background: var(--canvas);
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
            color: inherit;
            font: inherit;
            -webkit-tap-highlight-color: transparent;
        }

        svg {
            display: block;
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
            background: rgba(255, 255, 255, .93);
            box-shadow: 0 4px 18px rgba(15, 45, 31, .035);
            backdrop-filter: blur(16px);
        }

        .nav {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
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
            width: 21px;
            height: 21px;
            stroke: currentColor;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 23px;
        }

        .nav-links a {
            color: var(--slate-700);
            text-decoration: none;
            font-size: 13px;
            font-weight: 750;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--brand);
        }

        .nav-cta {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border-radius: 11px;
            color: #fff !important;
            background: var(--brand);
            box-shadow: 0 8px 20px rgba(25, 135, 84, .18);
        }

        .profile-hero {
            position: relative;
            overflow: hidden;
            padding: 88px 0 82px;
            background:
                radial-gradient(
                    circle at 88% 12%,
                    rgba(24, 183, 165, .18),
                    transparent 29%
                ),
                linear-gradient(135deg, #f9fcfa 0%, #eaf8f1 100%);
        }

        .profile-hero::after {
            content: "";
            position: absolute;
            width: 440px;
            height: 440px;
            right: -245px;
            bottom: -275px;
            border: 78px solid rgba(25, 135, 84, .055);
            border-radius: 50%;
        }

        .profile-hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(340px, .85fr);
            align-items: center;
            gap: 72px;
        }

        .profile-hero h1 {
            max-width: 690px;
            margin: 0;
            font-size: clamp(42px, 5.5vw, 68px);
            line-height: 1.03;
            letter-spacing: -.055em;
        }

        .profile-hero h1 span {
            color: var(--brand);
        }

        .lead {
            max-width: 650px;
            margin: 24px 0 0;
            color: var(--slate-600);
            font-size: 17px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 31px;
        }

        .button {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 20px;
            border: 1px solid var(--slate-200);
            border-radius: 13px;
            color: var(--slate-800);
            background: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 850;
        }

        .button-primary {
            border-color: var(--brand);
            color: #fff;
            background: var(--brand);
            box-shadow: 0 14px 30px rgba(25, 135, 84, .23);
        }

        .button svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .identity-card {
            padding: 27px;
            border: 1px solid rgba(207, 220, 213, .86);
            border-radius: 25px;
            background: rgba(255, 255, 255, .9);
            box-shadow: var(--shadow-md);
        }

        .identity-head {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--slate-100);
        }

        .identity-icon {
            width: 54px;
            height: 54px;
            display: grid;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 17px;
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
        }

        .identity-icon svg {
            width: 27px;
            height: 27px;
            stroke: currentColor;
        }

        .identity-head strong {
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .identity-head span {
            color: var(--slate-600);
            font-size: 12px;
        }

        .identity-list {
            display: grid;
            gap: 17px;
            margin: 22px 0 0;
        }

        .identity-item {
            display: grid;
            grid-template-columns: 22px 1fr;
            gap: 12px;
        }

        .identity-item svg {
            width: 20px;
            height: 20px;
            color: var(--brand);
            stroke: currentColor;
        }

        .identity-item span {
            color: var(--slate-600);
            font-size: 12px;
            line-height: 1.65;
        }

        .identity-item strong {
            display: block;
            margin-bottom: 2px;
            color: var(--slate-800);
            font-size: 12px;
        }

        .section {
            padding: 78px 0;
        }

        .section-white {
            background: #fff;
        }

        .section-head {
            max-width: 680px;
            margin-bottom: 35px;
        }

        .section-head h2 {
            margin: 0;
            font-size: clamp(29px, 4vw, 43px);
            line-height: 1.12;
            letter-spacing: -.04em;
        }

        .section-head p {
            margin: 16px 0 0;
            color: var(--slate-600);
            font-size: 15px;
            line-height: 1.8;
        }

        .principle-grid,
        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .principle,
        .service {
            min-height: 230px;
            padding: 26px;
            border: 1px solid var(--slate-200);
            border-radius: 20px;
            background: var(--surface);
            box-shadow: var(--shadow-sm);
        }

        .principle-icon,
        .service-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            margin-bottom: 25px;
            border-radius: 14px;
            color: var(--brand);
            background: var(--brand-soft);
        }

        .principle-icon svg,
        .service-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
        }

        .principle h3,
        .service h3 {
            margin: 0 0 11px;
            font-size: 17px;
        }

        .principle p,
        .service p {
            margin: 0;
            color: var(--slate-600);
            font-size: 13px;
            line-height: 1.75;
        }

        .visit-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            border: 1px solid var(--slate-200);
            border-radius: 25px;
            background: #fff;
            box-shadow: var(--shadow-md);
        }

        .visit-copy {
            padding: 38px;
        }

        .visit-copy h2 {
            margin: 0;
            font-size: clamp(28px, 4vw, 40px);
            letter-spacing: -.04em;
        }

        .visit-copy > p {
            margin: 16px 0 0;
            color: var(--slate-600);
            font-size: 14px;
            line-height: 1.8;
        }

        .schedule {
            display: grid;
            gap: 11px;
            margin-top: 27px;
        }

        .schedule-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 0;
            border-bottom: 1px solid var(--slate-100);
            color: var(--slate-600);
            font-size: 12px;
        }

        .schedule-row strong {
            color: var(--slate-800);
        }

        .visit-address {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 38px;
            color: #fff;
            background:
                radial-gradient(
                    circle at 90% 0,
                    rgba(255, 255, 255, .15),
                    transparent 30%
                ),
                linear-gradient(145deg, var(--brand-deep), var(--brand));
        }

        .visit-address svg {
            width: 34px;
            height: 34px;
            margin-bottom: 22px;
            stroke: currentColor;
        }

        .visit-address strong {
            font-size: 20px;
        }

        .visit-address p {
            margin: 13px 0 25px;
            color: rgba(255, 255, 255, .83);
            font-size: 13px;
            line-height: 1.8;
        }

        .visit-address .button {
            align-self: flex-start;
            border-color: #fff;
            color: var(--brand-deep);
        }

        .footer {
            color: var(--slate-600);
            background: #fff;
        }

        .footer-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            padding: 33px 0;
            border-top: 1px solid var(--slate-100);
        }

        .footer-main p {
            max-width: 510px;
            margin: 0;
            font-size: 11px;
            line-height: 1.7;
        }

        .footer-links {
            display: flex;
            gap: 18px;
        }

        .footer-links a {
            color: var(--slate-600);
            text-decoration: none;
            font-size: 11px;
            font-weight: 750;
        }

        @media (max-width: 860px) {
            .profile-hero-grid,
            .visit-card {
                grid-template-columns: 1fr;
            }

            .identity-card {
                max-width: 620px;
            }

            .principle-grid,
            .service-grid {
                grid-template-columns: 1fr;
            }

            .principle,
            .service {
                min-height: auto;
            }
        }

        @media (max-width: 700px) {
            .container {
                width: min(100% - 28px, 1120px);
            }

            .nav {
                min-height: 66px;
            }

            .nav-links a:not(.active):not(.nav-cta) {
                display: none;
            }

            .profile-hero {
                padding: 58px 0 64px;
            }

            .profile-hero-grid {
                gap: 38px;
            }

            .lead {
                font-size: 15px;
            }

            .section {
                padding: 64px 0;
            }

            .visit-copy,
            .visit-address {
                padding: 27px;
            }

            .footer-main {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .brand {
                font-size: 15px;
            }

            .brand-mark {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .nav-cta {
                display: none;
            }

            .profile-hero h1 {
                font-size: 40px;
            }

            .hero-actions .button {
                width: 100%;
            }

            .identity-card {
                padding: 21px;
            }

            .schedule-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                scroll-behavior: auto !important;
                transition: none !important;
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
                        stroke-width="2.5"
                        stroke-linecap="round"
                    >
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </span>
                <span>MD Farma</span>
            </a>

            <div class="nav-links">
                <a class="active" href="{{ route('profile') }}">Profil</a>
                <a href="{{ route('home') }}#layanan">Layanan</a>
                <a href="{{ route('home') }}#operasional">Lokasi</a>
                <a class="nav-cta" href="{{ route('consultation.entry') }}">
                    Mulai Konsultasi
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="profile-hero">
            <div class="profile-hero-grid container">
                <div>
                    <h1>
                        Profil Apotek
                        <span>MD Farma</span>
                    </h1>

                    <p class="lead">
                        MD Farma menyediakan layanan apotek di Warakas,
                        Jakarta Utara, yang dilengkapi konsultasi farmasi
                        digital dan akses pembelian melalui marketplace resmi.
                    </p>

                    <div class="hero-actions">
                        <a
                            class="button button-primary"
                            href="{{ route('consultation.entry') }}"
                        >
                            Mulai Konsultasi
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>

                        <a
                            class="button"
                            href="https://maps.app.goo.gl/82xaeQfUQYvyrork8"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Lihat Lokasi
                        </a>
                    </div>
                </div>

                <aside class="identity-card" aria-label="Informasi MD Farma">
                    <div class="identity-head">
                        <span class="identity-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M6 2h12l2 5H4z"/>
                                <path d="M4 7v13h16V7M9 12h6M12 9v6"/>
                            </svg>
                        </span>
                        <div>
                            <strong>Apotek MD Farma</strong>
                            <span>Warakas, Tanjung Priok, Jakarta Utara</span>
                        </div>
                    </div>

                    <div class="identity-list">
                        <div class="identity-item">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                            <span>
                                <strong>Jam layanan hari kerja</strong>
                                Senin–Jumat, 08.00–20.00 WIB
                            </span>
                        </div>

                        <div class="identity-item">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                            <span>
                                <strong>Konsultasi farmasi digital</strong>
                                Percakapan privat dengan dukungan lampiran
                            </span>
                        </div>

                        <div class="identity-item">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            <span>
                                <strong>Lokasi apotek</strong>
                                Jl. Warakas V Gg. 7 No.125 12, Jakarta Utara
                            </span>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section section-white">
            <div class="container">
                <div class="section-head">
                    <h2>Pendekatan layanan MD Farma</h2>
                    <p>
                        Layanan dirancang agar pasien dapat memperoleh
                        informasi awal terkait obat dengan alur yang jelas,
                        aman, dan mudah digunakan.
                    </p>
                </div>

                <div class="principle-grid">
                    <article class="principle">
                        <span class="principle-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                        </span>
                        <h3>Mudah diakses</h3>
                        <p>
                            Pasien dapat memulai konsultasi langsung melalui
                            browser tanpa membuat akun terlebih dahulu.
                        </p>
                    </article>

                    <article class="principle">
                        <span class="principle-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 21s7-3.5 7-10V5l-7-3-7 3v6c0 6.5 7 10 7 10Z"/>
                                <path d="m9 11 2 2 4-4"/>
                            </svg>
                        </span>
                        <h3>Percakapan privat</h3>
                        <p>
                            Akses ruang chat terikat pada browser pasien dan
                            dilindungi oleh identitas konsultasi yang unik.
                        </p>
                    </article>

                    <article class="principle">
                        <span class="principle-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6M8 13h8M8 17h6"/>
                            </svg>
                        </span>
                        <h3>Informasi lebih terarah</h3>
                        <p>
                            Foto obat atau dokumen resep dapat dilampirkan
                            untuk membantu memperjelas pertanyaan pasien.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-head">
                    <h2>Layanan yang tersedia</h2>
                    <p>
                        Pilih konsultasi terlebih dahulu atau lanjutkan
                        pembelian melalui toko resmi MD Farma.
                    </p>
                </div>

                <div class="service-grid">
                    <article class="service">
                        <span class="service-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                            >
                                <path d="M7 3h10v18H7zM10 7h4M10 11h4M10 15h3"/>
                            </svg>
                        </span>
                        <h3>Konsultasi penggunaan obat</h3>
                        <p>
                            Pertanyaan mengenai aturan pakai, efek samping,
                            interaksi, dan informasi umum terkait obat.
                        </p>
                    </article>

                    <article class="service">
                        <span class="service-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                            >
                                <path d="M7 3h7l4 4v14H7z"/>
                                <path d="M14 3v5h5M10 13h5M10 17h5"/>
                            </svg>
                        </span>
                        <h3>Konsultasi resep</h3>
                        <p>
                            Pasien dapat mengirim foto atau dokumen resep
                            melalui fitur lampiran pada ruang chat.
                        </p>
                    </article>

                    <article class="service">
                        <span class="service-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M6 2h12l2 5H4z"/>
                                <path d="M4 7v13h16V7M9 11h6"/>
                            </svg>
                        </span>
                        <h3>Marketplace resmi</h3>
                        <p>
                            Produk MD Farma dapat diakses melalui Tokopedia,
                            Shopee, GoApotik, dan Blibli.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section section-white">
            <div class="container">
                <div class="visit-card">
                    <div class="visit-copy">
                        <h2>Kunjungi Apotek MD Farma</h2>
                        <p>
                            Jam operasional berikut menjadi acuan layanan
                            apotek dan waktu respons konsultasi digital.
                        </p>

                        <div class="schedule">
                            <div class="schedule-row">
                                <span>Senin–Jumat</span>
                                <strong>08.00–20.00 WIB</strong>
                            </div>
                            <div class="schedule-row">
                                <span>Sabtu–Minggu</span>
                                <strong>08.00–21.00 WIB</strong>
                            </div>
                        </div>
                    </div>

                    <div class="visit-address">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        <strong>Warakas, Jakarta Utara</strong>
                        <p>
                            Jl. Warakas V Gg. 7 No.125 12,
                            RT.12/RW.9, Warakas, Kec. Tj. Priok,
                            Jakarta Utara 14370.
                        </p>
                        <a
                            class="button"
                            href="https://maps.app.goo.gl/82xaeQfUQYvyrork8"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Buka Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-main container">
            <p>
                © {{ date('Y') }} Apotek MD Farma. Informasi pada layanan
                konsultasi tidak menggantikan pemeriksaan dokter.
            </p>
            <div class="footer-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('consultation.entry') }}">Konsultasi</a>
                <a href="{{ route('admin.login') }}">Login Admin</a>
            </div>
        </div>
    </footer>
</body>
</html>
