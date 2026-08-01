<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Dashboard Analitik MD Farma</title>

    @vite('resources/js/app.js')

    <style>
        :root {
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-500:#10b981;
            --green-200:#a7f3d0;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --amber-100:#fef3c7;
            --amber-800:#92400e;
            --red-100:#fee2e2;
            --red-800:#991b1b;
            --slate-950:#0f172a;
            --slate-800:#1e293b;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --slate-50:#f8fafc;
            --white:#fff;
            --shadow:0 16px 42px rgba(15,23,42,.08);
        }

        * { box-sizing:border-box; }

        body {
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,
                -apple-system,BlinkMacSystemFont,
                "Segoe UI",sans-serif;
            color:var(--slate-950);
            background:var(--slate-50);
        }

        button,input,select { font:inherit; }

        .topbar {
            position:sticky;
            top:0;
            z-index:40;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            padding:13px clamp(18px,4vw,56px);
            background:rgba(6,78,59,.97);
            color:#fff;
            box-shadow:0 8px 24px rgba(6,78,59,.16);
            backdrop-filter:blur(12px);
        }

        .brand {
            display:flex;
            align-items:center;
            gap:10px;
            color:#fff;
            text-decoration:none;
            font-weight:850;
        }

        .brand-mark {
            display:grid;
            place-items:center;
            width:38px;
            height:38px;
            border:1px solid rgba(255,255,255,.22);
            border-radius:12px;
            background:rgba(255,255,255,.14);
        }

        .admin-actions {
            display:flex;
            align-items:center;
            gap:10px;
        }

        .admin-nav {
            display:flex;
            align-items:center;
            gap:6px;
            margin-right:auto;
        }

        .admin-nav-link {
            display:flex;
            align-items:center;
            gap:6px;
            min-height:36px;
            padding:7px 11px;
            border-radius:10px;
            color:rgba(255,255,255,.74);
            text-decoration:none;
            font-size:11px;
            font-weight:800;
        }

        .admin-nav-link:hover,
        .admin-nav-link.active {
            color:#fff;
            background:rgba(255,255,255,.14);
        }

        .live-pill,
        .notification-button,
        .logout {
            min-height:36px;
            border:1px solid rgba(255,255,255,.22);
            border-radius:10px;
            color:#fff;
            background:rgba(255,255,255,.10);
        }

        .live-pill {
            display:flex;
            align-items:center;
            gap:7px;
            padding:7px 10px;
            font-size:11px;
            font-weight:750;
        }

        .live-dot {
            width:8px;
            height:8px;
            border-radius:50%;
            background:#f59e0b;
        }

        .live-pill.connected .live-dot {
            background:#4ade80;
        }

        .live-pill.disconnected .live-dot {
            background:#f87171;
        }

        .notification-button {
            position:relative;
            padding:7px 11px;
            cursor:pointer;
        }

        .notification-badge {
            position:absolute;
            top:-7px;
            right:-7px;
            display:none;
            min-width:19px;
            height:19px;
            padding:0 5px;
            border:2px solid var(--green-900);
            border-radius:999px;
            background:#ef4444;
            color:#fff;
            font-size:10px;
            font-weight:850;
            line-height:15px;
        }

        .notification-badge.visible {
            display:grid;
            place-items:center;
        }

        .identity {
            display:grid;
            text-align:right;
        }

        .identity strong { font-size:12px; }
        .identity small {
            color:rgba(255,255,255,.72);
            font-size:10px;
        }

        .logout {
            padding:7px 11px;
            cursor:pointer;
        }

        .page {
            width:min(1440px,94%);
            margin:0 auto;
            padding:26px 0 50px;
        }

        .hero {
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:18px;
            margin-bottom:18px;
        }

        .eyebrow {
            margin:0 0 5px;
            color:var(--green-700);
            font-size:11px;
            font-weight:850;
            letter-spacing:.09em;
            text-transform:uppercase;
        }

        .hero h1 {
            margin:0 0 7px;
            font-size:clamp(27px,4vw,42px);
            line-height:1;
            letter-spacing:-.045em;
        }

        .hero p {
            margin:0;
            color:var(--slate-500);
        }

        .period-label {
            padding:9px 12px;
            border:1px solid var(--green-100);
            border-radius:999px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:11px;
            font-weight:800;
            white-space:nowrap;
        }

        .notice {
            margin-bottom:16px;
            padding:12px 14px;
            border:1px solid var(--green-200);
            border-radius:12px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:12px;
        }

        .panel {
            border:1px solid var(--slate-200);
            border-radius:17px;
            background:#fff;
            box-shadow:var(--shadow);
        }

        .filter-panel {
            display:grid;
            grid-template-columns:auto minmax(420px,1fr);
            gap:18px;
            align-items:end;
            margin-bottom:17px;
            padding:15px 17px;
        }

        .period-tabs {
            display:flex;
            flex-wrap:wrap;
            gap:7px;
        }

        .period-tab {
            padding:9px 11px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            color:var(--slate-700);
            text-decoration:none;
            font-size:11px;
            font-weight:800;
        }

        .period-tab.active {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .custom-period {
            display:grid;
            grid-template-columns:1fr 1fr auto;
            gap:9px;
            align-items:end;
        }

        .field {
            display:grid;
            gap:5px;
        }

        .field label {
            color:var(--slate-500);
            font-size:10px;
            font-weight:750;
        }

        input,select {
            min-height:39px;
            padding:8px 10px;
            border:1px solid var(--slate-300);
            border-radius:9px;
            background:#fff;
            color:var(--slate-800);
        }

        .button {
            min-height:39px;
            padding:8px 13px;
            border:0;
            border-radius:9px;
            background:var(--green-700);
            color:#fff;
            cursor:pointer;
            font-weight:800;
        }

        .kpi-grid {
            display:grid;
            grid-template-columns:repeat(6,minmax(0,1fr));
            gap:11px;
            margin-bottom:17px;
        }

        .kpi {
            position:relative;
            overflow:hidden;
            min-height:126px;
            padding:15px;
            border:1px solid var(--slate-200);
            border-radius:15px;
            background:#fff;
        }

        .kpi::after {
            content:"";
            position:absolute;
            right:-22px;
            bottom:-30px;
            width:92px;
            height:92px;
            border-radius:50%;
            background:var(--green-50);
        }

        .kpi span,.kpi strong,.kpi small {
            position:relative;
            z-index:1;
            display:block;
        }

        .kpi span {
            margin-bottom:12px;
            color:var(--slate-600);
            font-size:10px;
            font-weight:850;
            letter-spacing:.03em;
            text-transform:uppercase;
        }

        .kpi strong {
            margin-bottom:7px;
            font-size:clamp(25px,3vw,34px);
            line-height:1;
            letter-spacing:-.04em;
        }

        .kpi small {
            color:var(--slate-500);
            font-size:10px;
            line-height:1.4;
        }

        .grid-2 {
            display:grid;
            grid-template-columns:minmax(0,1.65fr)
                minmax(285px,.85fr);
            gap:17px;
            margin-bottom:17px;
        }

        .panel-header {
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            padding:18px 19px 0;
        }

        .panel-header h2 {
            margin:0 0 4px;
            font-size:17px;
        }

        .panel-header p {
            margin:0;
            color:var(--slate-500);
            font-size:11px;
        }

        .chart-wrap {
            height:285px;
            padding:12px 17px 18px;
        }

        #trendChart {
            width:100%;
            height:100%;
        }

        .donut-area {
            display:grid;
            place-items:center;
            padding:21px 17px 18px;
        }

        .donut {
            --share:0%;
            position:relative;
            display:grid;
            place-items:center;
            width:174px;
            aspect-ratio:1;
            border-radius:50%;
            background:conic-gradient(
                var(--green-600) 0 var(--share),
                var(--slate-300) var(--share) 100%
            );
        }

        .donut::after {
            content:"";
            position:absolute;
            width:65%;
            height:65%;
            border-radius:50%;
            background:#fff;
        }

        .donut-value {
            position:relative;
            z-index:1;
            text-align:center;
        }

        .donut-value strong {
            display:block;
            font-size:29px;
        }

        .donut-value small {
            color:var(--slate-500);
            font-size:10px;
        }

        .legend {
            display:grid;
            gap:7px;
            width:100%;
            margin-top:15px;
        }

        .legend-row {
            display:flex;
            justify-content:space-between;
            padding:9px 10px;
            border-radius:9px;
            background:var(--slate-100);
            font-size:11px;
        }

        .legend-name {
            display:flex;
            align-items:center;
            gap:7px;
        }

        .dot {
            width:9px;
            height:9px;
            border-radius:50%;
            background:var(--green-600);
        }

        .dot.gray { background:var(--slate-300); }

        .activity-panel {
            margin-bottom:17px;
            overflow:hidden;
        }

        .activity-toolbar {
            display:flex;
            align-items:center;
            gap:8px;
        }

        .sync-label {
            color:var(--slate-500);
            font-size:10px;
        }

        .activity-tabs {
            display:flex;
            gap:5px;
            padding:12px 18px 0;
        }

        .activity-tab {
            padding:8px 11px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            background:#fff;
            color:var(--slate-600);
            cursor:pointer;
            font-size:11px;
            font-weight:800;
        }

        .activity-tab.active {
            border-color:var(--green-700);
            background:var(--green-50);
            color:var(--green-800);
        }

        .insight-strip {
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:8px;
            padding:13px 18px 0;
        }

        .insight {
            padding:11px 12px;
            border:1px solid var(--slate-200);
            border-radius:11px;
            background:linear-gradient(
                145deg,
                #fff,
                var(--green-50)
            );
        }

        .insight span,
        .insight strong,
        .insight small {
            display:block;
        }

        .insight span {
            color:var(--slate-500);
            font-size:9px;
            font-weight:850;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        .insight strong {
            margin:6px 0 3px;
            font-size:14px;
        }

        .insight small {
            color:var(--slate-500);
            font-size:9px;
        }

        .activity-view {
            display:none;
            padding:15px 18px 18px;
        }

        .activity-view.active { display:block; }

        .hour-layout {
            display:grid;
            grid-template-columns:minmax(280px,.85fr)
                minmax(360px,1.15fr);
            gap:16px;
        }

        .period-buckets {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:8px;
        }

        .period-bucket {
            padding:13px;
            border:1px solid var(--slate-200);
            border-radius:12px;
            background:var(--slate-50);
        }

        .period-bucket span,
        .period-bucket strong,
        .period-bucket small {
            display:block;
        }

        .period-bucket span {
            color:var(--slate-500);
            font-size:10px;
        }

        .period-bucket strong {
            margin:5px 0;
            font-size:22px;
        }

        .period-bucket small {
            color:var(--slate-500);
            font-size:9px;
        }

        .top-hours {
            display:grid;
            align-content:start;
            gap:9px;
        }

        .section-label {
            margin:0 0 2px;
            color:var(--slate-600);
            font-size:11px;
            font-weight:850;
        }

        .hour-rank {
            display:grid;
            grid-template-columns:110px 1fr 40px;
            gap:9px;
            align-items:center;
            font-size:10px;
        }

        .hour-track {
            height:10px;
            overflow:hidden;
            border-radius:999px;
            background:var(--slate-100);
        }

        .hour-fill {
            height:100%;
            min-width:2px;
            border-radius:999px;
            background:linear-gradient(
                90deg,
                var(--green-500),
                var(--green-800)
            );
        }

        .no-data {
            padding:22px;
            border:1px dashed var(--slate-300);
            border-radius:12px;
            color:var(--slate-500);
            text-align:center;
            font-size:11px;
        }

        .calendar-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            margin-bottom:10px;
        }

        .calendar-title strong,
        .calendar-title small {
            display:block;
        }

        .calendar-title small {
            margin-top:3px;
            color:var(--slate-500);
            font-size:10px;
        }

        .calendar-nav {
            display:flex;
            gap:6px;
        }

        .calendar-nav a {
            display:grid;
            place-items:center;
            width:32px;
            height:32px;
            border:1px solid var(--slate-200);
            border-radius:8px;
            color:var(--slate-700);
            text-decoration:none;
        }

        .calendar {
            display:grid;
            grid-template-columns:repeat(7,minmax(0,1fr));
            gap:5px;
        }

        .weekday {
            padding:5px;
            color:var(--slate-500);
            font-size:9px;
            font-weight:850;
            text-align:center;
            text-transform:uppercase;
        }

        .calendar-blank,
        .calendar-day {
            min-height:49px;
            border-radius:8px;
        }

        .calendar-day {
            position:relative;
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            padding:7px;
            border:1px solid var(--slate-200);
            background:#fff;
            color:var(--slate-950);
            cursor:pointer;
            text-align:left;
        }

        .calendar-day i {
            display:grid;
            place-items:center;
            min-width:18px;
            height:18px;
            padding:0 4px;
            border-radius:999px;
            background:rgba(255,255,255,.7);
            color:inherit;
            font-size:8px;
            font-style:normal;
            font-weight:850;
        }

        .calendar-day.i1 { background:#ecfdf5; }
        .calendar-day.i2 { background:#a7f3d0; }
        .calendar-day.i3 { background:#34d399; }
        .calendar-day.i4 {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .calendar-day.today {
            outline:2px solid rgba(245,158,11,.55);
        }

        .calendar-legend {
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-top:9px;
            color:var(--slate-500);
            font-size:9px;
        }

        .calendar-legend span {
            display:flex;
            align-items:center;
            gap:5px;
        }

        .heat {
            width:9px;
            height:9px;
            border-radius:3px;
            background:var(--slate-100);
        }

        .h1 { background:#ecfdf5; }
        .h2 { background:#a7f3d0; }
        .h3 { background:#34d399; }
        .h4 { background:var(--green-700); }

        .table-panel { overflow:hidden; }

        .table-filter {
            display:grid;
            grid-template-columns:minmax(210px,1.5fr)
                repeat(3,minmax(125px,.7fr)) auto;
            gap:9px;
            padding:14px 17px;
            border-top:1px solid var(--slate-200);
            border-bottom:1px solid var(--slate-200);
            background:var(--slate-100);
        }

        .table-wrap { overflow-x:auto; }

        table {
            width:100%;
            min-width:1030px;
            border-collapse:collapse;
        }

        th,td {
            padding:13px 14px;
            border-bottom:1px solid var(--slate-200);
            text-align:left;
            vertical-align:middle;
        }

        th {
            background:#fbfdff;
            color:var(--slate-500);
            font-size:9px;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        td { font-size:11px; }

        tr.flash-row {
            animation:flashRow 2.6s ease;
        }

        @keyframes flashRow {
            0%,35% { background:#dcfce7; }
            100% { background:transparent; }
        }

        .patient-name { font-weight:850; }

        .sub {
            display:block;
            margin-top:3px;
            color:var(--slate-500);
            font-size:9px;
        }

        .badge {
            display:inline-flex;
            padding:5px 8px;
            border-radius:999px;
            background:var(--green-50);
            color:var(--green-800);
            font-size:9px;
            font-weight:850;
        }

        .badge.gray {
            background:var(--slate-100);
            color:var(--slate-700);
        }

        .badge.amber {
            background:var(--amber-100);
            color:var(--amber-800);
        }

        .chat-link {
            color:var(--green-700);
            font-weight:850;
            text-decoration:none;
        }

        .empty {
            padding:32px;
            color:var(--slate-500);
            text-align:center;
        }

        .pagination { padding:15px 17px; }

        .pager {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .pager-links {
            display:flex;
            align-items:center;
            gap:6px;
        }

        .pager a,.pager span {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:32px;
            min-height:32px;
            padding:6px 8px;
            border:1px solid var(--slate-200);
            border-radius:8px;
            color:var(--slate-700);
            text-decoration:none;
            font-size:10px;
        }

        .pager span.current {
            border-color:var(--green-700);
            background:var(--green-700);
            color:#fff;
        }

        .pager span.disabled {
            color:var(--slate-300);
            background:var(--slate-100);
        }

        .toast-stack {
            position:fixed;
            top:76px;
            right:18px;
            z-index:120;
            display:grid;
            gap:9px;
            width:min(360px,calc(100vw - 36px));
        }

        .toast {
            display:grid;
            grid-template-columns:1fr auto;
            gap:10px;
            padding:13px 14px;
            border:1px solid var(--green-200);
            border-radius:13px;
            background:#fff;
            box-shadow:0 18px 48px rgba(15,23,42,.18);
            animation:toastIn .2s ease;
        }

        .toast strong,
        .toast span {
            display:block;
        }

        .toast strong {
            margin-bottom:4px;
            font-size:12px;
        }

        .toast span {
            color:var(--slate-600);
            font-size:10px;
            line-height:1.45;
        }

        .toast a {
            color:var(--green-700);
            font-size:10px;
            font-weight:850;
            text-decoration:none;
        }

        .toast-close {
            width:28px;
            height:28px;
            border:0;
            border-radius:8px;
            background:var(--slate-100);
            cursor:pointer;
        }

        @keyframes toastIn {
            from {
                opacity:0;
                transform:translateY(-8px);
            }
        }

        .modal-bg {
            position:fixed;
            inset:0;
            z-index:130;
            display:none;
            place-items:center;
            padding:20px;
            background:rgba(15,23,42,.58);
        }

        .modal-bg.open { display:grid; }

        .modal {
            width:min(440px,100%);
            overflow:hidden;
            border-radius:18px;
            background:#fff;
            box-shadow:0 28px 80px rgba(15,23,42,.25);
        }

        .modal-head {
            display:flex;
            justify-content:space-between;
            gap:12px;
            padding:18px;
            border-bottom:1px solid var(--slate-200);
        }

        .modal-head h3 { margin:0 0 4px; }

        .modal-head p {
            margin:0;
            color:var(--slate-500);
            font-size:11px;
        }

        .modal-close {
            width:33px;
            height:33px;
            border:0;
            border-radius:8px;
            background:var(--slate-100);
            cursor:pointer;
        }

        .modal-grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:9px;
            padding:18px;
        }

        .modal-stat {
            padding:12px;
            border-radius:11px;
            background:var(--slate-100);
        }

        .modal-stat span,
        .modal-stat strong {
            display:block;
        }

        .modal-stat span {
            margin-bottom:5px;
            color:var(--slate-500);
            font-size:9px;
        }

        @media (max-width:1180px) {
            .kpi-grid {
                grid-template-columns:repeat(3,minmax(0,1fr));
            }

            .filter-panel {
                grid-template-columns:1fr;
            }
        }

        @media (max-width:920px) {
            .grid-2,.hour-layout {
                grid-template-columns:1fr;
            }

            .insight-strip {
                grid-template-columns:repeat(2,minmax(0,1fr));
            }

            .table-filter {
                grid-template-columns:repeat(2,minmax(0,1fr));
            }
        }

        @media (max-width:680px) {
            .topbar,.hero {
                align-items:flex-start;
                flex-direction:column;
            }

            .admin-actions {
                width:100%;
                flex-wrap:wrap;
            }

            .identity {
                margin-left:auto;
                text-align:right;
            }

            .custom-period,
            .kpi-grid,
            .table-filter {
                grid-template-columns:1fr;
            }

            .period-buckets {
                grid-template-columns:1fr 1fr;
            }

            .calendar-day {
                min-height:42px;
                padding:5px;
                font-size:10px;
            }

            .live-pill span:last-child {
                display:none;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.inbox') }}">
            <span class="brand-mark">✚</span>
            <span>MD Farma Admin</span>
        </a>

        <nav class="admin-nav" aria-label="Navigasi admin">
            <a
                class="admin-nav-link"
                href="{{ route('admin.inbox') }}"
            >
                💬 Inbox
            </a>

            <a
                class="admin-nav-link"
                href="{{ route('admin.archive-requests.index') }}"
            >
                🗂 Permintaan Arsip
            </a>

            <a
                class="admin-nav-link active"
                href="{{ route('admin.dashboard') }}"
            >
                📊 Analitik
            </a>
        </nav>

        <div class="admin-actions">
            <div
                class="live-pill"
                id="dashboardConnection"
                aria-live="polite"
            >
                <span class="live-dot"></span>
                <span data-live-text>
                    Menghubungkan realtime
                </span>
            </div>

            <button
                class="notification-button"
                id="notificationButton"
                type="button"
            >
                🔔 <span data-notification-label>
                    Aktifkan Notifikasi
                </span>
                <span
                    class="notification-badge"
                    id="notificationBadge"
                >0</span>
            </button>

            <div class="identity">
                <strong>
                    {{ auth('admin')->user()->username }}
                </strong>
                <small>Administrator</small>
            </div>

            <form
                action="{{ route('admin.logout') }}"
                method="POST"
            >
                @csrf
                <button class="logout" type="submit">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="page">
        <section class="hero">
            <div>
                <p class="eyebrow">
                    Dashboard analitik
                </p>
                <h1>Analitik Konsultasi</h1>
                <p>
                    Pantau tren, konsultasi baru,
                    dan aktivitas tersibuk secara realtime.
                </p>
            </div>

            <span class="period-label">
                {{ $periodLabel }} · WIB
            </span>
        </section>

        @if (session('success'))
            <div class="notice">
                {{ session('success') }}
            </div>
        @endif

        <section class="panel filter-panel">
            <div class="period-tabs">
                @foreach ([
                    'today' => 'Hari Ini',
                    'week' => 'Minggu Ini',
                    'month' => 'Bulan Ini',
                    'year' => 'Tahun Ini',
                ] as $key => $label)
                    <a
                        class="period-tab {{
                            $period === $key
                                ? 'active'
                                : ''
                        }}"
                        href="{{ route(
                            'admin.dashboard',
                            array_merge(
                                request()->except([
                                    'period',
                                    'start_date',
                                    'end_date',
                                    'page',
                                ]),
                                ['period' => $key]
                            )
                        ) }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form
                class="custom-period"
                action="{{ route('admin.dashboard') }}"
                method="GET"
            >
                <input
                    type="hidden"
                    name="period"
                    value="custom"
                >

                <div class="field">
                    <label for="start_date">
                        Tanggal mulai
                    </label>
                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        value="{{ $startDate }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="end_date">
                        Tanggal akhir
                    </label>
                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        value="{{ $endDate }}"
                        required
                    >
                </div>

                <button class="button" type="submit">
                    Terapkan Rentang
                </button>
            </form>
        </section>

        <section class="kpi-grid">
            <article class="kpi">
                <span>Total Konsultasi</span>
                <strong data-kpi="totalConsultation">
                    {{ $totalConsultation }}
                </strong>
                <small>Periode terpilih</small>
            </article>

            <article class="kpi">
                <span>Konsultasi Aktif</span>
                <strong data-kpi="activeChat">
                    {{ $activeChat }}
                </strong>
                <small>Masih memerlukan penanganan</small>
            </article>

            <article class="kpi">
                <span>Konsultasi Selesai</span>
                <strong data-kpi="completedChat">
                    {{ $completedChat }}
                </strong>
                <small>Sudah ditutup admin</small>
            </article>

            <article class="kpi">
                <span>Rata-rata Respons</span>
                <strong
                    data-kpi="averageResponseLabel"
                    style="font-size:22px"
                >
                    {{ $averageResponseLabel }}
                </strong>
                <small>Balasan admin pertama</small>
            </article>

            <article class="kpi">
                <span>Akses Form</span>
                <strong data-kpi="formViews">
                    {{ $formViews }}
                </strong>
                <small>
                    <span data-kpi="uniqueFormSessions">
                        {{ $uniqueFormSessions }}
                    </span>
                    estimasi sesi unik
                </small>
            </article>

            <article class="kpi">
                <span>Conversion Rate</span>
                <strong data-kpi="conversionRate">
                    {{ $conversionRate }}%
                </strong>
                <small>
                    <span data-kpi="trackedConsultations">
                        {{ $trackedConsultations }}
                    </span>
                    konsultasi terbuat ·
                    <span data-kpi="chatOpens">
                        {{ $chatOpens }}
                    </span>
                    akses chat
                </small>
            </article>
        </section>

        <section class="grid-2">
            <article class="panel">
                <header class="panel-header">
                    <div>
                        <h2 id="trendTitle">
                            {{ $trend['title'] }}
                        </h2>
                        <p>
                            Jumlah konsultasi pada periode
                            terpilih
                        </p>
                    </div>
                </header>

                <div class="chart-wrap">
                    <canvas id="trendChart"></canvas>
                </div>
            </article>

            <article class="panel">
                <header class="panel-header">
                    <div>
                        <h2>Jenis Konsultasi</h2>
                        <p>Resep dibanding non resep</p>
                    </div>
                </header>

                @php
                    $typeTotal = max(
                        1,
                        $resep + $nonResep
                    );

                    $resepPercent = round(
                        ($resep / $typeTotal) * 100,
                        1
                    );
                @endphp

                <div class="donut-area">
                    <div
                        class="donut"
                        id="typeDonut"
                        style="--share:{{
                            $resepPercent
                        }}%"
                    >
                        <div class="donut-value">
                            <strong id="resepPercent">
                                {{ $resepPercent }}%
                            </strong>
                            <small>Resep dokter</small>
                        </div>
                    </div>

                    <div class="legend">
                        <div class="legend-row">
                            <span class="legend-name">
                                <i class="dot"></i>
                                Resep Dokter
                            </span>
                            <strong id="resepCount">
                                {{ $resep }}
                            </strong>
                        </div>

                        <div class="legend-row">
                            <span class="legend-name">
                                <i class="dot gray"></i>
                                Non Resep
                            </span>
                            <strong id="nonResepCount">
                                {{ $nonResep }}
                            </strong>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <section class="panel activity-panel">
            <header class="panel-header">
                <div>
                    <h2>Insight Waktu Konsultasi</h2>
                    <p>
                        Ringkasan padat agar informasi utama
                        lebih cepat dibaca
                    </p>
                </div>

                <div class="activity-toolbar">
                    <span class="sync-label">
                        Sinkron:
                        <strong id="lastSync">sekarang</strong>
                    </span>
                </div>
            </header>

            <div class="insight-strip">
                @foreach ([
                    'day' => 'Hari Tersibuk',
                    'date' => 'Tanggal Tersibuk',
                    'month' => 'Bulan Tersibuk',
                    'hour' => 'Jam Tersibuk',
                ] as $metricKey => $metricTitle)
                    @php
                        $metric =
                            $busyMetrics[$metricKey];
                    @endphp

                    <article class="insight">
                        <span>{{ $metricTitle }}</span>
                        <strong
                            data-busy-label="{{
                                $metricKey
                            }}"
                        >
                            {{
                                $metric['label']
                                    ?? 'Belum ada data'
                            }}
                        </strong>
                        <small>
                            <span
                                data-busy-total="{{
                                    $metricKey
                                }}"
                            >
                                {{ $metric['total'] ?? 0 }}
                            </span>
                            konsultasi
                        </small>
                    </article>
                @endforeach
            </div>

            <div class="activity-tabs">
                <button
                    class="activity-tab active"
                    type="button"
                    data-activity-tab="hours"
                >
                    Jam Konsultasi
                </button>

                <button
                    class="activity-tab"
                    type="button"
                    data-activity-tab="calendar"
                >
                    Kalender Kepadatan
                </button>
            </div>

            <div
                class="activity-view active"
                data-activity-view="hours"
            >
                <div class="hour-layout">
                    <div
                        class="period-buckets"
                        id="periodBuckets"
                    >
                        @foreach (
                            $compactHourly['periods']
                            as $bucket
                        )
                            <article class="period-bucket">
                                <span>
                                    {{ $bucket['label'] }}
                                </span>
                                <strong>
                                    {{ $bucket['total'] }}
                                </strong>
                                <small>
                                    {{ $bucket['range'] }} ·
                                    {{ $bucket['share'] }}%
                                </small>
                            </article>
                        @endforeach
                    </div>

                    <div class="top-hours">
                        <p class="section-label">
                            Enam jam paling aktif
                        </p>

                        <div id="topHours">
                            @forelse (
                                $compactHourly['topHours']
                                as $hour
                            )
                                <div class="hour-rank">
                                    <span>
                                        {{ $hour['label'] }}
                                    </span>
                                    <div class="hour-track">
                                        <div
                                            class="hour-fill"
                                            style="width:{{
                                                $hour['width']
                                            }}%"
                                        ></div>
                                    </div>
                                    <strong>
                                        {{ $hour['total'] }}
                                    </strong>
                                </div>
                            @empty
                                <div class="no-data">
                                    Belum ada data konsultasi
                                    pada periode ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="activity-view"
                data-activity-view="calendar"
            >
                <div class="calendar-head">
                    <div class="calendar-title">
                        <strong id="calendarLabel">
                            {{ $calendar['label'] }}
                        </strong>
                        <small>
                            <span id="calendarTotal">
                                {{ $calendar['total'] }}
                            </span>
                            konsultasi · klik tanggal
                            untuk rincian
                        </small>
                    </div>

                    <div class="calendar-nav">
                        <a
                            aria-label="Bulan sebelumnya"
                            href="{{ route(
                                'admin.dashboard',
                                array_merge(
                                    request()->except([
                                        'calendar_month',
                                        'page',
                                    ]),
                                    [
                                        'calendar_month' =>
                                            $calendar[
                                                'previous'
                                            ],
                                    ]
                                )
                            ) }}"
                        >‹</a>

                        <a
                            aria-label="Bulan berikutnya"
                            href="{{ route(
                                'admin.dashboard',
                                array_merge(
                                    request()->except([
                                        'calendar_month',
                                        'page',
                                    ]),
                                    [
                                        'calendar_month' =>
                                            $calendar['next'],
                                    ]
                                )
                            ) }}"
                        >›</a>
                    </div>
                </div>

                <div class="calendar" id="calendarGrid">
                    @foreach ([
                        'Sen','Sel','Rab','Kam',
                        'Jum','Sab','Min',
                    ] as $weekday)
                        <div class="weekday">
                            {{ $weekday }}
                        </div>
                    @endforeach

                    @foreach (
                        $calendar['cells']
                        as $cell
                    )
                        @if ($cell === null)
                            <div
                                class="calendar-blank"
                            ></div>
                        @else
                            <button
                                class="calendar-day
                                    i{{ $cell['intensity'] }}
                                    {{
                                        $cell['is_today']
                                            ? 'today'
                                            : ''
                                    }}"
                                type="button"
                                data-day='{!! json_encode(
                                    $cell,
                                    JSON_HEX_APOS
                                    | JSON_HEX_QUOT
                                ) !!}'
                            >
                                <span>
                                    {{ $cell['day'] }}
                                </span>
                                @if ($cell['total'] > 0)
                                    <i>
                                        {{ $cell['total'] }}
                                    </i>
                                @endif
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="calendar-legend">
                    <span>
                        <i class="heat"></i> Tidak ada
                    </span>
                    <span>
                        <i class="heat h1"></i> Rendah
                    </span>
                    <span>
                        <i class="heat h2"></i> Sedang
                    </span>
                    <span>
                        <i class="heat h3"></i> Tinggi
                    </span>
                    <span>
                        <i class="heat h4"></i>
                        Sangat tinggi
                    </span>
                </div>
            </div>
        </section>

        <section class="panel table-panel">
            <header
                class="panel-header"
                style="padding-bottom:16px"
            >
                <div>
                    <h2>Daftar Konsultasi</h2>
                    <p>
                        Data baru masuk otomatis tanpa
                        refresh halaman
                    </p>
                </div>
            </header>

            <form
                class="table-filter"
                action="{{ route('admin.dashboard') }}"
                method="GET"
            >
                <input
                    type="hidden"
                    name="period"
                    value="{{ $period }}"
                >

                @if ($period === 'custom')
                    <input
                        type="hidden"
                        name="start_date"
                        value="{{ $startDate }}"
                    >
                    <input
                        type="hidden"
                        name="end_date"
                        value="{{ $endDate }}"
                    >
                @endif

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau nomor HP..."
                >

                <select name="type">
                    <option value="">
                        Semua jenis
                    </option>
                    <option
                        value="resep"
                        @selected($type === 'resep')
                    >
                        Resep Dokter
                    </option>
                    <option
                        value="non_resep"
                        @selected(
                            $type === 'non_resep'
                        )
                    >
                        Non Resep
                    </option>
                </select>

                <select name="status">
                    <option value="">
                        Semua status
                    </option>
                    <option
                        value="aktif"
                        @selected($status === 'aktif')
                    >
                        Aktif
                    </option>
                    <option
                        value="selesai"
                        @selected(
                            $status === 'selesai'
                        )
                    >
                        Selesai
                    </option>
                </select>

                <select name="sort">
                    <option
                        value="latest"
                        @selected($sort === 'latest')
                    >
                        Terbaru
                    </option>
                    <option
                        value="oldest"
                        @selected($sort === 'oldest')
                    >
                        Terlama
                    </option>
                    <option
                        value="last_activity"
                        @selected(
                            $sort === 'last_activity'
                        )
                    >
                        Aktivitas terakhir
                    </option>
                </select>

                <button class="button" type="submit">
                    Filter
                </button>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Jenis</th>
                            <th>Dibuat</th>
                            <th>Pesan Terakhir</th>
                            <th>Respons Pertama</th>
                            <th>Status</th>
                            <th>Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="consultationTableBody">
                        @include(
                            'admin.partials.consultation-table-rows',
                            [
                                'consultations' =>
                                    $consultations,
                                'timezone' => $timezone,
                            ]
                        )
                    </tbody>
                </table>
            </div>

            <div id="consultationPagination">
                @include(
                    'admin.partials.consultation-pagination',
                    [
                        'consultations' =>
                            $consultations,
                    ]
                )
            </div>
        </section>
    </main>

    <div class="toast-stack" id="toastStack"></div>

    <div class="modal-bg" id="calendarModal">
        <div class="modal">
            <header class="modal-head">
                <div>
                    <h3 id="modalTitle">
                        Detail Tanggal
                    </h3>
                    <p>
                        Rincian kepadatan konsultasi
                    </p>
                </div>

                <button
                    class="modal-close"
                    id="modalClose"
                    type="button"
                    aria-label="Tutup"
                >×</button>
            </header>

            <div class="modal-grid">
                <div class="modal-stat">
                    <span>Total Konsultasi</span>
                    <strong id="modalTotal">0</strong>
                </div>

                <div class="modal-stat">
                    <span>Resep Dokter</span>
                    <strong id="modalResep">0</strong>
                </div>

                <div class="modal-stat">
                    <span>Non Resep</span>
                    <strong id="modalNonResep">0</strong>
                </div>

                <div class="modal-stat">
                    <span>Jam Tersibuk</span>
                    <strong id="modalHour">-</strong>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const liveDataUrl = @json(
                route(
                    'admin.dashboard.live',
                    request()->query()
                )
            );

            let trendLabels = @json($trend['labels']);
            let trendValues = @json($trend['values']);
            let initialized = false;
            let refreshTimer = null;
            let unreadNotifications = 0;

            const canvas =
                document.getElementById('trendChart');

            const connection =
                document.getElementById(
                    'dashboardConnection'
                );

            const notificationButton =
                document.getElementById(
                    'notificationButton'
                );

            const notificationBadge =
                document.getElementById(
                    'notificationBadge'
                );

            const modal =
                document.getElementById(
                    'calendarModal'
                );

            function drawChart() {
                const rect =
                    canvas.getBoundingClientRect();

                const ratio =
                    window.devicePixelRatio || 1;

                const width =
                    Math.max(320, rect.width);

                const height =
                    Math.max(235, rect.height);

                canvas.width = width * ratio;
                canvas.height = height * ratio;

                const ctx =
                    canvas.getContext('2d');

                ctx.setTransform(
                    ratio,
                    0,
                    0,
                    ratio,
                    0,
                    0
                );

                ctx.clearRect(
                    0,
                    0,
                    width,
                    height
                );

                const padding = {
                    top:20,
                    right:18,
                    bottom:45,
                    left:39,
                };

                const chartWidth =
                    width
                    - padding.left
                    - padding.right;

                const chartHeight =
                    height
                    - padding.top
                    - padding.bottom;

                const maximum = Math.max(
                    1,
                    ...trendValues
                );

                ctx.font = '10px system-ui';
                ctx.fillStyle = '#64748b';
                ctx.strokeStyle = '#e2e8f0';

                for (let index = 0; index <= 4; index++) {
                    const y =
                        padding.top
                        + chartHeight
                        * index / 4;

                    ctx.beginPath();
                    ctx.moveTo(padding.left, y);
                    ctx.lineTo(
                        width - padding.right,
                        y
                    );
                    ctx.stroke();

                    ctx.fillText(
                        String(
                            Math.round(
                                maximum
                                - maximum
                                * index / 4
                            )
                        ),
                        4,
                        y + 3
                    );
                }

                if (!trendValues.length) {
                    return;
                }

                const step =
                    trendValues.length > 1
                        ? chartWidth
                            / (
                                trendValues.length
                                - 1
                            )
                        : chartWidth;

                const points =
                    trendValues.map(
                        (value, index) => ({
                            x: padding.left
                                + (
                                    trendValues.length > 1
                                        ? step * index
                                        : chartWidth / 2
                                ),
                            y: padding.top
                                + chartHeight
                                - (
                                    value
                                    / maximum
                                )
                                * chartHeight,
                        })
                    );

                const gradient =
                    ctx.createLinearGradient(
                        0,
                        padding.top,
                        0,
                        padding.top
                            + chartHeight
                    );

                gradient.addColorStop(
                    0,
                    'rgba(5,150,105,.27)'
                );

                gradient.addColorStop(
                    1,
                    'rgba(5,150,105,0)'
                );

                ctx.beginPath();
                ctx.moveTo(
                    points[0].x,
                    padding.top + chartHeight
                );

                points.forEach(
                    point => ctx.lineTo(
                        point.x,
                        point.y
                    )
                );

                ctx.lineTo(
                    points.at(-1).x,
                    padding.top + chartHeight
                );

                ctx.closePath();
                ctx.fillStyle = gradient;
                ctx.fill();

                ctx.beginPath();

                points.forEach(
                    (point, index) => {
                        if (index === 0) {
                            ctx.moveTo(
                                point.x,
                                point.y
                            );
                        } else {
                            ctx.lineTo(
                                point.x,
                                point.y
                            );
                        }
                    }
                );

                ctx.strokeStyle = '#047857';
                ctx.lineWidth = 3;
                ctx.lineJoin = 'round';
                ctx.stroke();

                points.forEach(point => {
                    ctx.beginPath();
                    ctx.arc(
                        point.x,
                        point.y,
                        3.5,
                        0,
                        Math.PI * 2
                    );
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    ctx.strokeStyle = '#047857';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                });

                const maxLabels =
                    width < 650 ? 6 : 12;

                const every = Math.max(
                    1,
                    Math.ceil(
                        trendLabels.length
                        / maxLabels
                    )
                );

                trendLabels.forEach(
                    (label, index) => {
                        if (
                            index % every !== 0
                            && index
                                !== trendLabels.length
                                    - 1
                        ) {
                            return;
                        }

                        ctx.save();
                        ctx.translate(
                            points[index].x,
                            height - 13
                        );
                        ctx.rotate(-.35);
                        ctx.fillStyle = '#64748b';
                        ctx.textAlign = 'right';
                        ctx.fillText(label, 0, 0);
                        ctx.restore();
                    }
                );
            }

            function setConnectionStatus(
                state,
                text
            ) {
                connection.classList.remove(
                    'connected',
                    'disconnected'
                );

                if (state) {
                    connection.classList.add(
                        state
                    );
                }

                connection
                    .querySelector('[data-live-text]')
                    .textContent = text;
            }

            function setKpi(name, value) {
                const element =
                    document.querySelector(
                        `[data-kpi="${name}"]`
                    );

                if (element) {
                    element.textContent = value;
                }
            }

            function updateKpis(kpis) {
                Object.entries(kpis)
                    .forEach(([name, value]) => {
                        setKpi(
                            name,
                            name === 'conversionRate'
                                ? `${value}%`
                                : value
                        );
                    });
            }

            function updateTypes(types) {
                const total = Math.max(
                    1,
                    types.resep
                    + types.nonResep
                );

                const share = Math.round(
                    (
                        types.resep
                        / total
                    ) * 1000
                ) / 10;

                document
                    .getElementById('typeDonut')
                    .style.setProperty(
                        '--share',
                        `${share}%`
                    );

                document
                    .getElementById(
                        'resepPercent'
                    )
                    .textContent =
                        `${share}%`;

                document
                    .getElementById(
                        'resepCount'
                    )
                    .textContent =
                        types.resep;

                document
                    .getElementById(
                        'nonResepCount'
                    )
                    .textContent =
                        types.nonResep;
            }

            function updateBusyMetrics(metrics) {
                Object.entries(metrics)
                    .forEach(([key, metric]) => {
                        const label =
                            document.querySelector(
                                `[data-busy-label="${key}"]`
                            );

                        const total =
                            document.querySelector(
                                `[data-busy-total="${key}"]`
                            );

                        if (label) {
                            label.textContent =
                                metric?.label
                                ?? 'Belum ada data';
                        }

                        if (total) {
                            total.textContent =
                                metric?.total ?? 0;
                        }
                    });
            }

            function renderCompactHourly(data) {
                const bucketContainer =
                    document.getElementById(
                        'periodBuckets'
                    );

                bucketContainer.innerHTML = '';

                data.periods.forEach(bucket => {
                    const article =
                        document.createElement(
                            'article'
                        );

                    article.className =
                        'period-bucket';

                    const label =
                        document.createElement(
                            'span'
                        );

                    label.textContent =
                        bucket.label;

                    const total =
                        document.createElement(
                            'strong'
                        );

                    total.textContent =
                        bucket.total;

                    const meta =
                        document.createElement(
                            'small'
                        );

                    meta.textContent =
                        `${bucket.range} · `
                        + `${bucket.share}%`;

                    article.append(
                        label,
                        total,
                        meta
                    );

                    bucketContainer.appendChild(
                        article
                    );
                });

                const topContainer =
                    document.getElementById(
                        'topHours'
                    );

                topContainer.innerHTML = '';

                if (!data.topHours.length) {
                    const empty =
                        document.createElement(
                            'div'
                        );

                    empty.className = 'no-data';
                    empty.textContent =
                        'Belum ada data konsultasi '
                        + 'pada periode ini.';

                    topContainer.appendChild(empty);
                    return;
                }

                data.topHours.forEach(hour => {
                    const row =
                        document.createElement(
                            'div'
                        );

                    row.className = 'hour-rank';

                    const label =
                        document.createElement(
                            'span'
                        );

                    label.textContent =
                        hour.label;

                    const track =
                        document.createElement(
                            'div'
                        );

                    track.className =
                        'hour-track';

                    const fill =
                        document.createElement(
                            'div'
                        );

                    fill.className = 'hour-fill';
                    fill.style.width =
                        `${hour.width}%`;

                    track.appendChild(fill);

                    const total =
                        document.createElement(
                            'strong'
                        );

                    total.textContent =
                        hour.total;

                    row.append(
                        label,
                        track,
                        total
                    );

                    topContainer.appendChild(row);
                });
            }

            function openCalendarDetail(data) {
                document
                    .getElementById('modalTitle')
                    .textContent =
                        data.date_label;

                document
                    .getElementById('modalTotal')
                    .textContent =
                        data.total;

                document
                    .getElementById('modalResep')
                    .textContent =
                        data.resep;

                document
                    .getElementById(
                        'modalNonResep'
                    )
                    .textContent =
                        data.non_resep;

                document
                    .getElementById('modalHour')
                    .textContent =
                        data.busiest_hour;

                modal.classList.add('open');
            }

            function bindCalendarCells() {
                document
                    .querySelectorAll('[data-day]')
                    .forEach(button => {
                        button.onclick = () => {
                            openCalendarDetail(
                                JSON.parse(
                                    button.dataset.day
                                )
                            );
                        };
                    });
            }

            function renderCalendar(calendar) {
                document
                    .getElementById(
                        'calendarLabel'
                    )
                    .textContent =
                        calendar.label;

                document
                    .getElementById(
                        'calendarTotal'
                    )
                    .textContent =
                        calendar.total;

                const grid =
                    document.getElementById(
                        'calendarGrid'
                    );

                grid.innerHTML = '';

                [
                    'Sen','Sel','Rab','Kam',
                    'Jum','Sab','Min',
                ].forEach(day => {
                    const weekday =
                        document.createElement(
                            'div'
                        );

                    weekday.className =
                        'weekday';

                    weekday.textContent = day;
                    grid.appendChild(weekday);
                });

                calendar.cells.forEach(cell => {
                    if (!cell) {
                        const blank =
                            document.createElement(
                                'div'
                            );

                        blank.className =
                            'calendar-blank';

                        grid.appendChild(blank);
                        return;
                    }

                    const button =
                        document.createElement(
                            'button'
                        );

                    button.type = 'button';
                    button.className =
                        `calendar-day `
                        + `i${cell.intensity} `
                        + (
                            cell.is_today
                                ? 'today'
                                : ''
                        );

                    button.dataset.day =
                        JSON.stringify(cell);

                    const day =
                        document.createElement(
                            'span'
                        );

                    day.textContent = cell.day;
                    button.appendChild(day);

                    if (cell.total > 0) {
                        const count =
                            document.createElement(
                                'i'
                            );

                        count.textContent =
                            cell.total;

                        button.appendChild(count);
                    }

                    grid.appendChild(button);
                });

                bindCalendarCells();
            }

            function flashNewestRow() {
                const row =
                    document.querySelector(
                        '[data-consultation-row]'
                    );

                if (!row) {
                    return;
                }

                row.classList.remove('flash-row');

                requestAnimationFrame(() => {
                    row.classList.add('flash-row');
                });
            }

            async function refreshLiveDashboard() {
                try {
                    const response = await fetch(
                        liveDataUrl,
                        {
                            credentials:'same-origin',
                            headers: {
                                Accept:'application/json',
                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },
                        }
                    );

                    if (!response.ok) {
                        throw new Error(
                            'Gagal menyinkronkan dashboard.'
                        );
                    }

                    const data =
                        await response.json();

                    updateKpis(data.kpis);
                    updateTypes(data.types);
                    updateBusyMetrics(
                        data.busyMetrics
                    );
                    renderCompactHourly(
                        data.compactHourly
                    );
                    renderCalendar(data.calendar);

                    trendLabels =
                        data.trend.labels;

                    trendValues =
                        data.trend.values;

                    document
                        .getElementById(
                            'trendTitle'
                        )
                        .textContent =
                            data.trend.title;

                    drawChart();

                    document
                        .getElementById(
                            'consultationTableBody'
                        )
                        .innerHTML =
                            data.tableHtml;

                    document
                        .getElementById(
                            'consultationPagination'
                        )
                        .innerHTML =
                            data.paginationHtml;

                    document
                        .getElementById(
                            'lastSync'
                        )
                        .textContent =
                            data.syncedAt;

                    flashNewestRow();
                } catch (error) {
                    setConnectionStatus(
                        'disconnected',
                        'Sinkronisasi dashboard gagal'
                    );
                }
            }

            function scheduleRefresh() {
                clearTimeout(refreshTimer);

                refreshTimer = setTimeout(
                    refreshLiveDashboard,
                    250
                );
            }

            function updateBadge() {
                notificationBadge.textContent =
                    unreadNotifications > 99
                        ? '99+'
                        : unreadNotifications;

                notificationBadge.classList.toggle(
                    'visible',
                    unreadNotifications > 0
                );
            }

            function showToast(activity) {
                if (
                    !activity.notification
                        ?.should_notify
                ) {
                    return;
                }

                unreadNotifications++;
                updateBadge();

                const toast =
                    document.createElement('div');

                toast.className = 'toast';

                const content =
                    document.createElement('div');

                const title =
                    document.createElement('strong');

                title.textContent =
                    activity.notification.title;

                const body =
                    document.createElement('span');

                body.textContent =
                    activity.notification
                        .toast_body;

                const link =
                    document.createElement('a');

                link.href =
                    activity.consultation.chat_url;

                link.textContent = 'Buka chat';

                content.append(
                    title,
                    body,
                    link
                );

                const close =
                    document.createElement(
                        'button'
                    );

                close.type = 'button';
                close.className = 'toast-close';
                close.textContent = '×';

                close.addEventListener(
                    'click',
                    () => toast.remove()
                );

                toast.append(content, close);

                document
                    .getElementById('toastStack')
                    .prepend(toast);

                setTimeout(
                    () => toast.remove(),
                    9000
                );
            }

            function showBrowserNotification(
                activity
            ) {
                if (
                    !activity.notification
                        ?.should_notify
                    || !('Notification' in window)
                    || Notification.permission
                        !== 'granted'
                ) {
                    return;
                }

                const notification =
                    new Notification(
                        activity.notification.title,
                        {
                            body:
                                activity.notification
                                    .browser_body,
                            tag:
                                activity.activity_type
                                + ':'
                                + activity
                                    .consultation
                                    .public_id,
                        }
                    );

                notification.onclick = () => {
                    window.focus();
                    window.location.href =
                        activity.consultation
                            .chat_url;
                    notification.close();
                };
            }

            function updateNotificationButton() {
                const label =
                    notificationButton
                        .querySelector(
                            '[data-notification-label]'
                        );

                if (!('Notification' in window)) {
                    label.textContent =
                        'Notifikasi tidak didukung';
                    notificationButton.disabled =
                        true;
                    return;
                }

                if (
                    Notification.permission
                    === 'granted'
                ) {
                    label.textContent =
                        'Notifikasi Aktif';
                    return;
                }

                if (
                    Notification.permission
                    === 'denied'
                ) {
                    label.textContent =
                        'Notifikasi Diblokir';
                    return;
                }

                label.textContent =
                    'Aktifkan Notifikasi';
            }

            notificationButton.addEventListener(
                'click',
                async () => {
                    unreadNotifications = 0;
                    updateBadge();

                    if (
                        !('Notification' in window)
                    ) {
                        return;
                    }

                    if (
                        Notification.permission
                        === 'default'
                    ) {
                        await Notification
                            .requestPermission();
                    }

                    updateNotificationButton();
                }
            );

            function initializeRealtime() {
                if (
                    initialized
                    || !window.Echo
                ) {
                    return;
                }

                initialized = true;

                window.Echo
                    .private('admin.dashboard')
                    .listen(
                        '.dashboard.activity',
                        activity => {
                            showToast(activity);
                            showBrowserNotification(
                                activity
                            );
                            scheduleRefresh();
                        }
                    );

                const websocket =
                    window.Echo
                        .connector
                        ?.pusher
                        ?.connection;

                websocket?.bind(
                    'connected',
                    () => setConnectionStatus(
                        'connected',
                        'Realtime terhubung'
                    )
                );

                websocket?.bind(
                    'disconnected',
                    () => setConnectionStatus(
                        'disconnected',
                        'Realtime terputus'
                    )
                );

                websocket?.bind(
                    'unavailable',
                    () => setConnectionStatus(
                        'disconnected',
                        'Server realtime tidak tersedia'
                    )
                );

                websocket?.bind(
                    'error',
                    () => setConnectionStatus(
                        'disconnected',
                        'Koneksi realtime bermasalah'
                    )
                );
            }

            document
                .querySelectorAll(
                    '[data-activity-tab]'
                )
                .forEach(button => {
                    button.addEventListener(
                        'click',
                        () => {
                            const target =
                                button.dataset
                                    .activityTab;

                            document
                                .querySelectorAll(
                                    '[data-activity-tab]'
                                )
                                .forEach(tab => {
                                    tab.classList
                                        .toggle(
                                            'active',
                                            tab === button
                                        );
                                });

                            document
                                .querySelectorAll(
                                    '[data-activity-view]'
                                )
                                .forEach(view => {
                                    view.classList
                                        .toggle(
                                            'active',
                                            view.dataset
                                                .activityView
                                                === target
                                        );
                                });
                        }
                    );
                });

            const closeModal = () =>
                modal.classList.remove('open');

            document
                .getElementById('modalClose')
                .addEventListener(
                    'click',
                    closeModal
                );

            modal.addEventListener(
                'click',
                event => {
                    if (event.target === modal) {
                        closeModal();
                    }
                }
            );

            document.addEventListener(
                'keydown',
                event => {
                    if (event.key === 'Escape') {
                        closeModal();
                    }
                }
            );

            document.addEventListener(
                'visibilitychange',
                () => {
                    if (!document.hidden) {
                        unreadNotifications = 0;
                        updateBadge();
                    }
                }
            );

            let resizeTimer;

            window.addEventListener(
                'resize',
                () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(
                        drawChart,
                        120
                    );
                }
            );

            bindCalendarCells();
            drawChart();
            updateNotificationButton();

            if (window.Echo) {
                initializeRealtime();
            } else {
                window.addEventListener(
                    'md-farma:echo-ready',
                    initializeRealtime,
                    { once:true }
                );
            }

            window.addEventListener(
                'beforeunload',
                () => {
                    window.Echo?.leave(
                        'admin.dashboard'
                    );
                }
            );
        })();
    </script>
</body>
</html>
