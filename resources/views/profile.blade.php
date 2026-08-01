<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        name="description"
        content="Kenali Apotek MD Farma yang melayani masyarakat Warakas sejak 2022, lengkap dengan konsultasi farmasi digital, layanan resep, lokasi, dan peluang kerja sama."
    >
    <meta name="theme-color" content="#1238cc">

    <title>Profil Apotek MD Farma | Melayani Sejak 2022</title>

    <style>
        :root {
            --brand: #1238cc;
            --brand-dark: #1735a6;
            --brand-deep: #232b3a;
            --brand-soft: #eef2ff;
            --teal: #53658d;
            --amber: #f1a62a;
            --surface: #ffffff;
            --canvas: #f7f8fb;
            --slate-950: #1f2937;
            --slate-850: #213029;
            --slate-700: #4b5563;
            --slate-600: #687080;
            --slate-500: #7f8795;
            --slate-300: #d6dae2;
            --slate-200: #e3e6ec;
            --slate-100: #f1f3f7;
            --shadow-sm: 0 10px 30px rgba(18, 56, 204, .075);
            --shadow-md: 0 22px 60px rgba(18, 56, 204, .13);
            --radius-lg: 26px;
            --radius-md: 19px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
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

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid rgba(83, 101, 141, .35);
            outline-offset: 3px;
        }

        svg {
            display: block;
        }

        .container {
            width: min(1140px, calc(100% - 40px));
            margin-inline: auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(207, 220, 213, .82);
            background: rgba(255, 255, 255, .94);
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
            flex: 0 0 auto;
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
            box-shadow: 0 10px 24px rgba(18, 56, 204, .24);
        }

        .brand-mark svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .nav-links a {
            color: var(--slate-700);
            text-decoration: none;
            font-size: 13px;
            font-weight: 760;
            white-space: nowrap;
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
            padding: 0 17px;
            border-radius: 11px;
            color: #fff !important;
            background: var(--brand);
            box-shadow: 0 8px 20px rgba(18, 56, 204, .18);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 94px 0 88px;
            background:
                radial-gradient(circle at 88% 8%, rgba(83, 101, 141, .20), transparent 27%),
                radial-gradient(circle at 5% 90%, rgba(18, 56, 204, .10), transparent 28%),
                linear-gradient(135deg, #fbfcfe 0%, #eef2ff 100%);
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 480px;
            height: 480px;
            right: -245px;
            bottom: -310px;
            border: 82px solid rgba(18, 56, 204, .052);
            border-radius: 50%;
        }

        .hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.14fr) minmax(340px, .86fr);
            align-items: center;
            gap: 72px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 22px;
            padding: 8px 12px;
            border: 1px solid rgba(18, 56, 204, .17);
            border-radius: 999px;
            color: var(--brand-dark);
            background: rgba(255, 255, 255, .75);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .eyebrow-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--brand);
            box-shadow: 0 0 0 5px rgba(18, 56, 204, .10);
        }

        .hero h1 {
            max-width: 720px;
            margin: 0;
            font-size: clamp(42px, 5.6vw, 70px);
            line-height: 1.02;
            letter-spacing: -.058em;
        }

        .hero h1 span {
            color: var(--brand);
        }

        .lead {
            max-width: 680px;
            margin: 25px 0 0;
            color: var(--slate-600);
            font-size: 17px;
            line-height: 1.82;
        }

        .hero-actions,
        .inline-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 31px;
        }

        .button {
            min-height: 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 21px;
            border: 1px solid var(--slate-200);
            border-radius: 13px;
            color: var(--slate-850);
            background: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 850;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .button:hover {
            transform: translateY(-2px);
            border-color: var(--slate-300);
            box-shadow: var(--shadow-sm);
        }

        .button-primary {
            border-color: var(--brand);
            color: #fff;
            background: var(--brand);
            box-shadow: 0 14px 30px rgba(18, 56, 204, .23);
        }

        .button-primary:hover {
            border-color: var(--brand-dark);
            background: var(--brand-dark);
            box-shadow: 0 16px 34px rgba(18, 56, 204, .27);
        }

        .button-ghost {
            border-color: rgba(255, 255, 255, .55);
            color: #fff;
            background: rgba(255, 255, 255, .10);
        }

        .button svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .identity-card {
            position: relative;
            padding: 29px;
            border: 1px solid rgba(207, 220, 213, .88);
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, .92);
            box-shadow: var(--shadow-md);
        }

        .identity-year {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--slate-100);
        }

        .identity-year small {
            display: block;
            margin-bottom: 6px;
            color: var(--slate-500);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .identity-year strong {
            color: var(--brand);
            font-size: 46px;
            line-height: 1;
            letter-spacing: -.05em;
        }

        .identity-badge {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 17px;
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
        }

        .identity-badge svg {
            width: 27px;
            height: 27px;
            stroke: currentColor;
        }

        .identity-list {
            display: grid;
            gap: 18px;
            margin: 23px 0 0;
        }

        .identity-item {
            display: grid;
            grid-template-columns: 22px 1fr;
            gap: 13px;
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
            color: var(--slate-850);
            font-size: 12px;
        }

        .section {
            padding: 82px 0;
        }

        .section-white {
            background: #fff;
        }

        .section-deep {
            color: #fff;
            background:
                radial-gradient(circle at 85% 10%, rgba(83, 101, 141, .22), transparent 28%),
                linear-gradient(145deg, var(--brand-deep), var(--brand-dark));
        }

        .section-head {
            max-width: 720px;
            margin-bottom: 38px;
        }

        .section-head.center {
            margin-inline: auto;
            text-align: center;
        }

        .section-kicker {
            display: block;
            margin-bottom: 13px;
            color: var(--brand);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .section-deep .section-kicker {
            color: #a7b8ef;
        }

        .section-head h2 {
            margin: 0;
            font-size: clamp(30px, 4.2vw, 46px);
            line-height: 1.11;
            letter-spacing: -.045em;
        }

        .section-head p {
            margin: 17px 0 0;
            color: var(--slate-600);
            font-size: 15px;
            line-height: 1.82;
        }

        .section-deep .section-head p {
            color: rgba(255, 255, 255, .76);
        }

        .story-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.04fr) minmax(360px, .96fr);
            align-items: stretch;
            gap: 32px;
        }

        .story-copy {
            padding: 34px;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            background: #fff;
            box-shadow: var(--shadow-sm);
        }

        .story-copy p {
            margin: 0;
            color: var(--slate-600);
            font-size: 15px;
            line-height: 1.88;
        }

        .story-copy p + p {
            margin-top: 17px;
        }

        .story-highlight {
            position: relative;
            overflow: hidden;
            padding: 34px;
            border-radius: var(--radius-lg);
            color: #fff;
            background:
                radial-gradient(circle at 100% 0, rgba(255, 255, 255, .18), transparent 32%),
                linear-gradient(145deg, var(--brand-deep), var(--brand));
            box-shadow: var(--shadow-md);
        }

        .story-highlight::after {
            content: "2022";
            position: absolute;
            right: -12px;
            bottom: -22px;
            color: rgba(255, 255, 255, .07);
            font-size: 112px;
            font-weight: 950;
            letter-spacing: -.08em;
        }

        .story-highlight > * {
            position: relative;
            z-index: 1;
        }

        .story-highlight small {
            color: #aabaf0;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .story-highlight h3 {
            max-width: 380px;
            margin: 17px 0 14px;
            font-size: 27px;
            line-height: 1.25;
            letter-spacing: -.03em;
        }

        .story-highlight p {
            max-width: 440px;
            margin: 0;
            color: rgba(255, 255, 255, .78);
            font-size: 13px;
            line-height: 1.8;
        }

        .value-grid,
        .service-grid,
        .trust-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .value-card,
        .service-card,
        .trust-card {
            padding: 27px;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            background: var(--surface);
            box-shadow: var(--shadow-sm);
        }

        .value-card,
        .trust-card {
            min-height: 226px;
        }

        .service-card {
            min-height: 276px;
            display: flex;
            flex-direction: column;
        }

        .card-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            margin-bottom: 24px;
            border-radius: 14px;
            color: var(--brand);
            background: var(--brand-soft);
        }

        .card-icon svg {
            width: 23px;
            height: 23px;
            stroke: currentColor;
        }

        .value-card h3,
        .service-card h3,
        .trust-card h3 {
            margin: 0 0 11px;
            font-size: 18px;
            letter-spacing: -.02em;
        }

        .value-card p,
        .service-card p,
        .trust-card p {
            margin: 0;
            color: var(--slate-600);
            font-size: 13px;
            line-height: 1.78;
        }

        .service-card a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: auto;
            padding-top: 23px;
            color: var(--brand);
            text-decoration: none;
            font-size: 12px;
            font-weight: 850;
        }

        .service-card a svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .process-step {
            position: relative;
            padding: 0 24px 0 0;
        }

        .process-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 25px;
            right: -10px;
            width: 42px;
            border-top: 1px dashed rgba(255, 255, 255, .34);
        }

        .step-number {
            width: 50px;
            height: 50px;
            display: grid;
            place-items: center;
            margin-bottom: 22px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 16px;
            color: #fff;
            background: rgba(255, 255, 255, .10);
            font-size: 15px;
            font-weight: 900;
        }

        .process-step h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .process-step p {
            margin: 0;
            color: rgba(255, 255, 255, .74);
            font-size: 13px;
            line-height: 1.78;
        }

        .trust-note {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 15px;
            margin-top: 23px;
            padding: 18px 20px;
            border: 1px solid #f2dfb5;
            border-radius: 16px;
            color: #6c511c;
            background: #fffaf0;
        }

        .trust-note svg {
            width: 22px;
            height: 22px;
            color: var(--amber);
            stroke: currentColor;
        }

        .trust-note strong {
            display: block;
            margin-bottom: 4px;
            color: #5b4315;
            font-size: 13px;
        }

        .trust-note span {
            font-size: 12px;
            line-height: 1.68;
        }

        .partnership-card {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(330px, .92fr);
            align-items: center;
            gap: 42px;
            overflow: hidden;
            padding: 46px;
            border-radius: var(--radius-lg);
            color: #fff;
            background:
                radial-gradient(circle at 100% 0, rgba(83, 101, 141, .30), transparent 32%),
                linear-gradient(145deg, #232b3a, #1735a6);
            box-shadow: var(--shadow-md);
        }

        .partnership-card h2 {
            margin: 0;
            font-size: clamp(29px, 4vw, 43px);
            line-height: 1.14;
            letter-spacing: -.045em;
        }

        .partnership-card p {
            max-width: 650px;
            margin: 17px 0 0;
            color: rgba(255, 255, 255, .76);
            font-size: 14px;
            line-height: 1.82;
        }

        .partnership-points {
            display: grid;
            gap: 13px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 19px;
            background: rgba(255, 255, 255, .08);
        }

        .partnership-point {
            display: grid;
            grid-template-columns: 22px 1fr;
            gap: 11px;
            color: rgba(255, 255, 255, .86);
            font-size: 12px;
            line-height: 1.6;
        }

        .partnership-point svg {
            width: 20px;
            height: 20px;
            color: #a7b8ef;
            stroke: currentColor;
        }

        .visit-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            background: #fff;
            box-shadow: var(--shadow-md);
        }

        .visit-copy {
            padding: 40px;
        }

        .visit-copy h2 {
            margin: 0;
            font-size: clamp(29px, 4vw, 42px);
            line-height: 1.14;
            letter-spacing: -.045em;
        }

        .visit-copy > p {
            margin: 16px 0 0;
            color: var(--slate-600);
            font-size: 14px;
            line-height: 1.8;
        }

        .schedule {
            display: grid;
            gap: 10px;
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
            color: var(--slate-850);
        }

        .visit-address {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            color: #fff;
            background:
                radial-gradient(circle at 90% 0, rgba(255, 255, 255, .15), transparent 30%),
                linear-gradient(145deg, var(--brand-deep), var(--brand));
        }

        .visit-address > svg {
            width: 35px;
            height: 35px;
            margin-bottom: 22px;
            stroke: currentColor;
        }

        .visit-address strong {
            font-size: 21px;
        }

        .visit-address p {
            margin: 13px 0 25px;
            color: rgba(255, 255, 255, .82);
            font-size: 13px;
            line-height: 1.8;
        }

        .visit-address .button {
            align-self: flex-start;
        }

        .final-cta {
            padding: 76px 0;
            text-align: center;
            background: #fff;
        }

        .final-cta-inner {
            max-width: 760px;
            margin-inline: auto;
        }

        .final-cta h2 {
            margin: 0;
            font-size: clamp(31px, 4.2vw, 47px);
            line-height: 1.12;
            letter-spacing: -.045em;
        }

        .final-cta p {
            margin: 16px 0 0;
            color: var(--slate-600);
            font-size: 15px;
            line-height: 1.8;
        }

        .final-cta .inline-actions {
            justify-content: center;
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
            padding: 32px 0;
            border-top: 1px solid var(--slate-100);
        }

        .footer-main p {
            max-width: 540px;
            margin: 0;
            font-size: 11px;
            line-height: 1.7;
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        .footer-links a {
            color: var(--slate-600);
            text-decoration: none;
            font-size: 11px;
            font-weight: 750;
        }

        @media (max-width: 930px) {
            .hero-grid,
            .story-grid,
            .partnership-card,
            .visit-card {
                grid-template-columns: 1fr;
            }

            .identity-card {
                max-width: 650px;
            }

            .value-grid,
            .service-grid,
            .trust-grid {
                grid-template-columns: 1fr;
            }

            .value-card,
            .service-card,
            .trust-card {
                min-height: auto;
            }

            .process-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .process-step {
                padding-right: 0;
            }

            .process-step:not(:last-child)::after {
                display: none;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1140px);
            }

            .nav {
                min-height: 66px;
            }

            .nav-links {
                gap: 13px;
            }

            .nav-links a:not(.active):not(.nav-cta) {
                display: none;
            }

            .hero {
                padding: 60px 0 66px;
            }

            .hero-grid {
                gap: 40px;
            }

            .lead {
                font-size: 15px;
            }

            .section {
                padding: 66px 0;
            }

            .story-copy,
            .story-highlight,
            .partnership-card,
            .visit-copy,
            .visit-address {
                padding: 28px;
            }

            .footer-main {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 500px) {
            .brand {
                font-size: 15px;
            }

            .brand-mark {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .nav-links .active {
                display: none;
            }

            .nav-cta {
                min-height: 40px;
                padding-inline: 14px;
                font-size: 12px !important;
            }

            .hero h1 {
                font-size: 41px;
            }

            .hero-actions .button,
            .final-cta .button {
                width: 100%;
            }

            .identity-card,
            .value-card,
            .service-card,
            .trust-card {
                padding: 22px;
            }

            .identity-year strong {
                font-size: 40px;
            }

            .schedule-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }

            .partnership-card .button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                scroll-behavior: auto !important;
                transition: none !important;
            }
        }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/md-farma-logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/md-farma-logo-theme.css') }}">
</head>
<body>
    <header class="topbar">
        <nav class="nav container" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/md-farma-logo.jpeg') }}" alt="" width="52" height="52"></span>
                <span>MD Farma</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a class="active" href="{{ route('profile') }}" aria-current="page">Profil</a>
                <a href="#layanan">Layanan</a>
                <a href="{{ route('partnership') }}">Kerja Sama</a>
                <a class="nav-cta" href="{{ route('consultation.entry') }}">Mulai Konsultasi</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-grid container">
                <div>
                    <span class="eyebrow">
                        <span class="eyebrow-dot" aria-hidden="true"></span>
                        Melayani sejak 2022
                    </span>

                    <h1>
                        Apotek lokal yang hadir lebih
                        <span>dekat dan mudah diakses.</span>
                    </h1>

                    <p class="lead">
                        MD Farma melayani masyarakat Warakas dan sekitarnya
                        melalui layanan apotek langsung, konsultasi farmasi
                        digital, serta akses pembelian melalui marketplace resmi.
                    </p>

                    <div class="hero-actions">
                        <a class="button button-primary" href="{{ route('consultation.entry') }}">
                            Mulai Konsultasi
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                        <a class="button" href="#tentang">Kenali MD Farma</a>
                    </div>
                </div>

                <aside class="identity-card" aria-label="Ringkasan profil MD Farma">
                    <div class="identity-year">
                        <div>
                            <small>Berdiri sejak</small>
                            <strong>2022</strong>
                        </div>
                        <span class="identity-badge" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2h12l2 5H4z"/>
                                <path d="M4 7v13h16V7M9 12h6M12 9v6"/>
                            </svg>
                        </span>
                    </div>

                    <div class="identity-list">
                        <div class="identity-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            <span>
                                <strong>Berbasis di Warakas</strong>
                                Tanjung Priok, Jakarta Utara
                            </span>
                        </div>

                        <div class="identity-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                            <span>
                                <strong>Konsultasi berbasis browser</strong>
                                Tanpa proses pembuatan akun yang rumit
                            </span>
                        </div>

                        <div class="identity-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 6h18M6 6V4h12v2M5 6l1 14h12l1-14M9 10v6M15 10v6"/>
                            </svg>
                            <span>
                                <strong>Layanan obat dan resep</strong>
                                Mendukung lampiran foto maupun dokumen
                            </span>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section section-white" id="tentang">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Tentang kami</span>
                    <h2>Tumbuh bersama kebutuhan kesehatan masyarakat sekitar.</h2>
                    <p>
                        Halaman ini membantu pasien, keluarga, dan calon mitra
                        memahami siapa MD Farma, layanan yang tersedia, serta
                        cara berinteraksi dengan kami.
                    </p>
                </div>

                <div class="story-grid">
                    <div class="story-copy">
                        <p>
                            Apotek MD Farma berdiri sejak tahun 2022 dan berlokasi
                            di Warakas, Tanjung Priok, Jakarta Utara. Sejak awal,
                            MD Farma berupaya menjadi titik layanan farmasi yang
                            mudah dijangkau oleh masyarakat sekitar.
                        </p>
                        <p>
                            Seiring berkembangnya kebutuhan pasien, layanan tidak
                            hanya tersedia secara langsung di apotek. MD Farma juga
                            menghadirkan konsultasi digital berbasis browser agar
                            pertanyaan mengenai obat dan resep dapat disampaikan
                            secara lebih praktis.
                        </p>
                        <p>
                            Kehadiran kanal digital dan marketplace resmi menjadi
                            bagian dari upaya MD Farma untuk mempermudah akses,
                            tanpa menghilangkan peran layanan apotek secara langsung.
                        </p>
                    </div>

                    <aside class="story-highlight">
                        <small>Arah pelayanan</small>
                        <h3>Lebih mudah ditemukan, dipahami, dan dihubungi.</h3>
                        <p>
                            MD Farma mengembangkan pengalaman layanan yang jelas:
                            pengunjung dapat mengenali layanan, memulai konsultasi,
                            menemukan lokasi, atau membuka pembicaraan kerja sama
                            melalui jalur yang sesuai kebutuhannya.
                        </p>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-head center">
                    <span class="section-kicker">Nilai pelayanan</span>
                    <h2>Pengalaman yang sederhana tanpa mengabaikan kejelasan.</h2>
                    <p>
                        Tiga prinsip berikut menjadi dasar penyusunan alur layanan
                        MD Farma, baik ketika pasien datang langsung maupun menggunakan kanal digital.
                    </p>
                </div>

                <div class="value-grid">
                    <article class="value-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                        </span>
                        <h3>Mudah diakses</h3>
                        <p>
                            Konsultasi dapat dimulai langsung melalui browser,
                            sehingga pasien tidak perlu melewati proses registrasi panjang.
                        </p>
                    </article>

                    <article class="value-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s7-3.5 7-10V5l-7-3-7 3v6c0 6.5 7 10 7 10Z"/>
                                <path d="m9 11 2 2 4-4"/>
                            </svg>
                        </span>
                        <h3>Percakapan lebih privat</h3>
                        <p>
                            Setiap konsultasi menggunakan identitas unik dan akses
                            pasien terikat pada sesi perangkat yang digunakan.
                        </p>
                    </article>

                    <article class="value-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6M8 13h8M8 17h6"/>
                            </svg>
                        </span>
                        <h3>Informasi lebih terarah</h3>
                        <p>
                            Foto obat atau dokumen resep dapat dilampirkan untuk
                            membantu memperjelas konteks pertanyaan pasien.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section section-white" id="layanan">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Layanan utama</span>
                    <h2>Pilih jalur layanan sesuai kebutuhan Anda.</h2>
                    <p>
                        Setiap layanan memiliki tujuan yang jelas agar pengunjung
                        dapat mengambil langkah berikutnya tanpa kebingungan.
                    </p>
                </div>

                <div class="service-grid">
                    <article class="service-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                                <path d="M8 8h8M8 12h5"/>
                            </svg>
                        </span>
                        <h3>Konsultasi obat dan resep</h3>
                        <p>
                            Tanyakan aturan pakai, efek samping, interaksi obat,
                            atau kirim foto resep melalui satu ruang live chat yang sama.
                        </p>
                        <a href="{{ route('consultation.entry') }}">
                            Mulai konsultasi
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </article>

                    <article class="service-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2h12l2 5H4z"/>
                                <path d="M4 7v13h16V7M9 11h6"/>
                            </svg>
                        </span>
                        <h3>Belanja produk resmi</h3>
                        <p>
                            Temukan produk MD Farma melalui kanal marketplace resmi
                            yang tersedia pada landing page.
                        </p>
                        <a href="{{ route('home') }}#marketplace">
                            Lihat marketplace
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </article>

                    <article class="service-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 11V7a4 4 0 0 0-8 0v4"/>
                                <path d="M5 9h14l1 12H4L5 9Z"/>
                                <path d="M9 15h6"/>
                            </svg>
                        </span>
                        <h3>Kerja sama bisnis</h3>
                        <p>
                            Pelajari peluang kolaborasi dengan MD Farma untuk kebutuhan
                            institusi, komunitas, maupun mitra usaha.
                        </p>
                        <a href="{{ route('partnership') }}">
                            Jelajahi kerja sama
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <section class="section section-deep">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Cara menggunakan konsultasi</span>
                    <h2>Tiga langkah untuk memulai percakapan.</h2>
                    <p>
                        Alur dibuat singkat agar pasien dapat fokus menjelaskan
                        kebutuhan dan mengirim informasi pendukung yang relevan.
                    </p>
                </div>

                <div class="process-grid">
                    <article class="process-step">
                        <span class="step-number">01</span>
                        <h3>Isi data dasar</h3>
                        <p>
                            Masukkan nama, umur, nomor telepon, dan pilih jenis
                            konsultasi yang dibutuhkan.
                        </p>
                    </article>

                    <article class="process-step">
                        <span class="step-number">02</span>
                        <h3>Jelaskan kebutuhan</h3>
                        <p>
                            Tulis pertanyaan dan sertakan foto obat atau resep
                            bila informasi tersebut diperlukan.
                        </p>
                    </article>

                    <article class="process-step">
                        <span class="step-number">03</span>
                        <h3>Lanjutkan percakapan</h3>
                        <p>
                            Balasan dapat dibaca pada ruang konsultasi yang sama
                            selama akses pasien masih aktif.
                        </p>
                    </article>
                </div>

                <div class="inline-actions">
                    <a class="button button-ghost" href="{{ route('consultation.entry') }}">
                        Buka Konsultasi
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-head center">
                    <span class="section-kicker">Membangun kepercayaan</span>
                    <h2>Informasi yang jelas membantu pasien mengambil keputusan.</h2>
                    <p>
                        MD Farma menempatkan transparansi alur, akses yang mudah,
                        dan batas layanan sebagai bagian penting dari pengalaman pasien.
                    </p>
                </div>

                <div class="trust-grid">
                    <article class="trust-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s7-3.5 7-10V5l-7-3-7 3v6c0 6.5 7 10 7 10Z"/>
                            </svg>
                        </span>
                        <h3>Akses konsultasi terkontrol</h3>
                        <p>
                            Ruang percakapan menggunakan identitas konsultasi unik
                            dan pemeriksaan akses pada setiap permintaan pasien.
                        </p>
                    </article>

                    <article class="trust-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 8v4l3 2"/>
                            </svg>
                        </span>
                        <h3>Jam layanan terlihat jelas</h3>
                        <p>
                            Informasi operasional dan lokasi ditampilkan agar pasien
                            dapat memperkirakan waktu kunjungan atau respons layanan.
                        </p>
                    </article>

                    <article class="trust-card">
                        <span class="card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 11v5M12 8h.01"/>
                            </svg>
                        </span>
                        <h3>Batas layanan diinformasikan</h3>
                        <p>
                            Konsultasi digital berfungsi sebagai layanan informasi
                            farmasi dan tidak menggantikan pemeriksaan langsung oleh dokter.
                        </p>
                    </article>
                </div>

                <div class="trust-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <div>
                        <strong>Untuk kondisi darurat atau gejala berat</strong>
                        <span>
                            Segera hubungi fasilitas kesehatan atau layanan kegawatdaruratan terdekat.
                            Jangan menunggu balasan konsultasi digital.
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-white">
            <div class="container">
                <div class="partnership-card">
                    <div>
                        <span class="section-kicker">Peluang kerja sama</span>
                        <h2>Mari membuka peluang kolaborasi yang relevan.</h2>
                        <p>
                            MD Farma terbuka untuk pembicaraan kerja sama bisnis yang
                            selaras dengan layanan farmasi, distribusi, program kesehatan,
                            maupun kebutuhan kolaborasi lain yang dapat dibahas lebih lanjut.
                        </p>
                        <div class="inline-actions">
                            <a class="button button-ghost" href="{{ route('partnership') }}">
                                Lihat Halaman Kerja Sama
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="partnership-points" aria-label="Tujuan halaman kerja sama">
                        <div class="partnership-point">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                            <span>Kontak bisnis berada pada halaman khusus dan mudah ditemukan.</span>
                        </div>
                        <div class="partnership-point">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                            <span>Calon mitra dapat membuka WhatsApp melalui tombol atau QR.</span>
                        </div>
                        <div class="partnership-point">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                            <span>Alur kerja sama dipisahkan dari konsultasi pasien.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="lokasi">
            <div class="container">
                <div class="visit-card">
                    <div class="visit-copy">
                        <span class="section-kicker">Lokasi dan operasional</span>
                        <h2>Kunjungi Apotek MD Farma.</h2>
                        <p>
                            Gunakan informasi berikut sebagai acuan untuk kunjungan
                            langsung dan waktu layanan konsultasi digital.
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
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
                            class="button button-ghost"
                            href="https://maps.app.goo.gl/82xaeQfUQYvyrork8"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Buka Google Maps
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 3h7v7M10 14 21 3M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="final-cta">
            <div class="final-cta-inner container">
                <span class="section-kicker">Langkah berikutnya</span>
                <h2>Butuh informasi obat atau ingin mengenal MD Farma lebih lanjut?</h2>
                <p>
                    Pilih konsultasi untuk kebutuhan pasien atau buka halaman kerja sama
                    untuk pembicaraan bisnis. Kedua alur dipisahkan agar lebih jelas.
                </p>
                <div class="inline-actions">
                    <a class="button button-primary" href="{{ route('consultation.entry') }}">
                        Mulai Konsultasi
                    </a>
                    <a class="button" href="{{ route('partnership') }}">
                        Buka Kerja Sama
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-main container">
            <p>
                © {{ date('Y') }} Apotek MD Farma. Informasi pada layanan konsultasi
                tidak menggantikan pemeriksaan dan diagnosis dokter.
            </p>
            <div class="footer-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('profile') }}">Profil</a>
                <a href="{{ route('partnership') }}">Kerja Sama</a>
                <a href="{{ route('consultation.entry') }}">Konsultasi</a>
                <a href="{{ route('admin.login') }}">Login Admin</a>
            </div>
        </div>
    </footer>
</body>
</html>
