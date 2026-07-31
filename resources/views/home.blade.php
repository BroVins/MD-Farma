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
        content="Konsultasi farmasi dan pembelian obat melalui Apotek MD Farma Jakarta Utara."
    >
    <meta name="theme-color" content="#198754">

    <title>Apotek MD Farma | Konsultasi dan Pembelian Obat</title>

    <style>
        :root {
            --brand: #198754;
            --brand-dark: #0f6840;
            --brand-deep: #0a4f34;
            --brand-soft: #eaf8f1;
            --teal: #18b7a5;
            --teal-soft: #e9faf7;
            --surface: #ffffff;
            --canvas: #f6faf8;
            --canvas-soft: #eef7f2;
            --slate-950: #15201b;
            --slate-800: #26342e;
            --slate-700: #405148;
            --slate-600: #5d6d65;
            --slate-500: #738178;
            --slate-300: #cfdcd5;
            --slate-200: #dee8e3;
            --slate-100: #edf3f0;
            --orange-soft: #fff6e9;
            --orange-border: #f5d4a3;
            --orange-text: #9b5514;
            --shadow-sm: 0 8px 24px rgba(15, 45, 31, .06);
            --shadow-md: 0 18px 50px rgba(15, 45, 31, .11);
            --shadow-brand: 0 16px 34px rgba(25, 135, 84, .22);
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 13px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
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

        body,
        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            color: inherit;
        }

        img,
        svg {
            display: block;
        }

        button,
        a {
            font: inherit;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid rgba(24, 183, 165, .38);
            outline-offset: 3px;
        }

        .container {
            width: min(1160px, calc(100% - 40px));
            margin-inline: auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(207, 220, 213, .82);
            background: rgba(255, 255, 255, .92);
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
            background:
                linear-gradient(145deg, var(--teal), var(--brand));
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

        .nav-links > a {
            color: var(--slate-700);
            text-decoration: none;
            font-size: 13px;
            font-weight: 750;
            transition:
                color .18s ease,
                background .18s ease,
                border-color .18s ease;
        }

        .nav-links > a:hover {
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

        .hero {
            position: relative;
            overflow: hidden;
            padding: 82px 0 76px;
            background:
                radial-gradient(
                    circle at 84% 13%,
                    rgba(24, 183, 165, .16),
                    transparent 29%
                ),
                radial-gradient(
                    circle at 9% 82%,
                    rgba(25, 135, 84, .08),
                    transparent 25%
                ),
                linear-gradient(135deg, #f9fcfa 0%, #eaf8f1 100%);
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::before {
            width: 430px;
            height: 430px;
            top: -250px;
            right: -120px;
            border: 78px solid rgba(24, 183, 165, .05);
        }

        .hero::after {
            width: 300px;
            height: 300px;
            left: -175px;
            bottom: -215px;
            border: 54px solid rgba(25, 135, 84, .05);
        }

        .hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns:
                minmax(0, 1.1fr)
                minmax(350px, .9fr);
            align-items: center;
            gap: 66px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 19px;
            padding: 7px 11px;
            border: 1px solid rgba(25, 135, 84, .12);
            border-radius: 999px;
            color: var(--brand-deep);
            background: rgba(234, 248, 241, .92);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .075em;
            text-transform: uppercase;
        }

        .eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand);
            box-shadow: 0 0 0 4px rgba(25, 135, 84, .11);
        }

        h1 {
            max-width: 760px;
            margin: 0;
            font-size: clamp(42px, 5.8vw, 70px);
            line-height: 1.03;
            letter-spacing: -.055em;
        }

        .accent {
            color: var(--brand);
        }

        .hero-lead {
            max-width: 660px;
            margin: 23px 0 29px;
            color: var(--slate-600);
            font-size: 17px;
            line-height: 1.72;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .button {
            min-height: 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 20px;
            border: 1px solid transparent;
            border-radius: 13px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 850;
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                background .18s ease,
                border-color .18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .button-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--brand), #0b9b68);
            box-shadow: var(--shadow-brand);
        }

        .button-primary:hover {
            box-shadow: 0 19px 38px rgba(25, 135, 84, .26);
        }

        .button-secondary {
            border-color: var(--slate-300);
            color: var(--slate-700);
            background: rgba(255, 255, 255, .88);
        }

        .button-secondary:hover {
            border-color: #a7cbbb;
            background: #fff;
        }

        .trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            margin-top: 27px;
            color: var(--slate-600);
            font-size: 11px;
            font-weight: 720;
        }

        .trust-row span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .trust-row i {
            width: 19px;
            height: 19px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: var(--brand);
            background: var(--brand-soft);
            font-size: 10px;
            font-style: normal;
            font-weight: 950;
        }

        .hero-card {
            position: relative;
            padding: 23px;
            border: 1px solid rgba(255, 255, 255, .82);
            border-radius: 27px;
            background: rgba(255, 255, 255, .84);
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(18px);
        }

        .hero-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .pharmacist {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .pharmacist-avatar {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 15px;
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
            box-shadow: 0 9px 22px rgba(25, 135, 84, .18);
            font-size: 13px;
            font-weight: 900;
        }

        .pharmacist-copy strong {
            display: block;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .pharmacist-copy span {
            color: var(--slate-500);
            font-size: 10px;
        }

        .open-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid rgba(25, 135, 84, .1);
            border-radius: 999px;
            color: var(--brand-dark);
            background: var(--brand-soft);
            font-size: 9px;
            font-weight: 900;
        }

        .open-status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand);
            box-shadow: 0 0 0 4px rgba(25, 135, 84, .1);
        }

        .open-status.closed {
            border-color: rgba(155, 85, 20, .12);
            color: var(--orange-text);
            background: var(--orange-soft);
        }

        .open-status.closed::before {
            background: #e78b2f;
            box-shadow: 0 0 0 4px rgba(231, 139, 47, .11);
        }

        .chat-preview {
            min-height: 289px;
            padding: 16px;
            border: 1px solid var(--slate-200);
            border-radius: 18px;
            background:
                linear-gradient(
                    rgba(248, 251, 250, .965),
                    rgba(248, 251, 250, .965)
                ),
                radial-gradient(
                    circle at 14px 14px,
                    rgba(25, 135, 84, .055) 1px,
                    transparent 1.2px
                );
            background-size: auto, 30px 30px;
        }

        .chat-day {
            width: fit-content;
            margin: 0 auto 15px;
            padding: 5px 9px;
            border: 1px solid var(--slate-200);
            border-radius: 999px;
            color: var(--slate-500);
            background: rgba(255, 255, 255, .94);
            font-size: 8px;
            font-weight: 800;
        }

        .chat-reminder {
            display: flex;
            gap: 9px;
            margin-bottom: 14px;
            padding: 10px 11px;
            border: 1px solid rgba(24, 183, 165, .16);
            border-radius: 13px;
            color: var(--slate-600);
            background: rgba(233, 250, 247, .8);
            font-size: 9px;
            line-height: 1.5;
        }

        .chat-reminder svg {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
            color: var(--teal);
            stroke: currentColor;
        }

        .mock-bubble {
            width: fit-content;
            min-width: 126px;
            max-width: 82%;
            margin-bottom: 10px;
            padding: 10px 12px 8px;
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            color: var(--slate-800);
            background: #fff;
            box-shadow: var(--shadow-sm);
            font-size: 10px;
            line-height: 1.5;
        }

        .mock-bubble.user {
            margin-left: auto;
            border-color: #c5ead8;
            border-top-right-radius: 5px;
            background: #dcf7e9;
        }

        .mock-bubble.pharmacy {
            border-top-left-radius: 5px;
        }

        .mock-bubble strong {
            display: block;
            margin-bottom: 3px;
            color: var(--brand-dark);
            font-size: 8px;
        }

        .mock-bubble time {
            display: block;
            margin-top: 5px;
            color: var(--slate-500);
            text-align: right;
            font-size: 7px;
        }

        .hero-schedule {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
            margin-top: 13px;
        }

        .hero-schedule-item {
            padding: 10px 11px;
            border: 1px solid var(--slate-200);
            border-radius: 13px;
            background: rgba(255, 255, 255, .82);
        }

        .hero-schedule-item span {
            display: block;
            margin-bottom: 4px;
            color: var(--slate-500);
            font-size: 8px;
            font-weight: 750;
        }

        .hero-schedule-item strong {
            color: var(--slate-800);
            font-size: 10px;
        }

        .section {
            padding: 82px 0;
        }

        .section-muted {
            border-block: 1px solid var(--slate-200);
            background: var(--canvas-soft);
        }

        .section-head {
            max-width: 700px;
            margin-bottom: 34px;
        }

        .section-kicker {
            display: inline-block;
            margin-bottom: 10px;
            color: var(--brand);
            font-size: 10px;
            font-weight: 950;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .section-head h2 {
            margin: 0 0 13px;
            font-size: clamp(30px, 4vw, 45px);
            line-height: 1.12;
            letter-spacing: -.045em;
        }

        .section-head p {
            margin: 0;
            color: var(--slate-600);
            line-height: 1.72;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .service-card {
            padding: 24px;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            background: var(--surface);
            box-shadow: var(--shadow-sm);
            transition:
                transform .18s ease,
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .service-card:hover {
            transform: translateY(-3px);
            border-color: #b8daca;
            box-shadow: 0 14px 34px rgba(15, 45, 31, .09);
        }

        .service-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            color: var(--brand);
            background: var(--brand-soft);
        }

        .service-icon svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
        }

        .service-card h3 {
            margin: 18px 0 8px;
            font-size: 17px;
        }

        .service-card p {
            margin: 0;
            color: var(--slate-600);
            font-size: 13px;
            line-height: 1.68;
        }

        .info-grid {
            display: grid;
            grid-template-columns:
                minmax(0, .84fr)
                minmax(0, 1.16fr);
            gap: 20px;
        }

        .info-card {
            min-height: 100%;
            padding: 27px;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow-sm);
        }

        .info-card-head {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-bottom: 22px;
        }

        .info-card-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 14px;
            color: var(--brand);
            background: var(--brand-soft);
        }

        .info-card-icon svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
        }

        .info-card-head h3 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        .info-card-head p {
            margin: 0;
            color: var(--slate-500);
            font-size: 11px;
            line-height: 1.55;
        }

        .schedule-list {
            display: grid;
            gap: 10px;
        }

        .schedule-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 13px 14px;
            border: 1px solid var(--slate-200);
            border-radius: 13px;
            background: #f9fbfa;
        }

        .schedule-row span {
            color: var(--slate-600);
            font-size: 12px;
            font-weight: 700;
        }

        .schedule-row strong {
            color: var(--brand-dark);
            font-size: 12px;
        }

        .address {
            margin: 0 0 20px;
            color: var(--slate-700);
            font-size: 13px;
            line-height: 1.78;
        }

        .map-link {
            width: fit-content;
        }

        .marketplace-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .marketplace-card {
            min-height: 174px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 22px;
            padding: 21px;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            color: var(--slate-950);
            background: var(--surface);
            box-shadow: var(--shadow-sm);
            text-decoration: none;
            transition:
                transform .18s ease,
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .marketplace-card:hover {
            transform: translateY(-4px);
            border-color: #a9d4c0;
            box-shadow: 0 17px 38px rgba(15, 45, 31, .11);
        }

        .marketplace-logo-wrap {
            min-height: 64px;
            display: flex;
            align-items: center;
            padding: 13px 14px;
            border: 1px solid var(--slate-100);
            border-radius: 13px;
            background: #fbfdfc;
        }

        .marketplace-logo {
            width: 100%;
            height: 42px;
            object-fit: contain;
            object-position: left center;
        }

        .marketplace-copy strong {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .marketplace-copy span {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 9px;
            color: var(--slate-500);
            font-size: 10px;
            font-weight: 700;
        }

        .marketplace-copy svg {
            width: 15px;
            height: 15px;
            color: var(--brand);
            stroke: currentColor;
        }

        .notice {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-top: 30px;
            padding: 18px 20px;
            border: 1px solid var(--orange-border);
            border-radius: var(--radius-md);
            color: var(--orange-text);
            background: var(--orange-soft);
        }

        .notice-icon {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 12px;
            background: rgba(245, 212, 163, .55);
        }

        .notice-icon svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
        }

        .notice strong {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
        }

        .notice p {
            margin: 0;
            font-size: 11px;
            line-height: 1.65;
        }

        .partnership-section {
            padding: 0 0 28px;
        }

        .partnership-card {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(290px, .8fr);
            align-items: center;
            gap: 44px;
            padding: 40px;
            border: 1px solid rgba(25, 135, 84, .15);
            border-radius: 27px;
            background:
                radial-gradient(
                    circle at 94% 8%,
                    rgba(24, 183, 165, .15),
                    transparent 29%
                ),
                linear-gradient(135deg, #ffffff, #eef9f3);
            box-shadow: var(--shadow-md);
        }

        .partnership-copy {
            position: relative;
            z-index: 1;
        }

        .partnership-copy h2 {
            max-width: 700px;
            margin: 0 0 13px;
            font-size: clamp(29px, 4vw, 43px);
            line-height: 1.12;
            letter-spacing: -.044em;
        }

        .partnership-copy > p {
            max-width: 670px;
            margin: 0;
            color: var(--slate-600);
            line-height: 1.72;
        }

        .partnership-points {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin: 22px 0 26px;
        }

        .partnership-points span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid rgba(25, 135, 84, .13);
            border-radius: 999px;
            color: var(--brand-deep);
            background: rgba(255, 255, 255, .82);
            font-size: 11px;
            font-weight: 800;
        }

        .partnership-points i {
            width: 18px;
            height: 18px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: #fff;
            background: var(--brand);
            font-size: 9px;
            font-style: normal;
        }

        .partnership-guide {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 10px;
            padding: 21px;
            border: 1px solid rgba(207, 220, 213, .9);
            border-radius: 20px;
            background: rgba(255, 255, 255, .86);
            box-shadow: var(--shadow-sm);
        }

        .partnership-guide strong {
            margin-bottom: 3px;
            font-size: 14px;
        }

        .partnership-step {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px;
            border-radius: 13px;
            color: var(--slate-700);
            background: #f7fbf9;
            font-size: 12px;
            line-height: 1.45;
        }

        .partnership-step b {
            width: 28px;
            height: 28px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border-radius: 9px;
            color: var(--brand-deep);
            background: var(--brand-soft);
            font-size: 11px;
        }

        .cta-section {
            padding: 0 0 82px;
        }

        .cta-card {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 34px;
            padding: 37px 40px;
            border-radius: 27px;
            color: #fff;
            background:
                radial-gradient(
                    circle at 95% 15%,
                    rgba(255, 255, 255, .15),
                    transparent 25%
                ),
                linear-gradient(135deg, var(--brand-deep), var(--brand));
            box-shadow: var(--shadow-brand);
        }

        .cta-card::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -110px;
            bottom: -180px;
            border: 42px solid rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .cta-copy {
            position: relative;
            z-index: 1;
            max-width: 660px;
        }

        .cta-copy h2 {
            margin: 0 0 10px;
            font-size: clamp(27px, 4vw, 39px);
            letter-spacing: -.04em;
        }

        .cta-copy p {
            margin: 0;
            color: rgba(255, 255, 255, .78);
            line-height: 1.65;
        }

        .cta-card .button {
            position: relative;
            z-index: 1;
            flex: 0 0 auto;
            color: var(--brand-deep);
            background: #fff;
            box-shadow: 0 12px 27px rgba(5, 46, 43, .22);
        }

        .footer {
            border-top: 1px solid var(--slate-200);
            background: #fff;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.3fr .8fr 1fr;
            gap: 40px;
            padding: 44px 0 35px;
        }

        .footer-brand {
            margin-bottom: 15px;
        }

        .footer-text,
        .footer-address {
            margin: 0;
            color: var(--slate-600);
            font-size: 11px;
            line-height: 1.75;
        }

        .footer h3 {
            margin: 0 0 13px;
            font-size: 12px;
        }

        .footer-links {
            display: grid;
            gap: 9px;
        }

        .footer-links a {
            color: var(--slate-600);
            text-decoration: none;
            font-size: 11px;
        }

        .footer-links a:hover {
            color: var(--brand);
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 0;
            border-top: 1px solid var(--slate-100);
            color: var(--slate-500);
            font-size: 10px;
        }

        .mobile-cta {
            display: none;
        }

        @media (max-width: 980px) {
            .hero-grid,
            .info-grid,
            .partnership-card {
                grid-template-columns: 1fr;
            }

            .partnership-card {
                gap: 28px;
            }

            .hero-grid {
                gap: 46px;
            }

            .hero-card {
                max-width: 660px;
            }

            .marketplace-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .footer-grid > :first-child {
                grid-column: 1 / -1;
                max-width: 650px;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1160px);
            }

            .nav {
                min-height: 66px;
            }

            .nav-links > a:not(.nav-cta):not(.nav-profile) {
                display: none;
            }

            .nav-cta {
                min-height: 39px;
                padding-inline: 13px;
                font-size: 11px !important;
            }

            .hero {
                padding: 58px 0 61px;
            }

            .hero-grid {
                gap: 37px;
            }

            h1 {
                font-size: clamp(39px, 13vw, 57px);
            }

            .hero-lead {
                font-size: 15px;
            }

            .service-grid,
            .marketplace-grid {
                grid-template-columns: 1fr;
            }

            .marketplace-card {
                min-height: 150px;
            }

            .section {
                padding: 65px 0;
            }

            .partnership-card {
                padding: 30px 26px;
            }

            .cta-card {
                align-items: flex-start;
                flex-direction: column;
                padding: 29px 25px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .footer-grid > :first-child {
                grid-column: auto;
            }

            .footer-bottom {
                align-items: flex-start;
                flex-direction: column;
                gap: 7px;
            }
        }

        @media (max-width: 520px) {
            body {
                padding-bottom: 73px;
            }

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

            .hero-actions .button {
                width: 100%;
            }

            .hero-card {
                padding: 16px;
                border-radius: 21px;
            }

            .hero-card-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .chat-preview {
                padding: 12px;
            }

            .hero-schedule {
                grid-template-columns: 1fr;
            }

            .info-card {
                padding: 21px;
            }

            .schedule-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .marketplace-logo-wrap {
                min-height: 58px;
            }

            .notice {
                padding: 16px;
            }

            .partnership-card {
                padding: 25px 21px;
                border-radius: 21px;
            }

            .partnership-actions .button {
                width: 100%;
            }

            .partnership-guide {
                padding: 15px;
            }

            .mobile-cta {
                position: fixed;
                right: 12px;
                bottom: 12px;
                left: 12px;
                z-index: 60;
                min-height: 49px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                border-radius: 14px;
                color: #fff;
                background: linear-gradient(135deg, var(--brand), #0b9b68);
                box-shadow: 0 14px 34px rgba(25, 135, 84, .3);
                text-decoration: none;
                font-size: 12px;
                font-weight: 900;
            }

            .mobile-cta svg {
                width: 18px;
                height: 18px;
                stroke: currentColor;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
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
                <a class="nav-profile" href="{{ route('profile') }}">
                    Profil
                </a>
                <a href="#layanan">Layanan</a>
                <a href="#operasional">Jam dan Lokasi</a>
                <a href="#marketplace">Marketplace</a>
                <a data-partnership-nav href="#kerja-sama">Kerja Sama</a>
                <a class="nav-cta" href="{{ route('consultation.entry') }}">
                    Mulai Konsultasi
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-grid container">
                <div>
                    <p class="eyebrow">
                        <span class="eyebrow-dot" aria-hidden="true"></span>
                        Konsultasi farmasi digital
                    </p>

                    <h1>
                        Kebutuhan obat dan konsultasi,
                        <span class="accent">lebih mudah.</span>
                    </h1>

                    <p class="hero-lead">
                        Konsultasikan penggunaan obat dengan Apotek MD Farma
                        melalui ruang chat privat. Anda juga dapat membeli
                        produk melalui marketplace resmi kami.
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
                            class="button button-secondary"
                            href="#marketplace"
                        >
                            Belanja Obat
                        </a>

                        <a
                            class="button button-secondary"
                            data-partnership-hero
                            href="#kerja-sama"
                        >
                            Kerja Sama
                        </a>
                    </div>

                    <div class="trust-row">
                        <span><i>✓</i>Chat privat</span>
                        <span><i>✓</i>Apoteker MD Farma</span>
                        <span><i>✓</i>Lampiran aman</span>
                        <span><i>✓</i>Riwayat tersimpan otomatis</span>
                    </div>
                </div>

                <aside class="hero-card" aria-label="Pratinjau layanan">
                    <div class="hero-card-head">
                        <div class="pharmacist">
                            <span class="pharmacist-avatar" aria-hidden="true">
                                AP
                            </span>
                            <div class="pharmacist-copy">
                                <strong>Apoteker Apotek MD Farma</strong>
                                <span>Layanan konsultasi farmasi</span>
                            </div>
                        </div>

                        <span
                            class="open-status"
                            data-open-status
                            aria-live="polite"
                        >
                            Memeriksa jam layanan
                        </span>
                    </div>

                    <div class="chat-preview">
                        <div class="chat-day">Hari ini</div>

                        <div class="chat-reminder">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                            <span>
                                Konsultasi dibalas sesuai jam operasional.
                                Pesan di luar jam layanan tetap kami terima
                                dengan waktu respons yang mungkin lebih lama.
                            </span>
                        </div>

                        <div class="mock-bubble user">
                            Halo, saya ingin menanyakan aturan pakai obat.
                            <time>09.14 WIB</time>
                        </div>

                        <div class="mock-bubble pharmacy">
                            <strong>Apoteker</strong>
                            Silakan kirim nama atau foto obat yang ingin
                            dikonsultasikan.
                            <time>09.16 WIB</time>
                        </div>

                        <div class="mock-bubble user">
                            Baik, saya akan mengirim fotonya.
                            <time>09.17 WIB ✓✓</time>
                        </div>
                    </div>

                    <div class="hero-schedule">
                        <div class="hero-schedule-item">
                            <span>Senin sampai Jumat</span>
                            <strong>08.00 - 20.00 WIB</strong>
                        </div>
                        <div class="hero-schedule-item">
                            <span>Sabtu dan Minggu</span>
                            <strong>08.00 - 21.00 WIB</strong>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section" id="layanan">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Layanan MD Farma</span>
                    <h2>Layanan yang sederhana dan mudah diakses</h2>
                    <p>
                        Gunakan layanan sesuai kebutuhan. Anda dapat
                        berkonsultasi lebih dulu atau langsung menuju toko
                        resmi Apotek MD Farma di marketplace.
                    </p>
                </div>

                <div class="service-grid">
                    <article class="service-card">
                        <span class="service-icon" aria-hidden="true">
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
                        <h3>Konsultasi obat</h3>
                        <p>
                            Tanyakan aturan pakai, efek samping, interaksi,
                            atau informasi umum terkait obat kepada apoteker.
                        </p>
                    </article>

                    <article class="service-card">
                        <span class="service-icon" aria-hidden="true">
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
                        <h3>Konsultasi resep</h3>
                        <p>
                            Kirim gambar atau dokumen resep melalui fitur
                            lampiran agar informasi dapat diperiksa dengan
                            lebih terarah.
                        </p>
                    </article>

                    <article class="service-card">
                        <span class="service-icon" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M6 2h12l2 5H4z"/>
                                <path d="M4 7v13h16V7"/>
                                <path d="M9 11h6"/>
                            </svg>
                        </span>
                        <h3>Pembelian melalui marketplace</h3>
                        <p>
                            Kunjungi toko resmi MD Farma di Tokopedia, Shopee,
                            GoApotik, atau Blibli melalui tautan yang tersedia.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section section-muted" id="operasional">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Kunjungi apotek</span>
                    <h2>Jam operasional dan lokasi MD Farma</h2>
                    <p>
                        Informasi ini berlaku untuk layanan apotek dan menjadi
                        acuan waktu respons konsultasi.
                    </p>
                </div>

                <div class="info-grid">
                    <article class="info-card">
                        <div class="info-card-head">
                            <span class="info-card-icon" aria-hidden="true">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                >
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Jam operasional</h3>
                                <p>
                                    Waktu layanan menggunakan zona waktu WIB.
                                </p>
                            </div>
                        </div>

                        <div class="schedule-list">
                            <div class="schedule-row">
                                <span>Senin - Jumat</span>
                                <strong>08.00 - 20.00 WIB</strong>
                            </div>

                            <div class="schedule-row">
                                <span>Sabtu - Minggu</span>
                                <strong>08.00 - 21.00 WIB</strong>
                            </div>
                        </div>
                    </article>

                    <article class="info-card">
                        <div class="info-card-head">
                            <span class="info-card-icon" aria-hidden="true">
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
                            </span>
                            <div>
                                <h3>Alamat Apotek MD Farma</h3>
                                <p>Warakas, Tanjung Priok, Jakarta Utara</p>
                            </div>
                        </div>

                        <p class="address">
                            Jl. Warakas V Gg. 7 No.125 12,
                            RT.12/RW.9, Warakas, Kec. Tj. Priok,
                            Jkt Utara, Daerah Khusus Ibukota Jakarta 14370.
                        </p>

                        <a
                            class="button button-primary map-link"
                            href="https://maps.app.goo.gl/82xaeQfUQYvyrork8"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Buka Google Maps
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M14 3h7v7M10 14 21 3"/>
                                <path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/>
                            </svg>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="marketplace">
            <div class="container">
                <div class="section-head">
                    <span class="section-kicker">Toko online resmi</span>
                    <h2>Belanja obat melalui marketplace pilihan Anda</h2>
                    <p>
                        Klik kartu marketplace untuk membuka toko Apotek MD
                        Farma. Tautan akan dibuka pada tab baru.
                    </p>
                </div>

                <div class="marketplace-grid">
                    <a
                        class="marketplace-card"
                        href="https://www.tokopedia.com/apotek-md-farma-jakarta"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka toko Apotek MD Farma di Tokopedia"
                    >
                        <div class="marketplace-logo-wrap">
                            <img
                                class="marketplace-logo"
                                src="{{ asset('images/marketplaces/tokopedia.svg') }}"
                                alt="Tokopedia"
                            >
                        </div>
                        <div class="marketplace-copy">
                            <strong>Apotek MD Farma Jakarta</strong>
                            <span>
                                Kunjungi Tokopedia
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
                            </span>
                        </div>
                    </a>

                    <a
                        class="marketplace-card"
                        href="https://shopee.co.id/apotekmdfarma"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka toko Apotek MD Farma di Shopee"
                    >
                        <div class="marketplace-logo-wrap">
                            <img
                                class="marketplace-logo"
                                src="{{ asset('images/marketplaces/shopee.svg') }}"
                                alt="Shopee"
                            >
                        </div>
                        <div class="marketplace-copy">
                            <strong>Apotek MD Farma</strong>
                            <span>
                                Kunjungi Shopee
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
                            </span>
                        </div>
                    </a>

                    <a
                        class="marketplace-card"
                        href="https://store.goapotik.com/penjual/Apotek-MD-Farma"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka toko Apotek MD Farma di GoApotik"
                    >
                        <div class="marketplace-logo-wrap">
                            <img
                                class="marketplace-logo"
                                src="{{ asset('images/marketplaces/goapotik.svg') }}"
                                alt="GoApotik"
                            >
                        </div>
                        <div class="marketplace-copy">
                            <strong>Apotek MD Farma</strong>
                            <span>
                                Kunjungi GoApotik
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
                            </span>
                        </div>
                    </a>

                    <a
                        class="marketplace-card"
                        href="https://blibli.onelink.me/GNtk/aahkq31g"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka toko Apotek MD Farma di Blibli"
                    >
                        <div class="marketplace-logo-wrap">
                            <img
                                class="marketplace-logo"
                                src="{{ asset('images/marketplaces/blibli.svg') }}"
                                alt="Blibli"
                            >
                        </div>
                        <div class="marketplace-copy">
                            <strong>Apotek MD Farma</strong>
                            <span>
                                Kunjungi Blibli
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
                            </span>
                        </div>
                    </a>
                </div>

                <aside class="notice">
                    <span class="notice-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M10.3 3.7 2.6 17a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/>
                            <path d="M12 9v4M12 17h.01"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Bukan layanan kegawatdaruratan</strong>
                        <p>
                            Untuk kondisi darurat, sesak berat, penurunan
                            kesadaran, perdarahan, atau gejala berat lainnya,
                            segera hubungi fasilitas kesehatan atau layanan
                            darurat terdekat.
                        </p>
                    </div>
                </aside>
            </div>
        </section>

        <section
            class="partnership-section"
            id="kerja-sama"
            aria-labelledby="partnership-title"
        >
            <div class="container">
                <div class="partnership-card">
                    <div class="partnership-copy">
                        <span class="section-kicker">Kolaborasi dengan MD Farma</span>
                        <h2 id="partnership-title">
                            Punya kebutuhan atau peluang kerja sama?
                            Mari hubungi kami.
                        </h2>
                        <p>
                            Sampaikan rencana kolaborasi Anda melalui kanal
                            WhatsApp resmi MD Farma. Halaman khusus kerja sama
                            menyediakan kode QR, tombol WhatsApp, dan tautan
                            yang dapat disalin dengan mudah.
                        </p>

                        <div
                            class="partnership-points"
                            aria-label="Keunggulan kanal kerja sama"
                        >
                            <span><i>✓</i>Kanal resmi</span>
                            <span><i>✓</i>Mudah diakses</span>
                            <span><i>✓</i>Pesan awal otomatis</span>
                        </div>

                        <div class="partnership-actions">
                            <a
                                class="button button-primary"
                                href="{{ route('partnership') }}"
                            >
                                Buka Halaman Kerja Sama
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
                        </div>
                    </div>

                    <aside
                        class="partnership-guide"
                        aria-label="Langkah menghubungi MD Farma"
                    >
                        <strong>Tiga langkah sederhana</strong>
                        <div class="partnership-step">
                            <b>1</b>
                            <span>Buka halaman kerja sama.</span>
                        </div>
                        <div class="partnership-step">
                            <b>2</b>
                            <span>Scan QR atau tekan tombol WhatsApp.</span>
                        </div>
                        <div class="partnership-step">
                            <b>3</b>
                            <span>Sampaikan kebutuhan dan kontak Anda.</span>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="container">
                <div class="cta-card">
                    <div class="cta-copy">
                        <h2>Butuh informasi sebelum membeli obat?</h2>
                        <p>
                            Mulai konsultasi dan sampaikan pertanyaan Anda.
                            Pesan akan dijawab mengikuti jam operasional
                            Apotek MD Farma.
                        </p>
                    </div>

                    <a
                        class="button"
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
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-grid container">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}">
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

                <p class="footer-text">
                    Layanan konsultasi farmasi digital dan pembelian obat
                    melalui marketplace Apotek MD Farma.
                </p>
            </div>

            <div>
                <h3>Jam operasional</h3>
                <div class="footer-links">
                    <span class="footer-text">
                        Senin - Jumat<br>
                        08.00 - 20.00 WIB
                    </span>
                    <span class="footer-text">
                        Sabtu - Minggu<br>
                        08.00 - 21.00 WIB
                    </span>
                </div>
            </div>

            <div>
                <h3>Lokasi</h3>
                <p class="footer-address">
                    Jl. Warakas V Gg. 7 No.125 12,
                    RT.12/RW.9, Warakas, Kec. Tj. Priok,
                    Jakarta Utara 14370.
                </p>
                <div class="footer-links" style="margin-top: 12px;">
                    <a href="{{ route('profile') }}">
                        Profil MD Farma
                    </a>
                    <a href="{{ route('partnership') }}">
                        Kerja Sama
                    </a>
                    <a
                        href="https://maps.app.goo.gl/82xaeQfUQYvyrork8"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Buka Google Maps
                    </a>
                    <a href="{{ route('admin.login') }}">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom container">
            <span>
                © {{ date('Y') }} Apotek MD Farma.
            </span>
            <span>
                Informasi pada chat tidak menggantikan pemeriksaan dokter.
            </span>
        </div>
    </footer>

    <a
        class="mobile-cta"
        href="{{ route('consultation.entry') }}"
    >
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
        </svg>
        Mulai Konsultasi
    </a>

    <script>
        (() => {
            const statusElements = document.querySelectorAll(
                '[data-open-status]'
            );

            if (!statusElements.length) {
                return;
            }

            const getJakartaTime = () => {
                const formatter = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'short',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                    hourCycle: 'h23',
                });

                const parts = formatter.formatToParts(new Date());
                const values = Object.fromEntries(
                    parts.map((part) => [part.type, part.value])
                );

                return {
                    weekday: values.weekday,
                    hour: Number(values.hour),
                    minute: Number(values.minute),
                };
            };

            const updateStatus = () => {
                const now = getJakartaTime();
                const weekend = ['Sat', 'Sun'].includes(now.weekday);
                const closeHour = weekend ? 21 : 20;
                const totalMinutes = (now.hour * 60) + now.minute;
                const openMinutes = 8 * 60;
                const closeMinutes = closeHour * 60;
                const isOpen = totalMinutes >= openMinutes
                    && totalMinutes < closeMinutes;

                const text = isOpen
                    ? `Buka sampai ${String(closeHour).padStart(2, '0')}.00 WIB`
                    : 'Tutup, buka pukul 08.00 WIB';

                statusElements.forEach((element) => {
                    element.textContent = text;
                    element.classList.toggle('closed', !isOpen);
                });
            };

            updateStatus();
            window.setInterval(updateStatus, 60000);
        })();
    </script>
</body>
</html>
