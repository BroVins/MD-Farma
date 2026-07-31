<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chat Konsultasi - MD Farma</title>
    @vite('resources/js/app.js')

    <style>
        :root {
            --brand: #198754;
            --brand-dark: #126b43;
            --brand-deep: #0b5033;
            --teal: #18b7a5;
            --teal-dark: #0f8f82;
            --mint-25: #f8fdfb;
            --mint-50: #effaf5;
            --mint-100: #ddf5e9;
            --mint-200: #c6ecda;
            --orange-50: #fff8ed;
            --orange-100: #ffedd2;
            --orange-700: #b64c0d;
            --slate-950: #10221a;
            --slate-800: #24372f;
            --slate-700: #405249;
            --slate-600: #5f7068;
            --slate-500: #7b8982;
            --slate-400: #a3aea9;
            --slate-300: #cdd6d1;
            --slate-200: #e3e9e6;
            --slate-100: #f1f5f3;
            --white: #ffffff;
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --shadow-sm: 0 5px 18px rgba(26, 74, 53, .07);
            --shadow-md: 0 18px 45px rgba(26, 74, 53, .12);
            --shadow-lg: 0 26px 70px rgba(15, 80, 52, .16);
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            background: #eef8f3;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--slate-950);
            background:
                radial-gradient(circle at 8% 8%, rgba(24, 183, 165, .14), transparent 27rem),
                radial-gradient(circle at 92% 92%, rgba(25, 135, 84, .12), transparent 30rem),
                linear-gradient(145deg, #f8fcfa 0%, #edf8f3 100%);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        button,
        textarea,
        input {
            font: inherit;
        }

        button,
        a,
        label {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            color: inherit;
        }

        button:focus-visible,
        a:focus-visible,
        textarea:focus-visible,
        label:focus-visible {
            outline: 3px solid rgba(24, 183, 165, .26);
            outline-offset: 2px;
        }

        .site-bar {
            position: sticky;
            top: 0;
            z-index: 60;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 68px;
            padding: 10px clamp(16px, 4vw, 58px);
            border-bottom: 1px solid rgba(25, 135, 84, .1);
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(18px);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            color: var(--brand-deep);
            font-weight: 850;
            letter-spacing: -.025em;
            text-decoration: none;
        }

        .brand-mark {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            border-radius: 13px;
            color: #fff;
            background: linear-gradient(145deg, var(--brand), var(--teal));
            box-shadow: 0 9px 22px rgba(25, 135, 84, .22);
        }

        .brand-mark svg {
            width: 23px;
            height: 23px;
        }

        .site-actions {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .top-link,
        .top-button {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 13px;
            border: 1px solid var(--slate-200);
            border-radius: 13px;
            color: var(--slate-700);
            background: rgba(255, 255, 255, .86);
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            cursor: pointer;
        }

        .top-link:hover,
        .top-button:hover {
            border-color: var(--mint-200);
            color: var(--brand-dark);
            background: var(--mint-50);
        }

        .top-button {
            border: 0;
        }

        .page {
            width: min(960px, calc(100% - 28px));
            margin: 26px auto 52px;
        }

        .chat-shell {
            overflow: hidden;
            border: 1px solid rgba(25, 135, 84, .13);
            border-radius: 30px;
            background: rgba(255, 255, 255, .93);
            box-shadow: var(--shadow-lg);
        }

        .chat-header {
            position: relative;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 14px;
            min-height: 112px;
            padding: 21px 24px;
            border-bottom: 1px solid rgba(25, 135, 84, .1);
            background:
                radial-gradient(circle at 91% 12%, rgba(24, 183, 165, .17), transparent 13rem),
                linear-gradient(135deg, #ffffff, #effaf5);
        }

        .back-button {
            display: grid;
            width: 45px;
            height: 45px;
            place-items: center;
            flex: 0 0 auto;
            border: 1px solid var(--slate-200);
            border-radius: 15px;
            color: var(--slate-800);
            background: #fff;
            box-shadow: var(--shadow-sm);
            text-decoration: none;
        }

        .back-button svg {
            width: 22px;
            height: 22px;
        }

        .practitioner {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 13px;
        }

        .avatar {
            position: relative;
            display: grid;
            width: 58px;
            height: 58px;
            place-items: center;
            flex: 0 0 auto;
            border: 4px solid rgba(255, 255, 255, .94);
            border-radius: 50%;
            color: #fff;
            background:
                linear-gradient(145deg, rgba(25, 135, 84, .94), rgba(24, 183, 165, .9));
            box-shadow: 0 10px 24px rgba(25, 135, 84, .2);
            font-size: 17px;
            font-weight: 900;
        }

        .avatar::after {
            position: absolute;
            right: -1px;
            bottom: 1px;
            width: 13px;
            height: 13px;
            border: 3px solid #fff;
            border-radius: 50%;
            background: #35c759;
            content: "";
        }

        .practitioner-copy {
            min-width: 0;
        }

        .practitioner-copy h1 {
            overflow: hidden;
            margin: 0;
            color: var(--slate-950);
            font-size: clamp(18px, 2vw, 23px);
            line-height: 1.18;
            letter-spacing: -.035em;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .practitioner-name {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            color: var(--slate-600);
            font-size: 13px;
            font-weight: 650;
        }

        .verified {
            display: inline-grid;
            width: 17px;
            height: 17px;
            place-items: center;
            border-radius: 50%;
            color: #fff;
            background: var(--teal);
            font-size: 10px;
        }

        .response-line {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 7px;
            color: var(--slate-500);
            font-size: 12px;
        }

        .response-line svg {
            width: 16px;
            height: 16px;
            color: var(--brand);
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 10px 13px;
            border: 1px solid rgba(24, 183, 165, .22);
            border-radius: 16px;
            color: var(--slate-800);
            background: rgba(255, 255, 255, .74);
            box-shadow: var(--shadow-sm);
            font-size: 12px;
            font-weight: 800;
        }

        .trust-badge svg {
            width: 24px;
            height: 24px;
            color: var(--teal-dark);
        }

        .notice-wrap {
            padding: 15px 20px 0;
            background: var(--mint-25);
        }

        .notice {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 14px 15px;
            border: 1px solid var(--orange-100);
            border-radius: 18px;
            color: var(--orange-700);
            background: linear-gradient(135deg, #fffaf2, #fff7e9);
        }

        .notice-icon {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            border-radius: 50%;
            color: #f97316;
            background: #ffedd5;
        }

        .notice-icon svg {
            width: 21px;
            height: 21px;
        }

        .notice strong {
            display: block;
            font-size: 13px;
            line-height: 1.45;
        }

        .notice-close {
            display: grid;
            width: 32px;
            height: 32px;
            place-items: center;
            border: 0;
            border-radius: 10px;
            color: var(--orange-700);
            background: transparent;
            cursor: pointer;
        }

        .notice-close:hover {
            background: rgba(249, 115, 22, .09);
        }

        .consultation-meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            padding: 14px 20px 16px;
            background: var(--mint-25);
        }

        .meta-item {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid rgba(25, 135, 84, .11);
            border-radius: 16px;
            background: rgba(255, 255, 255, .8);
        }

        .meta-icon {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 13px;
            color: var(--brand);
            background: var(--mint-100);
        }

        .meta-icon svg {
            width: 20px;
            height: 20px;
        }

        .meta-copy {
            min-width: 0;
        }

        .meta-copy span,
        .meta-copy strong {
            display: block;
        }

        .meta-copy span {
            color: var(--slate-500);
            font-size: 10px;
            font-weight: 750;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .meta-copy strong {
            overflow: hidden;
            margin-top: 3px;
            color: var(--slate-800);
            font-size: 13px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .meta-copy strong.is-active {
            color: var(--brand);
        }

        .connection-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 28px;
            padding: 4px 14px 8px;
            color: var(--slate-500);
            background: var(--mint-25);
            font-size: 10px;
            font-weight: 750;
        }

        .connection-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, .13);
        }

        .connection-status.connected .connection-dot {
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, .13);
        }

        .connection-status.disconnected .connection-dot {
            background: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, .13);
        }

        .feedback-stack {
            padding: 0 20px;
            background: var(--mint-25);
        }

        .feedback {
            margin-bottom: 10px;
            padding: 11px 13px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 650;
        }

        .feedback.error,
        .form-error {
            color: #9f1239;
            background: var(--danger-soft);
        }

        .feedback.success {
            color: var(--brand-deep);
            background: var(--mint-100);
        }

        .form-error {
            display: none;
            margin: 0 0 9px;
        }

        .form-error.visible {
            display: block;
        }

        .chat-box {
            height: min(560px, 62vh);
            overflow-x: hidden;
            overflow-y: auto;
            padding: 22px 20px 16px;
            background:
                linear-gradient(rgba(255, 255, 255, .93), rgba(255, 255, 255, .93)),
                radial-gradient(circle at 15px 15px, rgba(24, 183, 165, .13) 1.2px, transparent 1.3px);
            background-size: auto, 30px 30px;
            scroll-behavior: smooth;
            scrollbar-color: rgba(25, 135, 84, .25) transparent;
            scrollbar-width: thin;
        }

        .date-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 9px 0 20px;
        }

        .date-divider::before,
        .date-divider::after {
            height: 1px;
            flex: 1;
            background: var(--slate-200);
            content: "";
        }

        .date-divider span,
        .date-divider:not(:has(span)) {
            color: var(--slate-600);
            font-size: 10px;
            font-weight: 800;
        }

        .date-divider span {
            margin: 0 11px;
            padding: 6px 11px;
            border: 1px solid var(--slate-200);
            border-radius: 999px;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 4px 12px rgba(15, 35, 26, .04);
        }

        .service-reminder {
            display: flex;
            width: min(100%, 690px);
            align-items: flex-start;
            gap: 11px;
            margin: -7px auto 20px;
            padding: 13px 14px;
            border: 1px solid rgba(24, 183, 165, .2);
            border-radius: 17px;
            color: var(--slate-700);
            background:
                linear-gradient(135deg, rgba(239, 250, 245, .98), rgba(245, 253, 251, .98));
            box-shadow: 0 8px 24px rgba(25, 135, 84, .07);
        }

        .service-reminder-icon {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 12px;
            color: var(--teal-dark);
            background: rgba(24, 183, 165, .12);
        }

        .service-reminder-icon svg {
            width: 19px;
            height: 19px;
        }

        .service-reminder-copy {
            min-width: 0;
        }

        .service-reminder-copy strong {
            display: block;
            margin: 0 0 3px;
            color: var(--brand-deep);
            font-size: 12px;
            font-weight: 850;
        }

        .service-reminder-copy p {
            margin: 0;
            color: var(--slate-600);
            font-size: 11px;
            line-height: 1.55;
        }

        .message-row {
            display: flex;
            align-items: flex-end;
            gap: 9px;
            margin-bottom: 12px;
        }

        .message-row.outgoing {
            justify-content: flex-end;
        }

        .message-avatar {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            flex: 0 0 auto;
            border: 2px solid #fff;
            border-radius: 50%;
            color: #fff;
            background: linear-gradient(145deg, var(--brand), var(--teal));
            box-shadow: 0 5px 14px rgba(25, 135, 84, .18);
            font-size: 10px;
            font-weight: 900;
        }

        .message {
            width: fit-content;
            max-width: min(76%, 620px);
            padding: 11px 13px 8px;
            border: 1px solid var(--slate-200);
            border-radius: 19px 19px 19px 6px;
            color: var(--slate-800);
            background: rgba(255, 255, 255, .97);
            box-shadow: 0 8px 23px rgba(25, 64, 47, .07);
            overflow-wrap: anywhere;
        }

        .message-row.outgoing .message {
            border-color: rgba(24, 183, 165, .13);
            border-radius: 19px 19px 6px 19px;
            background: linear-gradient(135deg, #e9faf5 0%, #d9f5eb 100%);
            box-shadow: 0 8px 23px rgba(24, 183, 165, .09);
        }

        .message-sender {
            display: block;
            margin-bottom: 4px;
            color: var(--brand-dark);
            font-size: 10px;
            font-weight: 850;
        }

        .message-row.outgoing .message-sender {
            display: none;
        }

        .message-text {
            color: inherit;
            font-size: 13px;
            line-height: 1.55;
            white-space: pre-wrap;
        }

        .message-image-link {
            display: block;
            margin-top: 9px;
            overflow: hidden;
            border-radius: 14px;
            background: var(--slate-100);
        }

        .message img {
            display: block;
            width: 100%;
            max-width: 310px;
            max-height: 280px;
            object-fit: cover;
        }

        .message-file-link {
            display: flex;
            min-width: min(280px, 72vw);
            max-width: 340px;
            align-items: center;
            gap: 10px;
            margin-top: 9px;
            padding: 11px 12px;
            border: 1px solid rgba(25, 135, 84, .14);
            border-radius: 14px;
            color: inherit;
            background: rgba(255, 255, 255, .72);
            text-decoration: none;
        }

        .message-file-link:hover {
            border-color: rgba(24, 183, 165, .4);
            background: rgba(255, 255, 255, .95);
        }

        .message-file-icon {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 12px;
            color: var(--brand);
            background: var(--mint-100);
        }

        .message-file-icon svg {
            width: 21px;
            height: 21px;
        }

        .message-file-copy {
            min-width: 0;
            flex: 1;
        }

        .message-file-copy strong,
        .message-file-copy span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .message-file-copy strong {
            font-size: 11px;
        }

        .message-file-copy span {
            margin-top: 3px;
            color: var(--slate-500);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .message-file-download {
            color: var(--teal-dark);
            font-size: 18px;
        }

        .message-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
            margin-top: 6px;
            color: var(--slate-500);
            font-size: 9px;
            font-weight: 650;
        }

        .checks {
            color: var(--teal-dark);
            font-size: 12px;
            letter-spacing: -4px;
            transform: translateX(-2px);
        }

        .empty-chat {
            display: grid;
            min-height: 280px;
            place-items: center;
            text-align: center;
        }

        .empty-chat-card {
            max-width: 340px;
            padding: 22px;
            border: 1px dashed var(--mint-200);
            border-radius: 22px;
            color: var(--slate-600);
            background: rgba(239, 250, 245, .76);
        }

        .empty-chat-icon {
            display: grid;
            width: 50px;
            height: 50px;
            margin: 0 auto 11px;
            place-items: center;
            border-radius: 17px;
            color: var(--brand);
            background: var(--mint-100);
        }

        .empty-chat-icon svg {
            width: 26px;
            height: 26px;
        }

        .empty-chat-card strong {
            display: block;
            margin-bottom: 5px;
            color: var(--slate-800);
            font-size: 14px;
        }

        .empty-chat-card p {
            margin: 0;
            font-size: 12px;
            line-height: 1.55;
        }

        .composer-panel {
            position: relative;
            padding: 13px 18px 17px;
            border-top: 1px solid rgba(25, 135, 84, .1);
            background: rgba(255, 255, 255, .96);
        }

        .quick-actions {
            display: flex;
            gap: 8px;
            padding: 0 1px 11px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .quick-actions::-webkit-scrollbar {
            display: none;
        }

        .quick-chip {
            display: inline-flex;
            min-height: 36px;
            align-items: center;
            gap: 7px;
            flex: 0 0 auto;
            padding: 8px 11px;
            border: 1px solid rgba(25, 135, 84, .13);
            border-radius: 999px;
            color: var(--slate-700);
            background: var(--mint-50);
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .quick-chip:nth-child(2) {
            background: #f3fbef;
        }

        .quick-chip:nth-child(3) {
            background: #eff9fc;
        }

        .quick-chip:nth-child(4) {
            border-color: var(--orange-100);
            color: var(--orange-700);
            background: var(--orange-50);
        }

        .quick-chip svg {
            width: 15px;
            height: 15px;
        }

        .image-preview {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            padding: 9px;
            border: 1px solid var(--mint-200);
            border-radius: 15px;
            background: var(--mint-50);
        }

        .image-preview.visible {
            display: flex;
        }

        .image-preview img {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            object-fit: cover;
        }

        .preview-file-icon {
            display: none;
            width: 54px;
            height: 54px;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 12px;
            color: var(--brand);
            background: var(--mint-100);
        }

        .preview-file-icon svg {
            width: 27px;
            height: 27px;
        }

        .image-preview.is-document img {
            display: none;
        }

        .image-preview.is-document .preview-file-icon {
            display: grid;
        }

        .attachment-menu-wrap {
            position: relative;
            align-self: end;
        }

        .attachment-menu {
            position: absolute;
            bottom: calc(100% + 12px);
            left: 0;
            z-index: 70;
            display: none;
            width: min(255px, calc(100vw - 32px));
            gap: 4px;
            padding: 7px;
            border: 1px solid rgba(25, 135, 84, .14);
            border-radius: 18px;
            background: rgba(255, 255, 255, .98);
            box-shadow: 0 18px 48px rgba(15, 80, 52, .18);
            backdrop-filter: blur(18px);
        }

        .attachment-menu.open {
            display: grid;
            animation: attachment-menu-in .16s ease-out;
        }

        @keyframes attachment-menu-in {
            from {
                opacity: 0;
                transform: translateY(7px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .attachment-option {
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr);
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px;
            border: 0;
            border-radius: 13px;
            color: var(--slate-800);
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .attachment-option:hover {
            background: var(--mint-50);
        }

        .attachment-option-icon {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 13px;
            color: var(--teal-dark);
            background: var(--mint-100);
        }

        .attachment-option:nth-child(2) .attachment-option-icon {
            color: #2563eb;
            background: #eff6ff;
        }

        .attachment-option:nth-child(3) .attachment-option-icon {
            color: #b45309;
            background: var(--orange-50);
        }

        .attachment-option-icon svg {
            width: 21px;
            height: 21px;
        }

        .attachment-option-copy strong,
        .attachment-option-copy span {
            display: block;
        }

        .attachment-option-copy strong {
            font-size: 11px;
        }

        .attachment-option-copy span {
            margin-top: 2px;
            color: var(--slate-500);
            font-size: 9px;
            line-height: 1.35;
        }

        .composer-icon.is-open {
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
            transform: rotate(45deg);
        }

        .preview-copy {
            min-width: 0;
            flex: 1;
        }

        .preview-copy strong,
        .preview-copy span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .preview-copy strong {
            color: var(--slate-800);
            font-size: 11px;
        }

        .preview-copy span {
            margin-top: 3px;
            color: var(--slate-500);
            font-size: 9px;
        }

        .preview-remove {
            display: grid;
            width: 31px;
            height: 31px;
            place-items: center;
            border: 0;
            border-radius: 10px;
            color: var(--danger);
            background: #fff;
            cursor: pointer;
        }

        .composer {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: end;
            gap: 7px;
            padding: 7px;
            border: 1px solid rgba(24, 183, 165, .24);
            border-radius: 23px;
            background: #fff;
            box-shadow: 0 11px 30px rgba(24, 183, 165, .12);
        }

        .composer:focus-within {
            border-color: rgba(24, 183, 165, .65);
            box-shadow: 0 0 0 4px rgba(24, 183, 165, .1),
                0 11px 30px rgba(24, 183, 165, .12);
        }

        .composer-icon,
        .upload-button,
        .send-button {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            flex: 0 0 auto;
            border: 0;
            border-radius: 14px;
            cursor: pointer;
        }

        .composer-icon,
        .upload-button {
            color: var(--teal-dark);
            background: var(--mint-50);
        }

        .composer-icon {
            transition: transform .16s ease, color .16s ease, background .16s ease;
        }

        .composer-icon:hover,
        .upload-button:hover {
            background: var(--mint-100);
        }

        .composer-icon svg,
        .upload-button svg,
        .send-button svg {
            width: 20px;
            height: 20px;
        }

        .message-input {
            width: 100%;
            min-height: 39px;
            max-height: 125px;
            resize: none;
            overflow-y: auto;
            padding: 10px 6px 7px;
            border: 0;
            color: var(--slate-800);
            background: transparent;
            font-size: 13px;
            line-height: 1.45;
            outline: none;
        }

        .message-input::placeholder {
            color: var(--slate-400);
        }

        .send-button {
            color: #fff;
            background: linear-gradient(145deg, var(--teal), var(--brand));
            box-shadow: 0 8px 20px rgba(25, 135, 84, .24);
        }

        .send-button:hover {
            transform: translateY(-1px);
        }

        .send-button:disabled {
            opacity: .5;
            cursor: wait;
            transform: none;
        }

        .composer-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 4px 0;
            color: var(--slate-500);
            font-size: 9px;
        }

        .security-note {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .security-note svg {
            width: 13px;
            height: 13px;
            color: var(--brand);
        }

        .status-finished {
            padding: 18px;
            border-top: 1px solid var(--slate-200);
            color: var(--slate-700);
            background: var(--slate-100);
            text-align: center;
            font-size: 12px;
            font-weight: 700;
        }

        .admin-status-form {
            margin-left: auto;
        }

        .status-action {
            padding: 9px 12px;
            border: 1px solid var(--slate-300);
            border-radius: 12px;
            color: var(--slate-700);
            background: #fff;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 720px) {
            body {
                background: #fff;
            }

            .site-bar {
                display: none;
            }

            .page {
                width: 100%;
                margin: 0;
            }

            .chat-shell {
                min-height: 100dvh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .chat-header {
                grid-template-columns: auto minmax(0, 1fr) auto;
                min-height: 102px;
                padding: max(15px, env(safe-area-inset-top)) 14px 14px;
            }

            .avatar {
                width: 48px;
                height: 48px;
                border-width: 3px;
                font-size: 14px;
            }

            .practitioner {
                gap: 9px;
            }

            .practitioner-copy h1 {
                font-size: 17px;
            }

            .practitioner-name {
                font-size: 11px;
            }

            .response-line {
                margin-top: 5px;
                font-size: 10px;
            }

            .trust-badge {
                width: 43px;
                height: 43px;
                justify-content: center;
                padding: 0;
                border-radius: 14px;
            }

            .trust-badge span {
                display: none;
            }

            .notice-wrap {
                padding: 11px 12px 0;
            }

            .notice {
                padding: 11px;
                border-radius: 16px;
            }

            .notice-icon {
                width: 34px;
                height: 34px;
            }

            .notice strong {
                font-size: 11px;
            }

            .consultation-meta {
                grid-template-columns: repeat(4, minmax(155px, 1fr));
                padding: 11px 12px 13px;
                overflow-x: auto;
                scrollbar-width: none;
            }

            .consultation-meta::-webkit-scrollbar {
                display: none;
            }

            .meta-item {
                padding: 10px;
            }

            .chat-box {
                height: calc(100dvh - 370px);
                min-height: 300px;
                padding: 18px 12px 12px;
            }

            .message {
                max-width: 84%;
                padding: 10px 11px 7px;
            }

            .message-text {
                font-size: 12px;
            }

            .composer-panel {
                position: sticky;
                bottom: 0;
                z-index: 40;
                padding: 10px 10px max(11px, env(safe-area-inset-bottom));
            }

            .quick-actions {
                padding-bottom: 8px;
            }

            .composer {
                grid-template-columns: auto minmax(0, 1fr) auto;
                border-radius: 21px;
            }

            .composer-footer {
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        @media (max-width: 430px) {
            .back-button {
                width: 40px;
                height: 40px;
                border-radius: 13px;
            }

            .chat-header {
                gap: 9px;
                padding-left: 10px;
                padding-right: 10px;
            }

            .practitioner-name {
                max-width: 185px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .notice-close {
                display: none;
            }

            .message {
                max-width: 88%;
            }

            .composer-icon,
            .upload-button,
            .send-button {
                width: 37px;
                height: 37px;
            }
        }
    </style>
</head>
<body>
    @php
        $isAdminView = false;
    @endphp

    <header class="site-bar">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                    <path d="M12 4v16M4 12h16" stroke-linecap="round"/>
                </svg>
            </span>
            <span>MD Farma</span>
        </a>

        <div class="site-actions">
            @if ($isAdminView)
                <a class="top-link" href="{{ route('admin.inbox') }}">
                    Inbox Admin
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="top-button" type="submit">Logout</button>
                </form>
            @else
                <a class="top-link" href="{{ route('home') }}">Beranda</a>
                <a class="top-link" href="{{ route('consultation.create') }}">
                    Konsultasi Baru
                </a>
            @endif
        </div>
    </header>

    @php
        $timezone = config('analytics.timezone', 'Asia/Jakarta');
        $started = $consultation->created_at
            ->copy()
            ->timezone($timezone);
        $startedDateKey = $started->format('Y-m-d');
        $lastDate = $startedDateKey;
        /*
         * Route ini khusus pasien. Panel admin memakai route inbox sendiri,
         * sehingga sesi admin pada browser yang sama tidak boleh mengubah
         * identitas pengirim di halaman pasien.
         */
        $isAdminView = false;
        $patientName = trim((string) ($consultation->nama ?? '')) ?: 'Pasien';
        $consultationLabel = $consultation->jenis_konsultasi === 'resep'
            ? 'Resep Dokter'
            : 'Non Resep';
    @endphp

    <main class="page">
        <section class="chat-shell">
            <header class="chat-header">
                <a
                    class="back-button"
                    href="{{ $isAdminView
                        ? route('admin.inbox')
                        : route('home') }}"
                    aria-label="Kembali"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="practitioner">
                    <div class="avatar" aria-hidden="true">AP</div>
                    <div class="practitioner-copy">
                        <h1>
                            {{ $isAdminView
                                ? 'Apoteker Apotek MD Farma'
                                : 'Chat dengan Apoteker' }}
                        </h1>
                        <div class="practitioner-name">
                            <span>
                                {{ $isAdminView
                                    ? 'Layanan Konsultasi Farmasi'
                                    : 'Apoteker MD Farma' }}
                            </span>
                            <span class="verified" title="Terverifikasi">✓</span>
                        </div>
                        <div class="response-line">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>
                                {{ $consultation->status === 'aktif'
                                    ? 'Konsultasi sedang berlangsung'
                                    : 'Konsultasi telah selesai' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="trust-badge" title="Aman dan terpercaya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3 5 6v5c0 4.8 2.8 8.1 7 10 4.2-1.9 7-5.2 7-10V6l-7-3Z"/>
                        <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Aman &amp;<br>Terpercaya</span>
                </div>
            </header>

            @if (! $isAdminView)
                <div class="notice-wrap" data-notice>
                    <div class="notice">
                        <span class="notice-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                                <path d="M10.3 4.4 2.8 17.3A2 2 0 0 0 4.5 20h15a2 2 0 0 0 1.7-2.7L13.7 4.4a2 2 0 0 0-3.4 0Z"/>
                                <path d="M12 9v4M12 17h.01" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <strong>
                            Jika kondisi memburuk, segera hubungi fasilitas
                            kesehatan terdekat.
                        </strong>
                        <button
                            class="notice-close"
                            type="button"
                            aria-label="Tutup pemberitahuan"
                            data-dismiss-notice
                        >
                            ✕
                        </button>
                    </div>
                </div>
            @endif

            <div class="consultation-meta">
                <div class="meta-item">
                    <span class="meta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4.5 21a7.5 7.5 0 0 1 15 0" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="meta-copy">
                        <span>Nama pasien</span>
                        <strong title="{{ $patientName }}">{{ $patientName }}</strong>
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 21s7-3.5 7-10V5l-7-3-7 3v6c0 6.5 7 10 7 10Z"/>
                            <path d="m9 11 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="meta-copy">
                        <span>Status</span>
                        <strong class="{{ $consultation->status === 'aktif' ? 'is-active' : '' }}">
                            {{ ucfirst($consultation->status) }}
                        </strong>
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3h8M9 3v3h6V3M6 5h12v16H6z"/>
                            <path d="M9 10h6M9 14h6M9 18h4" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="meta-copy">
                        <span>Jenis konsultasi</span>
                        <strong>{{ $consultationLabel }}</strong>
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="meta-copy">
                        <span>Dimulai</span>
                        <strong>{{ $started->format('d M, H.i') }} WIB</strong>
                    </span>
                </div>
            </div>

            <div class="connection-bar">
                <span
                    id="connectionStatus"
                    class="connection-status"
                    aria-live="polite"
                >
                    <span class="connection-dot"></span>
                    <span data-status-text>Menghubungkan realtime...</span>
                </span>

                @if ($isAdminView)
                    <form
                        class="admin-status-form"
                        action="{{ route('admin.chat.status', $consultation) }}"
                        method="POST"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="status"
                            value="{{ $consultation->status === 'aktif'
                                ? 'selesai'
                                : 'aktif' }}"
                        >
                        <button class="status-action" type="submit">
                            {{ $consultation->status === 'aktif'
                                ? 'Tandai Selesai'
                                : 'Aktifkan Kembali' }}
                        </button>
                    </form>
                @endif
            </div>

            <div class="feedback-stack">
                @if (session('success'))
                    <div class="feedback success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="feedback error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="chat-box" id="chatBox" aria-live="polite">
                <div
                    class="date-divider"
                    data-message-date="{{ $startedDateKey }}"
                >
                    <span>
                        {{ $started
                            ->locale('id')
                            ->isToday()
                            ? 'Hari ini'
                            : $started
                                ->locale('id')
                                ->isoFormat('D MMMM YYYY') }}
                    </span>
                </div>

                <aside class="service-reminder" role="note" aria-label="Informasi jam layanan">
                    <span class="service-reminder-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div class="service-reminder-copy">
                        <strong>Informasi jam layanan konsultasi</strong>
                        <p>
                            Konsultasi akan dibalas sesuai jam operasional Apotek MD Farma.
                            Pesan yang dikirim di luar jam operasional tetap kami layani,
                            tetapi waktu respons dapat lebih lama dibandingkan konsultasi
                            pada jam operasional.
                        </p>
                    </div>
                </aside>

                @forelse ($consultation->messages as $chat)
                    @php
                        $local = $chat->created_at
                            ->copy()
                            ->timezone($timezone);
                        $dateKey = $local->format('Y-m-d');
                        $isIncoming = $isAdminView
                            ? $chat->sender === 'user'
                            : $chat->sender === 'admin';
                        $senderLabel = $chat->sender === 'admin'
                            ? 'Apoteker'
                            : 'Pasien';
                    @endphp

                    @if ($lastDate !== $dateKey)
                        <div
                            class="date-divider"
                            data-message-date="{{ $dateKey }}"
                        >
                            <span>
                                {{ $local
                                    ->locale('id')
                                    ->isToday()
                                    ? 'Hari ini'
                                    : $local
                                        ->locale('id')
                                        ->isoFormat('D MMMM YYYY') }}
                            </span>
                        </div>
                        @php
                            $lastDate = $dateKey;
                        @endphp
                    @endif

                    <div
                        class="message-row {{ $isIncoming ? 'incoming' : 'outgoing' }}"
                        data-message-id="{{ $chat->id }}"
                        data-message-date="{{ $dateKey }}"
                    >
                        @if ($isIncoming)
                            <span class="message-avatar" aria-hidden="true">{{ $chat->sender === 'admin' ? 'AP' : 'PS' }}</span>
                        @endif

                        <article class="message">
                            <strong class="message-sender">{{ $senderLabel }}</strong>

                            @if ($chat->message)
                                <div class="message-text">{{ $chat->message }}</div>
                            @endif

                            @if ($chat->image)
                                @php
                                    $attachmentUrl = route(
                                        'chat.attachment',
                                        [
                                            'consultation' => $consultation,
                                            'message' => $chat,
                                        ]
                                    );
                                @endphp

                                @if ($chat->isImageAttachment())
                                    <a
                                        class="message-image-link"
                                        href="{{ $attachmentUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        <img
                                            src="{{ $attachmentUrl }}"
                                            alt="{{ $chat->attachmentName() }}"
                                            loading="lazy"
                                        >
                                    </a>
                                @else
                                    <a
                                        class="message-file-link"
                                        href="{{ $attachmentUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                        download
                                    >
                                        <span class="message-file-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M7 3h7l4 4v14H7z"/>
                                                <path d="M14 3v5h5M10 13h5M10 17h5" stroke-linecap="round"/>
                                            </svg>
                                        </span>
                                        <span class="message-file-copy">
                                            <strong>{{ $chat->attachmentName() }}</strong>
                                            <span>{{ $chat->attachmentExtension() ?: 'dokumen' }}</span>
                                        </span>
                                        <span class="message-file-download" aria-hidden="true">↓</span>
                                    </a>
                                @endif
                            @endif

                            <div class="message-meta">
                                <time
                                    datetime="{{ $chat->created_at->toIso8601String() }}"
                                    title="{{ $local
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY [pukul] HH.mm.ss') }} WIB"
                                >
                                    {{ $local->format('H.i') }}
                                </time>

                                @if (! $isIncoming)
                                    <span class="checks" aria-label="Pesan terkirim">✓✓</span>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="empty-chat" data-empty-chat>
                        <div class="empty-chat-card">
                            <span class="empty-chat-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12a8 8 0 0 1-8 8H7l-4 2 1.3-4A8 8 0 1 1 21 12Z"/>
                                </svg>
                            </span>
                            <strong>Mulai percakapan</strong>
                            <p>
                                Tulis keluhan atau pertanyaan terkait obat.
                                Apoteker akan membalas melalui ruang chat ini.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($consultation->status === 'selesai')
                <div class="status-finished">
                    Konsultasi telah selesai. Pesan baru tidak dapat dikirim.
                </div>
            @else
                <div class="composer-panel">
                    <div class="form-error" data-form-error></div>

                    @if (! $isAdminView)
                        <div class="quick-actions" aria-label="Saran pertanyaan cepat">
                            <button
                                class="quick-chip"
                                type="button"
                                data-suggestion="Bagaimana aturan pakai obat ini?"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 3h10v18H7zM10 7h4M10 11h4M10 15h3" stroke-linecap="round"/>
                                </svg>
                                Aturan Pakai
                            </button>
                            <button
                                class="quick-chip"
                                type="button"
                                data-suggestion="Apa efek samping yang perlu saya perhatikan?"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s7-3.5 7-10V5l-7-3-7 3v6c0 6.5 7 10 7 10Z"/>
                                </svg>
                                Efek Samping
                            </button>
                            <button
                                class="quick-chip"
                                type="button"
                                data-suggestion="Apakah obat ini aman diminum bersama obat lain?"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8.5 4.5a3.5 3.5 0 0 1 5 0l6 6a3.5 3.5 0 0 1-5 5l-6-6a3.5 3.5 0 0 1 0-5Z"/>
                                    <path d="m10 11 4-4"/>
                                </svg>
                                Interaksi Obat
                            </button>
                            <button
                                class="quick-chip"
                                type="button"
                                data-suggestion="Kapan saya perlu memeriksakan diri ke dokter?"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                    <path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round"/>
                                </svg>
                                Kapan ke Dokter
                            </button>
                        </div>
                    @endif

                    <form
                        class="realtime-form"
                        action="{{ $isAdminView
                            ? route('admin.chat.reply', $consultation)
                            : route('chat.send', $consultation) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="image-preview" data-image-preview>
                            <img alt="Pratinjau lampiran" data-preview-image>
                            <span class="preview-file-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 3h7l4 4v14H7z"/>
                                    <path d="M14 3v5h5M10 13h5M10 17h5" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="preview-copy">
                                <strong data-preview-name>Lampiran dipilih</strong>
                                <span data-preview-meta>
                                    Gambar maksimal 5 MB · Dokumen maksimal 10 MB
                                </span>
                            </span>
                            <button
                                class="preview-remove"
                                type="button"
                                aria-label="Hapus lampiran"
                                data-remove-image
                            >
                                ✕
                            </button>
                        </div>

                        <div class="composer">
                            <div class="attachment-menu-wrap">
                                <button
                                    class="composer-icon"
                                    type="button"
                                    aria-label="Tambahkan lampiran"
                                    aria-expanded="false"
                                    aria-controls="attachmentMenu"
                                    data-attachment-toggle
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                    </svg>
                                </button>

                                <div
                                    class="attachment-menu"
                                    id="attachmentMenu"
                                    role="menu"
                                    aria-label="Pilih jenis lampiran"
                                    data-attachment-menu
                                >
                                    <button
                                        class="attachment-option"
                                        type="button"
                                        role="menuitem"
                                        data-trigger-input="chatCamera"
                                    >
                                        <span class="attachment-option-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 7h3l2-3h6l2 3h3v13H4z"/>
                                                <circle cx="12" cy="13" r="4"/>
                                            </svg>
                                        </span>
                                        <span class="attachment-option-copy">
                                            <strong>Ambil gambar</strong>
                                            <span>Buka kamera perangkat</span>
                                        </span>
                                    </button>

                                    <button
                                        class="attachment-option"
                                        type="button"
                                        role="menuitem"
                                        data-trigger-input="chatGallery"
                                    >
                                        <span class="attachment-option-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                                <circle cx="8.5" cy="9" r="1.5"/>
                                                <path d="m21 15-5-5L5 20" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="attachment-option-copy">
                                            <strong>Kirim gambar</strong>
                                            <span>Pilih foto dari galeri atau folder</span>
                                        </span>
                                    </button>

                                    <button
                                        class="attachment-option"
                                        type="button"
                                        role="menuitem"
                                        data-trigger-input="chatDocument"
                                    >
                                        <span class="attachment-option-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M7 3h7l4 4v14H7z"/>
                                                <path d="M14 3v5h5M10 13h5M10 17h5" stroke-linecap="round"/>
                                            </svg>
                                        </span>
                                        <span class="attachment-option-copy">
                                            <strong>Kirim dokumen</strong>
                                            <span>PDF, Word, atau Excel maksimal 10 MB</span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <textarea
                                class="message-input"
                                name="message"
                                rows="1"
                                maxlength="2000"
                                autocomplete="off"
                                placeholder="{{ $isAdminView
                                    ? 'Tulis balasan untuk pasien...'
                                    : 'Tulis pesan di sini...' }}"
                                data-message-input
                            >{{ old('message') }}</textarea>

                            <button
                                class="send-button"
                                type="submit"
                                aria-label="Kirim pesan"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path d="m22 2-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 2 11 13" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <input
                            id="chatCamera"
                            type="file"
                            accept="image/*"
                            capture="environment"
                            hidden
                            data-attachment-input
                            data-attachment-kind="camera"
                        >
                        <input
                            id="chatGallery"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            hidden
                            data-attachment-input
                            data-attachment-kind="image"
                        >
                        <input
                            id="chatDocument"
                            type="file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            hidden
                            data-attachment-input
                            data-attachment-kind="document"
                        >

                        <div class="composer-footer">
                            <span class="security-note">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="5" y="10" width="14" height="11" rx="2"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                </svg>
                                Riwayat tersimpan otomatis di browser ini
                            </span>
                            <span><b data-character-count>0</b>/2000</span>
                        </div>
                    </form>
                </div>
            @endif
        </section>
    </main>

    <script>
        (() => {
            const publicId = @json($consultation->public_id);
            const timezone = @json($timezone);
            const isAdminView = @json($isAdminView);
            const channelName = `consultation.${publicId}`;
            const syncUrl = @json(
                route('chat.messages', $consultation)
            );
            const syncIntervalMs = @json(
                max(
                    2000,
                    (int) config(
                        'consultation.sync_interval_ms',
                        4000
                    )
                )
            );
            const chatBox = document.getElementById('chatBox');
            const status = document.getElementById('connectionStatus');
            const form = document.querySelector('.realtime-form');
            const messageInput = form?.querySelector('[data-message-input]');
            const attachmentInputs = form
                ? Array.from(form.querySelectorAll('[data-attachment-input]'))
                : [];
            const attachmentToggle = form?.querySelector('[data-attachment-toggle]');
            const attachmentMenu = form?.querySelector('[data-attachment-menu]');
            const preview = form?.querySelector('[data-image-preview]');
            const previewImage = form?.querySelector('[data-preview-image]');
            const previewName = form?.querySelector('[data-preview-name]');
            const previewMeta = form?.querySelector('[data-preview-meta]');
            const characterCount = form?.querySelector('[data-character-count]');

            let initialized = false;
            let sessionTimer = null;
            let syncTimer = null;
            let syncInFlight = false;
            let realtimeConnected = false;
            let consultationStatus = @json($consultation->status);
            let previewUrl = null;
            let selectedAttachmentInput = null;

            const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                timeZone: timezone,
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });

            const shortTime = new Intl.DateTimeFormat('id-ID', {
                timeZone: timezone,
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
            });

            const fullTime = new Intl.DateTimeFormat('id-ID', {
                timeZone: timezone,
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            });

            function dateKey(value) {
                const parts = new Intl.DateTimeFormat('en-CA', {
                    timeZone: timezone,
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }).formatToParts(new Date(value));

                const get = type => parts.find(
                    item => item.type === type
                )?.value;

                return `${get('year')}-${get('month')}-${get('day')}`;
            }

            function setStatus(state, text) {
                if (!status) return;

                status.classList.remove('connected', 'disconnected');
                if (state) status.classList.add(state);

                const textNode = status.querySelector('[data-status-text]');
                if (textNode) textNode.textContent = text;
            }

            function scrollBottom(behavior = 'auto') {
                if (!chatBox) return;
                chatBox.scrollTo({
                    top: chatBox.scrollHeight,
                    behavior,
                });
            }

            function isToday(value) {
                return dateKey(value) === dateKey(new Date().toISOString());
            }

            function ensureDateDivider(createdAt) {
                const key = dateKey(createdAt);

                if (
                    chatBox?.querySelector(
                        `.date-divider[data-message-date="${key}"]`
                    )
                ) return;

                const divider = document.createElement('div');
                divider.className = 'date-divider';
                divider.dataset.messageDate = key;

                const label = document.createElement('span');
                label.textContent = isToday(createdAt)
                    ? 'Hari ini'
                    : dateFormatter.format(new Date(createdAt));

                divider.appendChild(label);
                chatBox?.appendChild(divider);
            }

            function createSvgChecks() {
                const checks = document.createElement('span');
                checks.className = 'checks';
                checks.setAttribute('aria-label', 'Pesan terkirim');
                checks.textContent = '✓✓';
                return checks;
            }

            function appendMessage(data) {
                if (!chatBox || !data?.id || !data.created_at) return;

                if (
                    data.consultation_public_id
                    && data.consultation_public_id !== publicId
                ) return;

                if (
                    chatBox.querySelector(
                        `[data-message-id="${data.id}"]`
                    )
                ) return;

                chatBox.querySelector('[data-empty-chat]')?.remove();
                ensureDateDivider(data.created_at);

                const incoming = isAdminView
                    ? data.sender === 'user'
                    : data.sender === 'admin';
                const date = new Date(data.created_at);
                const row = document.createElement('div');
                row.className = `message-row ${incoming ? 'incoming' : 'outgoing'}`;
                row.dataset.messageId = data.id;
                row.dataset.messageDate = dateKey(data.created_at);

                if (incoming) {
                    const avatar = document.createElement('span');
                    avatar.className = 'message-avatar';
                    avatar.setAttribute('aria-hidden', 'true');
                    avatar.textContent = data.sender === 'admin' ? 'AP' : 'PS';
                    row.appendChild(avatar);
                }

                const bubble = document.createElement('article');
                bubble.className = 'message';

                const sender = document.createElement('strong');
                sender.className = 'message-sender';
                sender.textContent = data.sender === 'admin'
                    ? 'Apoteker'
                    : 'Pasien';
                bubble.appendChild(sender);

                if (data.message) {
                    const text = document.createElement('div');
                    text.className = 'message-text';
                    text.textContent = data.message;
                    bubble.appendChild(text);
                }

                const attachmentHref = data.attachment_download_url
                    ?? data.attachment_url;

                if (attachmentHref) {
                    if (data.attachment_type === 'document') {
                        const link = document.createElement('a');
                        link.className = 'message-file-link';
                        link.href = attachmentHref;
                        link.target = '_blank';
                        link.rel = 'noopener';
                        link.download = data.attachment_name ?? '';

                        const icon = document.createElement('span');
                        icon.className = 'message-file-icon';
                        icon.setAttribute('aria-hidden', 'true');
                        icon.textContent = '↧';

                        const copy = document.createElement('span');
                        copy.className = 'message-file-copy';

                        const name = document.createElement('strong');
                        name.textContent = data.attachment_name
                            ?? 'Lampiran dokumen';

                        const type = document.createElement('span');
                        type.textContent = data.attachment_extension
                            ?? 'dokumen';

                        copy.append(name, type);

                        const download = document.createElement('span');
                        download.className = 'message-file-download';
                        download.setAttribute('aria-hidden', 'true');
                        download.textContent = '↓';

                        link.append(icon, copy, download);
                        bubble.appendChild(link);
                    } else {
                        const link = document.createElement('a');
                        link.className = 'message-image-link';
                        link.href = attachmentHref;
                        link.target = '_blank';
                        link.rel = 'noopener';

                        const image = document.createElement('img');
                        image.src = data.attachment_url ?? attachmentHref;
                        image.alt = data.attachment_name ?? 'Lampiran chat';
                        image.loading = 'lazy';
                        link.appendChild(image);
                        bubble.appendChild(link);
                    }
                }

                const meta = document.createElement('div');
                meta.className = 'message-meta';

                const time = document.createElement('time');
                time.dateTime = data.created_at;
                time.textContent = shortTime.format(date).replace('.', ':');
                time.title = `${fullTime.format(date)} WIB`;
                meta.appendChild(time);

                if (!incoming) meta.appendChild(createSvgChecks());

                bubble.appendChild(meta);
                row.appendChild(bubble);
                chatBox.appendChild(row);
                scrollBottom('smooth');
            }

            function latestMessageId() {
                if (!chatBox) return 0;

                return Array.from(
                    chatBox.querySelectorAll('[data-message-id]')
                ).reduce(
                    (latest, element) => Math.max(
                        latest,
                        Number(element.dataset.messageId) || 0
                    ),
                    0
                );
            }

            async function syncMessages() {
                if (syncInFlight) return;
                syncInFlight = true;

                const url = new URL(
                    syncUrl,
                    window.location.origin
                );
                url.searchParams.set(
                    'after_id',
                    String(latestMessageId())
                );

                try {
                    const response = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(
                            'Riwayat chat belum dapat disinkronkan.'
                        );
                    }

                    const result = await response.json();

                    if (
                        result.consultation_status
                        && consultationStatus !== result.consultation_status
                    ) {
                        consultationStatus = result.consultation_status;
                        window.location.reload();
                        return;
                    }

                    (result.messages ?? []).forEach(appendMessage);
                    scheduleExpiry(result.access_expires_at);

                    if (!realtimeConnected) {
                        setStatus(
                            'connected',
                            'Sinkronisasi cadangan aktif'
                        );
                    }
                } catch (error) {
                    if (!realtimeConnected) {
                        setStatus(
                            'disconnected',
                            navigator.onLine
                                ? 'Sinkronisasi tertunda'
                                : 'Perangkat sedang offline'
                        );
                    }
                } finally {
                    syncInFlight = false;
                }
            }

            function startMessageSync() {
                window.clearInterval(syncTimer);
                syncMessages();
                syncTimer = window.setInterval(
                    syncMessages,
                    syncIntervalMs
                );
            }

            function showError(text) {
                const box = document.querySelector('[data-form-error]');
                if (!box) return;
                box.textContent = text;
                box.classList.add('visible');
            }

            function clearError() {
                const box = document.querySelector('[data-form-error]');
                if (!box) return;
                box.textContent = '';
                box.classList.remove('visible');
            }

            function updateTextarea() {
                if (!messageInput) return;
                messageInput.style.height = 'auto';
                messageInput.style.height = `${Math.min(
                    messageInput.scrollHeight,
                    125
                )}px`;

                if (characterCount) {
                    characterCount.textContent = messageInput.value.length;
                }
            }

            function closeAttachmentMenu() {
                attachmentMenu?.classList.remove('open');
                attachmentToggle?.classList.remove('is-open');
                attachmentToggle?.setAttribute('aria-expanded', 'false');
            }

            function clearPreview() {
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = null;
                selectedAttachmentInput = null;

                attachmentInputs.forEach(input => {
                    input.value = '';
                    input.removeAttribute('name');
                });

                if (previewImage) previewImage.removeAttribute('src');
                if (previewName) previewName.textContent = 'Lampiran dipilih';
                if (previewMeta) {
                    previewMeta.textContent =
                        'Gambar maksimal 5 MB · Dokumen maksimal 10 MB';
                }

                preview?.classList.remove('visible', 'is-document');
                closeAttachmentMenu();
            }

            function fileExtension(fileName) {
                const value = String(fileName ?? '');
                const dot = value.lastIndexOf('.');
                return dot >= 0
                    ? value.slice(dot + 1).toLowerCase()
                    : '';
            }

            function validateAttachment(file, kind) {
                const imageTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ];
                const documentExtensions = [
                    'pdf',
                    'doc',
                    'docx',
                    'xls',
                    'xlsx',
                ];

                const isImage = imageTypes.includes(file.type)
                    || ['jpg', 'jpeg', 'png', 'webp']
                        .includes(fileExtension(file.name));

                if (kind === 'document') {
                    if (!documentExtensions.includes(fileExtension(file.name))) {
                        return 'Dokumen harus berformat PDF, Word, atau Excel.';
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        return 'Ukuran dokumen maksimal 10 MB.';
                    }

                    return null;
                }

                if (!isImage) {
                    return 'Gambar harus berformat JPG, PNG, atau WebP.';
                }

                if (file.size > 5 * 1024 * 1024) {
                    return 'Ukuran gambar maksimal 5 MB.';
                }

                return null;
            }

            function showAttachmentPreview(input) {
                const file = input.files?.[0];
                if (!file) {
                    clearPreview();
                    return;
                }

                const kind = input.dataset.attachmentKind ?? 'image';
                const error = validateAttachment(file, kind);

                if (error) {
                    clearPreview();
                    showError(error);
                    return;
                }

                clearError();

                attachmentInputs.forEach(item => {
                    if (item !== input) {
                        item.value = '';
                        item.removeAttribute('name');
                    }
                });

                selectedAttachmentInput = input;
                input.setAttribute('name', 'image');

                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = null;

                const isDocument = kind === 'document';
                preview?.classList.toggle('is-document', isDocument);

                if (!isDocument) {
                    previewUrl = URL.createObjectURL(file);
                    if (previewImage) previewImage.src = previewUrl;
                } else if (previewImage) {
                    previewImage.removeAttribute('src');
                }

                if (previewName) previewName.textContent = file.name;
                if (previewMeta) {
                    previewMeta.textContent = isDocument
                        ? `${fileExtension(file.name).toUpperCase()} · maksimal 10 MB`
                        : 'Gambar · maksimal 5 MB';
                }

                preview?.classList.add('visible');
                closeAttachmentMenu();
            }

            function expireSession() {
                window.Echo?.leave(channelName);
                setStatus('disconnected', 'Sesi pasien telah berakhir');

                document.querySelectorAll(
                    '.realtime-form input, .realtime-form textarea, .realtime-form button'
                ).forEach(element => {
                    element.disabled = true;
                });
            }

            function scheduleExpiry(expiresAt) {
                if (!expiresAt || isAdminView) return;

                clearTimeout(sessionTimer);
                const delay = new Date(expiresAt).getTime() - Date.now();

                if (delay <= 0) {
                    expireSession();
                    return;
                }

                sessionTimer = setTimeout(expireSession, delay);
            }

            document.querySelector('[data-dismiss-notice]')
                ?.addEventListener('click', event => {
                    event.currentTarget.closest('[data-notice]')?.remove();
                });

            document.querySelectorAll('[data-suggestion]')
                .forEach(button => {
                    button.addEventListener('click', () => {
                        if (!messageInput) return;
                        messageInput.value = button.dataset.suggestion ?? '';
                        updateTextarea();
                        messageInput.focus();
                    });
                });

            attachmentToggle?.addEventListener('click', event => {
                event.stopPropagation();

                const willOpen = !attachmentMenu?.classList.contains('open');
                attachmentMenu?.classList.toggle('open', willOpen);
                attachmentToggle.classList.toggle('is-open', willOpen);
                attachmentToggle.setAttribute(
                    'aria-expanded',
                    willOpen ? 'true' : 'false'
                );
            });

            form?.querySelectorAll('[data-trigger-input]')
                .forEach(button => {
                    button.addEventListener('click', () => {
                        const target = document.getElementById(
                            button.dataset.triggerInput
                        );
                        closeAttachmentMenu();
                        target?.click();
                    });
                });

            attachmentInputs.forEach(input => {
                input.addEventListener(
                    'change',
                    () => showAttachmentPreview(input)
                );
            });

            document.addEventListener('click', event => {
                if (
                    attachmentMenu?.classList.contains('open')
                    && !event.target.closest('.attachment-menu-wrap')
                ) {
                    closeAttachmentMenu();
                }
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') closeAttachmentMenu();
            });

            messageInput?.addEventListener('input', updateTextarea);
            messageInput?.addEventListener('keydown', event => {
                if (
                    event.key === 'Enter'
                    && !event.shiftKey
                    && !event.isComposing
                ) {
                    event.preventDefault();
                    form?.requestSubmit();
                }
            });

            document.querySelector('[data-remove-image]')
                ?.addEventListener('click', clearPreview);

            form?.addEventListener('submit', async event => {
                event.preventDefault();
                clearError();

                const hasMessage = messageInput?.value.trim().length > 0;
                const hasAttachment = attachmentInputs.some(
                    input => Boolean(input.files?.length)
                );

                if (!hasMessage && !hasAttachment) {
                    showError('Tulis pesan atau pilih lampiran terlebih dahulu.');
                    messageInput?.focus();
                    return;
                }

                const button = form.querySelector('button[type="submit"]');
                if (button) button.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const result = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        const errors = result.errors
                            ? Object.values(result.errors).flat()
                            : [];

                        throw new Error(
                            errors[0]
                            ?? result.message
                            ?? 'Pesan gagal dikirim.'
                        );
                    }

                    appendMessage(result.message);
                    scheduleExpiry(result.access_expires_at);
                    form.reset();
                    clearPreview();
                    updateTextarea();

                    if (!result.realtime_delivered) {
                        setStatus(
                            'disconnected',
                            'Pesan tersimpan; realtime offline'
                        );
                    }
                } catch (error) {
                    showError(error.message);
                } finally {
                    if (button) button.disabled = false;
                }
            });

            function initializeRealtime() {
                if (initialized || !window.Echo) return;
                initialized = true;

                window.Echo
                    .private(channelName)
                    .listen('.message.sent', appendMessage);

                const connection = window.Echo
                    .connector
                    ?.pusher
                    ?.connection;

                connection?.bind('connected', () => {
                    realtimeConnected = true;
                    setStatus('connected', 'Realtime terhubung');
                    syncMessages();
                });

                connection?.bind('disconnected', () => {
                    realtimeConnected = false;
                    setStatus('disconnected', 'Realtime terputus');
                    syncMessages();
                });

                connection?.bind('unavailable', () => {
                    realtimeConnected = false;
                    setStatus(
                        'disconnected',
                        'Server realtime tidak tersedia'
                    );
                    syncMessages();
                });

                connection?.bind('error', () => {
                    realtimeConnected = false;
                    setStatus(
                        'disconnected',
                        'Koneksi realtime bermasalah'
                    );
                });
            }

            updateTextarea();
            scrollBottom();
            startMessageSync();

            @if (! $isAdminView)
                scheduleExpiry(
                    @json(
                        auth('patient')
                            ->user()
                            ?->expires_at
                            ?->toIso8601String()
                    )
                );
            @endif

            if (window.Echo) {
                initializeRealtime();
            } else {
                window.addEventListener(
                    'md-farma:echo-ready',
                    initializeRealtime,
                    { once: true }
                );
            }

            window.addEventListener('online', syncMessages);

            window.addEventListener('offline', () => {
                realtimeConnected = false;
                setStatus(
                    'disconnected',
                    'Perangkat sedang offline'
                );
            });

            window.addEventListener('focus', syncMessages);

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) syncMessages();
            });

            window.addEventListener('beforeunload', () => {
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                window.clearInterval(syncTimer);
                window.Echo?.leave(channelName);
            });
        })();
    </script>
</body>
</html>
