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

    <title>
        {{
            $selectedConsultation
                ? $selectedConsultation->nama.' · Inbox MD Farma'
                : 'Inbox Konsultasi MD Farma'
        }}
    </title>

    @vite('resources/js/app.js')

    <style>
        :root {
            --green-950:#052e28;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-500:#10b981;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --amber-700:#b45309;
            --amber-100:#fef3c7;
            --blue-700:#1d4ed8;
            --blue-100:#dbeafe;
            --red-700:#b91c1c;
            --red-100:#fee2e2;
            --slate-950:#0f172a;
            --slate-900:#172033;
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
            --shadow:0 18px 50px rgba(15,23,42,.10);
        }

        * { box-sizing:border-box; }

        html,body { height:100%; }

        body {
            margin:0;
            overflow:hidden;
            font-family:Inter,ui-sans-serif,system-ui,
                -apple-system,BlinkMacSystemFont,
                "Segoe UI",sans-serif;
            color:var(--slate-950);
            background:#e9eef2;
        }

        button,input,select,textarea { font:inherit; }
        button,a { -webkit-tap-highlight-color:transparent; }

        .admin-topbar {
            height:64px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
            padding:0 20px;
            color:#fff;
            background:linear-gradient(
                135deg,
                var(--green-950),
                var(--green-800)
            );
            box-shadow:0 8px 25px rgba(5,46,40,.20);
        }

        .brand-area,
        .topbar-nav,
        .topbar-actions {
            display:flex;
            align-items:center;
            gap:10px;
        }

        .brand-area {
            min-width:250px;
            color:#fff;
            text-decoration:none;
            font-weight:850;
        }

        .brand-mark {
            display:grid;
            place-items:center;
            width:38px;
            height:38px;
            border:1px solid rgba(255,255,255,.2);
            border-radius:12px;
            background:rgba(255,255,255,.12);
        }

        .topbar-nav {
            gap:6px;
        }

        .nav-link {
            display:flex;
            align-items:center;
            gap:7px;
            min-height:38px;
            padding:8px 12px;
            border-radius:10px;
            color:rgba(255,255,255,.78);
            text-decoration:none;
            font-size:13px;
            font-weight:750;
        }

        .nav-link.active,
        .nav-link:hover {
            color:#fff;
            background:rgba(255,255,255,.14);
        }

        .nav-count {
            min-width:21px;
            height:21px;
            display:grid;
            place-items:center;
            padding:0 6px;
            border-radius:999px;
            background:#ef4444;
            color:#fff;
            font-size:10px;
            font-weight:900;
        }

        .live-state {
            display:flex;
            align-items:center;
            gap:7px;
            padding:7px 10px;
            border:1px solid rgba(255,255,255,.15);
            border-radius:10px;
            background:rgba(255,255,255,.08);
            font-size:11px;
            font-weight:750;
        }

        .live-dot {
            width:8px;
            height:8px;
            border-radius:50%;
            background:#f59e0b;
        }

        .live-state.connected .live-dot { background:#4ade80; }
        .live-state.disconnected .live-dot { background:#f87171; }

        .notification-toggle,
        .logout-button {
            min-height:36px;
            padding:7px 10px;
            border:1px solid rgba(255,255,255,.16);
            border-radius:10px;
            color:#fff;
            background:rgba(255,255,255,.08);
            cursor:pointer;
        }

        .admin-identity {
            display:grid;
            text-align:right;
            line-height:1.1;
        }

        .admin-identity strong { font-size:12px; }
        .admin-identity small {
            margin-top:3px;
            color:rgba(255,255,255,.65);
            font-size:10px;
        }

        .workspace {
            height:calc(100vh - 64px);
            display:grid;
            grid-template-columns:370px minmax(480px,1fr) 310px;
            overflow:hidden;
        }

        .sidebar {
            min-width:0;
            display:flex;
            flex-direction:column;
            border-right:1px solid var(--slate-200);
            background:#fff;
        }

        .sidebar-header {
            padding:16px 16px 12px;
            border-bottom:1px solid var(--slate-200);
            background:#fff;
        }

        .sidebar-title-row {
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            margin-bottom:12px;
        }

        .sidebar-title-row h1 {
            margin:0;
            font-size:21px;
            letter-spacing:-.025em;
        }

        .sidebar-title-row p {
            margin:4px 0 0;
            color:var(--slate-500);
            font-size:11px;
        }

        .sync-time {
            color:var(--slate-400);
            font-size:10px;
            white-space:nowrap;
        }

        .search-box {
            position:relative;
            margin-bottom:10px;
        }

        .search-box span {
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            color:var(--slate-400);
        }

        .search-box input {
            width:100%;
            height:40px;
            padding:8px 12px 8px 36px;
            border:1px solid var(--slate-200);
            border-radius:11px;
            outline:none;
            background:var(--slate-50);
        }

        .search-box input:focus {
            border-color:var(--green-500);
            box-shadow:0 0 0 3px rgba(16,185,129,.13);
            background:#fff;
        }

        .filter-row {
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:8px;
            margin-bottom:10px;
        }

        .filter-row select {
            min-width:0;
            height:37px;
            padding:7px 9px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            color:var(--slate-700);
            background:#fff;
        }

        .state-tabs {
            display:flex;
            gap:6px;
            overflow-x:auto;
            padding-bottom:2px;
            scrollbar-width:none;
        }

        .state-tabs::-webkit-scrollbar { display:none; }

        .state-tab {
            flex:0 0 auto;
            display:flex;
            align-items:center;
            gap:5px;
            min-height:31px;
            padding:6px 9px;
            border:1px solid var(--slate-200);
            border-radius:999px;
            color:var(--slate-600);
            text-decoration:none;
            font-size:10px;
            font-weight:800;
            background:#fff;
        }

        .state-tab.active {
            border-color:var(--green-600);
            color:var(--green-800);
            background:var(--green-50);
        }

        .tab-count {
            min-width:18px;
            height:18px;
            display:grid;
            place-items:center;
            padding:0 4px;
            border-radius:999px;
            background:var(--slate-100);
            font-size:9px;
        }

        .state-tab.active .tab-count {
            background:var(--green-100);
        }

        .conversation-list {
            flex:1;
            min-height:0;
            overflow-y:auto;
            background:#fff;
        }

        .conversation-item {
            position:relative;
            display:grid;
            grid-template-columns:44px minmax(0,1fr);
            gap:11px;
            min-height:100px;
            padding:13px 14px;
            border-bottom:1px solid var(--slate-100);
            color:inherit;
            text-decoration:none;
            transition:background .15s ease;
        }

        .conversation-item:hover { background:var(--slate-50); }

        .conversation-item.active {
            background:var(--green-50);
        }

        .conversation-item.active::before {
            content:"";
            position:absolute;
            inset:0 auto 0 0;
            width:4px;
            background:var(--green-600);
        }

        .conversation-item.unread .conversation-name {
            color:var(--slate-950);
            font-weight:900;
        }

        .conversation-avatar {
            display:grid;
            place-items:center;
            width:44px;
            height:44px;
            border-radius:50%;
            color:#fff;
            background:linear-gradient(
                145deg,
                var(--green-700),
                var(--green-500)
            );
            font-size:16px;
            font-weight:900;
            box-shadow:0 7px 18px rgba(5,150,105,.18);
        }

        .conversation-avatar.large {
            width:42px;
            height:42px;
            flex:0 0 auto;
        }

        .conversation-copy { min-width:0; }

        .conversation-row {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:9px;
            min-width:0;
        }

        .conversation-name {
            min-width:0;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:13px;
            color:var(--slate-800);
        }

        .conversation-time {
            flex:0 0 auto;
            color:var(--slate-400);
            font-size:10px;
        }

        .conversation-meta-row {
            display:flex;
            align-items:center;
            gap:5px;
            margin:6px 0;
        }

        .state-chip,
        .type-chip {
            display:inline-flex;
            align-items:center;
            min-height:20px;
            padding:3px 7px;
            border-radius:999px;
            font-size:9px;
            font-weight:850;
            white-space:nowrap;
        }

        .type-chip {
            color:var(--slate-600);
            background:var(--slate-100);
        }

        .state-new {
            color:var(--blue-700);
            background:var(--blue-100);
        }

        .state-waiting_admin {
            color:var(--red-700);
            background:var(--red-100);
        }

        .state-waiting_patient {
            color:var(--amber-700);
            background:var(--amber-100);
        }

        .state-completed {
            color:var(--green-800);
            background:var(--green-100);
        }

        .state-chip.large {
            min-height:27px;
            padding:5px 10px;
            font-size:10px;
        }

        .preview-row { align-items:flex-end; }

        .conversation-preview {
            min-width:0;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            color:var(--slate-500);
            font-size:11px;
        }

        .conversation-item.unread .conversation-preview {
            color:var(--slate-700);
            font-weight:700;
        }

        .preview-sender { color:var(--green-700); }

        .unread-badge {
            flex:0 0 auto;
            min-width:21px;
            height:21px;
            display:grid;
            place-items:center;
            padding:0 6px;
            border-radius:999px;
            color:#fff;
            background:var(--green-600);
            font-size:9px;
            font-weight:900;
        }

        .conversation-empty {
            min-height:260px;
            display:grid;
            place-content:center;
            justify-items:center;
            padding:30px;
            text-align:center;
            color:var(--slate-500);
        }

        .conversation-empty strong {
            margin-top:8px;
            color:var(--slate-700);
        }

        .conversation-empty p {
            max-width:240px;
            margin:6px 0 0;
            font-size:11px;
            line-height:1.55;
        }

        .empty-icon { font-size:32px; }

        .pagination-slot {
            border-top:1px solid var(--slate-200);
            background:#fff;
        }

        .inbox-pagination {
            display:flex;
            align-items:center;
            justify-content:center;
            gap:12px;
            padding:9px;
        }

        .pagination-button {
            width:30px;
            height:30px;
            display:grid;
            place-items:center;
            border:1px solid var(--slate-200);
            border-radius:8px;
            color:var(--slate-700);
            text-decoration:none;
            background:#fff;
        }

        .pagination-button.disabled {
            color:var(--slate-300);
            background:var(--slate-50);
        }

        .pagination-label {
            color:var(--slate-500);
            font-size:10px;
            font-weight:700;
        }

        .conversation-pane {
            min-width:0;
            min-height:0;
            background:#e8efec;
        }

        .conversation-placeholder {
            height:100%;
            display:grid;
            place-content:center;
            justify-items:center;
            padding:40px;
            text-align:center;
            color:var(--slate-500);
            background:
                radial-gradient(
                    circle at 20% 20%,
                    rgba(16,185,129,.10),
                    transparent 28%
                ),
                linear-gradient(145deg,#eef5f2,#e4ece8);
        }

        .placeholder-icon {
            width:86px;
            height:86px;
            display:grid;
            place-items:center;
            border-radius:28px;
            color:var(--green-800);
            background:rgba(255,255,255,.78);
            box-shadow:var(--shadow);
            font-size:38px;
        }

        .conversation-placeholder h2 {
            margin:20px 0 8px;
            color:var(--slate-800);
        }

        .conversation-placeholder p {
            max-width:430px;
            margin:0;
            line-height:1.65;
            font-size:13px;
        }

        .conversation-shell {
            height:100%;
            display:grid;
            grid-template-rows:65px minmax(0,1fr) auto;
            background:#edf3f0;
        }

        .conversation-header {
            display:flex;
            align-items:center;
            gap:11px;
            padding:10px 15px;
            border-bottom:1px solid var(--slate-200);
            background:rgba(255,255,255,.96);
        }

        .conversation-heading {
            min-width:0;
            display:grid;
        }

        .conversation-heading strong {
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:14px;
        }

        .conversation-heading span {
            margin-top:3px;
            color:var(--slate-500);
            font-size:10px;
        }

        .conversation-header-actions {
            margin-left:auto;
            display:flex;
            gap:7px;
        }

        .header-action {
            min-height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:7px 10px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            color:var(--slate-700);
            text-decoration:none;
            background:#fff;
            cursor:pointer;
            font-size:10px;
            font-weight:800;
        }

        .mobile-back {
            display:none;
            width:34px;
            height:34px;
            border:1px solid var(--slate-200);
            border-radius:9px;
            background:#fff;
            cursor:pointer;
        }

        .message-stream {
            min-height:0;
            overflow-y:auto;
            padding:20px clamp(16px,4vw,44px);
            background:
                linear-gradient(
                    rgba(239,246,243,.92),
                    rgba(239,246,243,.92)
                ),
                radial-gradient(
                    circle at 15px 15px,
                    rgba(5,150,105,.09) 1px,
                    transparent 1px
                );
            background-size:auto,30px 30px;
        }

        .date-divider {
            display:flex;
            justify-content:center;
            margin:8px 0 15px;
        }

        .date-divider span {
            padding:6px 10px;
            border:1px solid rgba(148,163,184,.28);
            border-radius:999px;
            color:var(--slate-600);
            background:rgba(255,255,255,.86);
            box-shadow:0 5px 13px rgba(15,23,42,.05);
            font-size:9px;
            font-weight:800;
        }

        .message-bubble {
            width:fit-content;
            max-width:min(76%,620px);
            margin-bottom:10px;
            padding:9px 11px 7px;
            border-radius:13px;
            box-shadow:0 7px 20px rgba(15,23,42,.07);
        }

        .message-bubble.patient {
            margin-right:auto;
            border-top-left-radius:4px;
            background:#fff;
        }

        .message-bubble.admin {
            margin-left:auto;
            border-top-right-radius:4px;
            background:#d8f7e8;
        }

        .message-sender {
            display:block;
            margin-bottom:4px;
            color:var(--green-800);
            font-size:9px;
            font-weight:900;
        }

        .message-bubble p {
            margin:0;
            white-space:pre-wrap;
            overflow-wrap:anywhere;
            font-size:13px;
            line-height:1.5;
        }

        .message-time {
            display:block;
            margin-top:5px;
            color:var(--slate-500);
            text-align:right;
            font-size:8px;
        }

        .message-attachment {
            display:block;
            margin-top:7px;
        }

        .message-attachment img {
            display:block;
            width:min(250px,100%);
            max-height:260px;
            object-fit:cover;
            border-radius:9px;
        }

        .message-empty {
            min-height:100%;
            display:grid;
            place-content:center;
            justify-items:center;
            text-align:center;
            color:var(--slate-500);
        }

        .message-empty span { font-size:35px; }
        .message-empty strong {
            margin-top:9px;
            color:var(--slate-700);
        }
        .message-empty p {
            max-width:360px;
            margin:5px 0 0;
            font-size:11px;
            line-height:1.55;
        }

        .composer-area {
            padding:10px 14px 12px;
            border-top:1px solid var(--slate-200);
            background:rgba(255,255,255,.97);
        }

        .reply-form {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:9px;
            align-items:end;
        }

        .reply-form textarea {
            width:100%;
            min-height:44px;
            max-height:130px;
            resize:none;
            padding:11px 13px;
            border:1px solid var(--slate-200);
            border-radius:12px;
            outline:none;
            background:var(--slate-50);
            line-height:1.45;
        }

        .reply-form textarea:focus {
            border-color:var(--green-500);
            box-shadow:0 0 0 3px rgba(16,185,129,.12);
            background:#fff;
        }

        .send-reply {
            min-width:76px;
            height:44px;
            border:0;
            border-radius:12px;
            color:#fff;
            background:var(--green-700);
            cursor:pointer;
            font-weight:850;
        }

        .send-reply:disabled { opacity:.55; cursor:wait; }

        .status-inline-form {
            margin-top:6px;
            text-align:right;
        }

        .finish-button,
        .reopen-button {
            padding:5px 8px;
            border:0;
            color:var(--slate-500);
            background:transparent;
            cursor:pointer;
            font-size:9px;
            font-weight:800;
        }

        .reopen-button {
            padding:8px 11px;
            border-radius:9px;
            color:#fff;
            background:var(--green-700);
        }

        .closed-conversation {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            color:var(--slate-600);
            font-size:11px;
        }

        .composer-error {
            display:none;
            margin-bottom:8px;
            padding:9px 10px;
            border-radius:9px;
            color:var(--red-700);
            background:var(--red-100);
            font-size:10px;
        }

        .composer-error.visible { display:block; }

        .patient-pane {
            min-width:0;
            overflow-y:auto;
            border-left:1px solid var(--slate-200);
            background:#fff;
        }

        .patient-placeholder {
            height:100%;
            display:grid;
            place-content:center;
            padding:28px;
            text-align:center;
            color:var(--slate-400);
            font-size:11px;
        }

        .patient-detail-card { padding:22px 18px; }

        .patient-profile {
            display:grid;
            justify-items:center;
            padding-bottom:20px;
            border-bottom:1px solid var(--slate-200);
            text-align:center;
        }

        .conversation-avatar.profile {
            width:68px;
            height:68px;
            font-size:25px;
        }

        .patient-profile h2 {
            margin:12px 0 3px;
            font-size:17px;
        }

        .patient-profile p {
            margin:0 0 10px;
            color:var(--slate-500);
            font-size:10px;
        }

        .patient-facts {
            display:grid;
            gap:0;
            margin:14px 0;
        }

        .patient-facts div {
            padding:11px 0;
            border-bottom:1px solid var(--slate-100);
        }

        .patient-facts dt {
            color:var(--slate-400);
            font-size:9px;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.06em;
        }

        .patient-facts dd {
            margin:4px 0 0;
            color:var(--slate-700);
            font-size:11px;
            line-height:1.45;
        }

        .privacy-note {
            padding:12px;
            border:1px solid var(--green-100);
            border-radius:11px;
            color:var(--green-900);
            background:var(--green-50);
        }

        .privacy-note strong { font-size:10px; }
        .privacy-note p {
            margin:5px 0 0;
            font-size:9px;
            line-height:1.55;
        }

        .toast-stack {
            position:fixed;
            z-index:100;
            top:76px;
            right:18px;
            display:grid;
            gap:9px;
            width:min(350px,calc(100vw - 36px));
            pointer-events:none;
        }

        .toast {
            display:grid;
            grid-template-columns:auto minmax(0,1fr) auto;
            gap:10px;
            align-items:start;
            padding:13px;
            border:1px solid var(--slate-200);
            border-radius:13px;
            background:#fff;
            box-shadow:var(--shadow);
            pointer-events:auto;
            animation:toast-in .2s ease-out;
        }

        .toast-icon {
            width:32px;
            height:32px;
            display:grid;
            place-items:center;
            border-radius:9px;
            background:var(--green-50);
        }

        .toast strong {
            display:block;
            font-size:11px;
        }

        .toast p {
            margin:3px 0 0;
            color:var(--slate-500);
            font-size:10px;
            line-height:1.45;
        }

        .toast-close {
            border:0;
            color:var(--slate-400);
            background:transparent;
            cursor:pointer;
        }

        @keyframes toast-in {
            from { opacity:0; transform:translateY(-8px); }
            to { opacity:1; transform:none; }
        }

        @media (max-width:1180px) {
            .workspace {
                grid-template-columns:340px minmax(430px,1fr);
            }

            .patient-pane {
                position:fixed;
                z-index:70;
                top:64px;
                right:0;
                bottom:0;
                width:min(330px,88vw);
                transform:translateX(100%);
                box-shadow:-20px 0 45px rgba(15,23,42,.16);
                transition:transform .2s ease;
            }

            .patient-pane.open { transform:none; }
        }

        @media (max-width:820px) {
            body { overflow:auto; }

            .admin-topbar {
                position:sticky;
                top:0;
                z-index:80;
                height:auto;
                min-height:60px;
                padding:10px 12px;
            }

            .brand-area { min-width:0; }
            .brand-area > span:last-child { display:none; }
            .topbar-nav { margin-right:auto; }
            .nav-link { padding:7px 9px; }
            .live-state span:last-child,
            .notification-toggle span,
            .admin-identity { display:none; }

            .workspace {
                height:calc(100vh - 60px);
                display:block;
                position:relative;
            }

            .sidebar,
            .conversation-pane {
                position:absolute;
                inset:0;
                width:100%;
            }

            .conversation-pane {
                z-index:5;
                transform:translateX(100%);
                transition:transform .2s ease;
            }

            .workspace.show-conversation .conversation-pane {
                transform:none;
            }

            .workspace.show-conversation .sidebar {
                visibility:hidden;
            }

            .mobile-back { display:grid; place-items:center; }
            .header-action[data-toggle-patient] { display:inline-flex; }
        }

        @media (max-width:520px) {
            .topbar-nav .nav-link span:not(.nav-count) {
                display:none;
            }

            .workspace { height:calc(100vh - 58px); }
            .sidebar-header { padding:13px 12px 10px; }
            .conversation-item { padding:12px; }
            .message-stream { padding:16px 11px; }
            .message-bubble { max-width:88%; }
            .header-action[href] { display:none; }
            .conversation-header { padding:9px 10px; }
            .conversation-heading span { max-width:180px; }
            .composer-area { padding:9px 10px; }
            .closed-conversation { align-items:flex-start; flex-direction:column; }
        }
    
        
    
        /*
        |--------------------------------------------------------------------------
        | Inbox UI V3 — balanced inbox
        |--------------------------------------------------------------------------
        |
        | Goals:
        | - sidebar more readable
        | - chat panel not cropped
        | - composer always visible
        | - detail remains as drawer
        |--------------------------------------------------------------------------
        */

        body {
            background:#f3f5f7;
        }

        .admin-topbar {
            height:58px;
            padding:0 16px;
            gap:12px;
            box-shadow:0 2px 14px rgba(5,46,40,.15);
        }

        .brand-area {
            min-width:auto;
            gap:9px;
            font-size:14px;
        }

        .brand-mark {
            width:32px;
            height:32px;
            border-radius:9px;
            font-size:14px;
        }

        .topbar-nav {
            margin-right:auto;
        }

        .nav-link {
            min-height:36px;
            padding:7px 11px;
            border-radius:9px;
            font-size:12px;
        }

        .live-state {
            padding:6px 9px;
            border-radius:9px;
        }

        .notification-toggle,
        .logout-button {
            min-height:36px;
            padding:7px 10px;
            border-radius:9px;
            font-size:11px;
        }

        .notification-toggle span {
            display:none;
        }

        .admin-identity small {
            display:none;
        }

        .workspace {
            width:min(100%,1650px);
            height:calc(100dvh - 58px);
            margin:0 auto;
            display:grid;
            grid-template-columns:minmax(340px,390px) minmax(0,1fr);
            border-left:1px solid var(--slate-200);
            border-right:1px solid var(--slate-200);
            background:#fff;
            overflow:hidden;
        }

        .sidebar {
            min-width:0;
            display:flex;
            flex-direction:column;
        }

        .sidebar-header {
            padding:14px 14px 12px;
        }

        .sidebar-title-row {
            align-items:center;
            margin-bottom:10px;
        }

        .sidebar-title-row h1 {
            font-size:18px;
            letter-spacing:-.015em;
        }

        .sidebar-title-row p,
        .sync-time {
            display:none;
        }

        .search-box {
            margin-bottom:10px;
        }

        .search-box input {
            height:40px;
            border-radius:10px;
            font-size:13px;
        }

        .filter-row {
            gap:8px;
            margin-bottom:10px;
        }

        .filter-row select {
            height:37px;
            padding:7px 9px;
            border-radius:9px;
            font-size:11px;
        }

        .state-tabs {
            gap:6px;
        }

        .state-tab {
            min-height:31px;
            padding:6px 10px;
            font-size:10px;
        }

        .conversation-item {
            grid-template-columns:44px minmax(0,1fr);
            gap:11px;
            min-height:90px;
            padding:12px 14px;
        }

        .conversation-avatar {
            width:44px;
            height:44px;
            font-size:16px;
            box-shadow:none;
        }

        .conversation-name {
            font-size:14px;
        }

        .conversation-time {
            font-size:10px;
        }

        .conversation-meta-row {
            margin:5px 0;
        }

        .state-chip,
        .type-chip {
            min-height:20px;
            padding:3px 7px;
            font-size:9px;
        }

        .conversation-preview {
            font-size:12px;
        }

        .unread-badge {
            min-width:20px;
            height:20px;
            font-size:9px;
        }

        .inbox-pagination {
            padding:8px;
        }

        .conversation-pane {
            min-width:0;
            min-height:0;
            overflow:hidden;
            display:block;
        }

        .conversation-shell {
            height:100%;
            min-height:0;
            display:flex;
            flex-direction:column;
        }

        .conversation-header {
            flex:0 0 auto;
            padding:9px 14px;
        }

        .conversation-avatar.large {
            width:40px;
            height:40px;
        }

        .conversation-heading strong {
            font-size:14px;
        }

        .conversation-heading span {
            font-size:10px;
        }

        .header-action {
            min-height:34px;
            padding:7px 10px;
            border-radius:9px;
            font-size:10px;
        }

        .message-stream {
            flex:1 1 auto;
            min-height:0;
            overflow-y:auto;
            padding:18px clamp(14px,3vw,36px);
        }

        .message-bubble {
            max-width:min(74%,660px);
            margin-bottom:9px;
            padding:9px 11px 7px;
            border-radius:12px;
            box-shadow:0 3px 10px rgba(15,23,42,.06);
        }

        .message-bubble p {
            font-size:13px;
            line-height:1.5;
        }

        .composer-area {
            flex:0 0 auto;
            padding:10px 14px 12px;
            border-top:1px solid var(--slate-200);
            background:rgba(255,255,255,.98);
        }

        .reply-form {
            gap:9px;
            align-items:end;
        }

        .reply-form textarea {
            min-height:46px;
            max-height:120px;
            padding:11px 12px;
            border-radius:11px;
            font-size:13px;
        }

        .send-reply {
            min-width:78px;
            height:46px;
            border-radius:11px;
            font-size:12px;
        }

        .status-inline-form {
            margin-top:7px;
        }

        .patient-pane {
            position:fixed;
            z-index:90;
            top:58px;
            right:0;
            bottom:0;
            width:min(360px,94vw);
            min-width:0;
            overflow-y:auto;
            border-left:1px solid var(--slate-200);
            background:#fff;
            box-shadow:-18px 0 42px rgba(15,23,42,.16);
            transform:translateX(102%);
            transition:transform .2s ease;
        }

        .patient-pane.open {
            transform:none;
        }

        .patient-detail-card {
            padding:18px 16px;
        }

        .patient-profile {
            grid-template-columns:52px minmax(0,1fr);
            column-gap:12px;
            justify-items:start;
            padding-bottom:15px;
            text-align:left;
        }

        .conversation-avatar.profile {
            grid-row:1 / 4;
            width:52px;
            height:52px;
            font-size:19px;
        }

        .patient-profile h2 {
            grid-column:2;
            margin:1px 0 2px;
            font-size:15px;
        }

        .patient-profile p {
            grid-column:2;
            margin:0 0 7px;
        }

        .patient-profile .state-chip {
            grid-column:2;
        }

        .patient-facts {
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:8px;
            margin:12px 0;
        }

        .patient-facts div {
            min-width:0;
            padding:9px;
            border:1px solid var(--slate-100);
            border-radius:9px;
            background:var(--slate-50);
        }

        .patient-facts dt {
            font-size:8px;
        }

        .patient-facts dd {
            margin-top:3px;
            font-size:10px;
            overflow-wrap:anywhere;
        }

        .privacy-note {
            padding:10px;
            border-radius:9px;
        }

        .toast-stack {
            top:68px;
            right:12px;
            width:min(320px,calc(100vw - 24px));
        }

        .toast {
            padding:11px;
            border-radius:11px;
        }

        @media (max-width:1180px) {
            .workspace {
                grid-template-columns:minmax(320px,350px) minmax(0,1fr);
            }
        }

        @media (max-width:820px) {
            body {
                overflow:hidden;
            }

            .admin-topbar {
                position:sticky;
                top:0;
                z-index:80;
                height:54px;
                min-height:54px;
                padding:0 10px;
            }

            .brand-area > span:last-child {
                display:none;
            }

            .topbar-actions {
                gap:6px;
            }

            .admin-identity,
            .live-state span:last-child {
                display:none;
            }

            .live-state {
                width:30px;
                height:30px;
                justify-content:center;
                padding:0;
            }

            .notification-toggle,
            .logout-button {
                width:32px;
                height:32px;
                min-height:32px;
                padding:0;
                display:grid;
                place-items:center;
                overflow:hidden;
            }

            .logout-button {
                font-size:0;
            }

            .logout-button::after {
                content:"↪";
                font-size:14px;
            }

            .workspace {
                width:100%;
                height:calc(100dvh - 54px);
                display:block;
                position:relative;
                border:0;
            }

            .sidebar,
            .conversation-pane {
                position:absolute;
                inset:0;
                width:100%;
                height:100%;
            }

            .conversation-pane {
                z-index:5;
                transform:translateX(100%);
                transition:transform .2s ease;
            }

            .workspace.show-conversation .conversation-pane {
                transform:none;
            }

            .workspace.show-conversation .sidebar {
                visibility:hidden;
            }

            .mobile-back {
                display:grid;
                place-items:center;
            }

            .message-bubble {
                max-width:88%;
            }

            .patient-pane {
                top:54px;
            }
        }

        @media (max-width:560px) {
            .nav-link {
                padding:6px 7px;
                font-size:10px;
            }

            .sidebar-header {
                padding:11px 10px 10px;
            }

            .filter-row {
                grid-template-columns:1fr 1fr;
            }

            .conversation-item {
                min-height:82px;
                padding:11px 10px;
            }

            .conversation-name {
                font-size:13px;
            }

            .conversation-preview {
                font-size:11px;
            }

            .conversation-header {
                padding:8px 10px;
            }

            .message-stream {
                padding:14px 10px;
            }

            .composer-area {
                padding:9px 10px 10px;
            }

            .reply-form {
                grid-template-columns:minmax(0,1fr);
            }

            .send-reply {
                width:100%;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | Hotfix — sidebar scroll + visible composer
        |--------------------------------------------------------------------------
        |
        | Fixes:
        | - left inbox column can scroll independently
        | - chat composer/footer always visible
        | - message area uses remaining height only
        |--------------------------------------------------------------------------
        */

        .workspace {
            overflow: hidden !important;
        }

        .sidebar {
            height: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .sidebar-header {
            flex: 0 0 auto !important;
        }

        .conversation-list {
            flex: 1 1 auto !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch;
        }

        .pagination-slot {
            flex: 0 0 auto !important;
        }

        .conversation-pane {
            height: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .conversation-shell {
            flex: 1 1 auto !important;
            height: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
            display: grid !important;
            grid-template-rows: auto minmax(0, 1fr) auto !important;
        }

        .conversation-header {
            flex: 0 0 auto !important;
        }

        .message-stream {
            min-height: 0 !important;
            height: auto !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            flex: initial !important;
        }

        .composer-area {
            position: relative !important;
            display: block !important;
            flex: 0 0 auto !important;
            z-index: 3 !important;
            box-shadow: 0 -6px 16px rgba(15, 23, 42, 0.05);
            background: rgba(255,255,255,.98) !important;
        }

        .reply-form {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) auto !important;
            align-items: end !important;
        }

        .reply-form textarea {
            width: 100% !important;
            min-height: 46px !important;
            max-height: 120px !important;
        }

        .send-reply {
            align-self: end !important;
        }

        @media (max-width: 560px) {
            .reply-form {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .send-reply {
                width: 100% !important;
            }
        }

    
        /*
        |--------------------------------------------------------------------------
        | Admin image attachment composer
        |--------------------------------------------------------------------------
        */

        .reply-form {
            grid-template-columns:auto minmax(0,1fr) auto !important;
        }

        .image-picker {
            width:46px;
            height:46px;
            display:grid;
            place-items:center;
            align-self:end;
            border:1px solid var(--slate-200);
            border-radius:11px;
            color:var(--slate-600);
            background:var(--slate-50);
            cursor:pointer;
            font-size:18px;
            transition:
                border-color .15s ease,
                background .15s ease,
                color .15s ease;
        }

        .image-picker:hover {
            border-color:var(--green-500);
            color:var(--green-800);
            background:var(--green-50);
        }

        .image-picker input {
            position:absolute;
            width:1px;
            height:1px;
            overflow:hidden;
            clip:rect(0,0,0,0);
            white-space:nowrap;
            clip-path:inset(50%);
        }

        .composer-input-stack {
            min-width:0;
            display:grid;
            gap:7px;
        }

        .selected-image {
            min-width:0;
            display:grid;
            grid-template-columns:42px minmax(0,1fr) 28px;
            gap:9px;
            align-items:center;
            padding:7px 8px;
            border:1px solid var(--green-100);
            border-radius:10px;
            color:var(--green-950);
            background:var(--green-50);
        }

        .selected-image[hidden] {
            display:none !important;
        }

        .selected-image img {
            width:42px;
            height:42px;
            object-fit:cover;
            border-radius:8px;
            background:#fff;
        }

        .selected-image strong,
        .selected-image small {
            display:block;
            min-width:0;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }

        .selected-image strong {
            font-size:10px;
        }

        .selected-image small {
            margin-top:3px;
            color:var(--slate-500);
            font-size:8px;
        }

        .selected-image button {
            width:28px;
            height:28px;
            display:grid;
            place-items:center;
            border:0;
            border-radius:8px;
            color:var(--red-700);
            background:var(--red-100);
            cursor:pointer;
            font-size:18px;
            line-height:1;
        }

        @media (max-width:560px) {
            .reply-form {
                grid-template-columns:42px minmax(0,1fr) !important;
            }

            .image-picker {
                width:42px;
                height:42px;
            }

            .send-reply {
                grid-column:1 / -1;
            }
        }

    </style>
</head>
<body>
    <header class="admin-topbar">
        <a class="brand-area" href="{{ route('admin.inbox') }}">
            <span class="brand-mark">✚</span>
            <span>MD Farma Admin</span>
        </a>

        <nav class="topbar-nav" aria-label="Navigasi admin">
            <a class="nav-link active" href="{{ route('admin.inbox') }}">
                <span>💬 Inbox</span>
                <span
                    class="nav-count"
                    id="topUnreadCount"
                    style="{{
                        $counts['unreadMessages'] > 0
                            ? ''
                            : 'display:none'
                    }}"
                >
                    {{ $counts['unreadMessages'] }}
                </span>
            </a>

            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span>📊 Analitik</span>
            </a>
        </nav>

        <div class="topbar-actions">
            <div class="live-state" id="inboxConnection">
                <span class="live-dot"></span>
                <span data-live-text>Menghubungkan realtime</span>
            </div>

            <button
                class="notification-toggle"
                id="notificationToggle"
                type="button"
                title="Aktifkan notifikasi browser dan suara"
            >
                🔔 <span>Notifikasi</span>
            </button>

            <div class="admin-identity">
                <strong>{{ auth('admin')->user()->username }}</strong>
                <small>Administrator</small>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="logout-button" type="submit">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="workspace" id="workspace">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title-row">
                    <div>
                        <h1>Inbox Konsultasi</h1>
                        <p>
                            Prioritaskan chat yang menunggu balasan.
                        </p>
                    </div>
                    <span class="sync-time" id="syncTime">
                        Sinkron realtime
                    </span>
                </div>

                <form method="GET" action="{{ route('admin.inbox') }}" data-filter-form>
                    <div class="search-box">
                        <span>⌕</span>
                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama, nomor HP, atau pesan..."
                        >
                    </div>

                    <input type="hidden" name="state" value="{{ $state }}">

                    <div class="filter-row">
                        <select name="type" aria-label="Filter jenis konsultasi">
                            <option value="">Semua jenis</option>
                            <option value="resep" @selected($type === 'resep')>
                                Resep dokter
                            </option>
                            <option value="non_resep" @selected($type === 'non_resep')>
                                Non resep
                            </option>
                        </select>

                        <select name="sort" aria-label="Urutan percakapan">
                            <option value="latest" @selected($sort === 'latest')>
                                Aktivitas terbaru
                            </option>
                            <option value="waiting_oldest" @selected($sort === 'waiting_oldest')>
                                Menunggu terlama
                            </option>
                            <option value="oldest" @selected($sort === 'oldest')>
                                Aktivitas terlama
                            </option>
                        </select>
                    </div>

                    <noscript>
                        <button type="submit">Terapkan filter</button>
                    </noscript>
                </form>

                @php
                    $stateOptions = [
                        'all' => ['Semua', $counts['total']],
                        'unread' => ['Belum dibaca', $counts['unreadConversations']],
                        'waiting_admin' => ['Belum dibalas', $counts['waitingAdmin']],
                        'new' => ['Baru', $counts['new']],
                        'completed' => ['Selesai', $counts['completed']],
                    ];
                @endphp

                <div class="state-tabs">
                    @foreach ($stateOptions as $stateKey => [$label, $total])
                        <a
                            class="state-tab {{
                                $state === $stateKey
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route(
                                    'admin.inbox',
                                    array_filter([
                                        'state' => $stateKey,
                                        'search' => $search,
                                        'type' => $type,
                                        'sort' => $sort,
                                    ], fn ($value) => $value !== '')
                                )
                            }}"
                        >
                            {{ $label }}
                            <span
                                class="tab-count"
                                data-count-key="{{ $stateKey }}"
                            >{{ $total }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="conversation-list" id="conversationList">
                @include(
                    'admin.inbox.partials.conversation-list',
                    [
                        'consultations' => $consultations,
                        'activePublicId' => $activePublicId,
                    ]
                )
            </div>

            <div class="pagination-slot" id="inboxPagination">
                @include(
                    'admin.inbox.partials.pagination',
                    ['consultations' => $consultations]
                )
            </div>
        </aside>

        <section class="conversation-pane" id="conversationPane">
            @if ($selectedConsultation)
                @include(
                    'admin.inbox.partials.conversation-panel',
                    [
                        'consultation' => $selectedConsultation,
                        'timezone' => $timezone,
                    ]
                )
            @else
                <div class="conversation-placeholder">
                    <span class="placeholder-icon">💬</span>
                    <h2>Pilih percakapan</h2>
                    <p>
                        Daftar chat diurutkan berdasarkan aktivitas terbaru.
                        Gunakan filter “Belum dibalas” untuk menangani pasien
                        yang masih menunggu respons admin.
                    </p>
                </div>
            @endif
        </section>

        <aside class="patient-pane" id="patientPane">
            @if ($selectedConsultation)
                @include(
                    'admin.inbox.partials.patient-panel',
                    [
                        'consultation' => $selectedConsultation,
                        'timezone' => $timezone,
                    ]
                )
            @else
                <div class="patient-placeholder">
                    Detail pasien akan ditampilkan setelah admin membuka
                    salah satu percakapan.
                </div>
            @endif
        </aside>
    </main>

    <div class="toast-stack" id="toastStack"></div>

    <script>
        (() => {
            const workspace = document.getElementById('workspace');
            const listElement = document.getElementById('conversationList');
            const paginationElement = document.getElementById('inboxPagination');
            const conversationPane = document.getElementById('conversationPane');
            const patientPane = document.getElementById('patientPane');
            const connectionElement = document.getElementById('inboxConnection');
            const syncTime = document.getElementById('syncTime');
            const notificationToggle = document.getElementById('notificationToggle');
            const topUnreadCount = document.getElementById('topUnreadCount');
            const toastStack = document.getElementById('toastStack');
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .content;

            const liveUrl = @json(route('admin.inbox.live'));
            const timezone = @json($timezone);
            let activePublicId = @json($activePublicId ?: null);
            let activeChannelName = null;
            let refreshTimer = null;
            let readTimer = null;
            let inboxInitialized = false;
            let notificationEnabled =
                localStorage.getItem('md-farma-admin-notifications')
                === 'enabled';

            function setConnection(state, text) {
                connectionElement.classList.remove(
                    'connected',
                    'disconnected'
                );

                if (state) {
                    connectionElement.classList.add(state);
                }

                connectionElement
                    .querySelector('[data-live-text]')
                    .textContent = text;
            }

            function showToast(title, body, icon = '💬') {
                const toast = document.createElement('div');
                toast.className = 'toast';

                const iconElement = document.createElement('span');
                iconElement.className = 'toast-icon';
                iconElement.textContent = icon;

                const copy = document.createElement('div');
                const heading = document.createElement('strong');
                const paragraph = document.createElement('p');
                heading.textContent = title;
                paragraph.textContent = body;
                copy.append(heading, paragraph);

                const close = document.createElement('button');
                close.className = 'toast-close';
                close.type = 'button';
                close.textContent = '×';
                close.addEventListener('click', () => toast.remove());

                toast.append(iconElement, copy, close);
                toastStack.appendChild(toast);

                window.setTimeout(() => {
                    toast.remove();
                }, 6000);
            }

            function playNotificationSound() {
                if (!notificationEnabled) {
                    return;
                }

                try {
                    const AudioContextClass =
                        window.AudioContext
                        || window.webkitAudioContext;
                    const context = new AudioContextClass();
                    const oscillator = context.createOscillator();
                    const gain = context.createGain();

                    oscillator.frequency.value = 720;
                    gain.gain.setValueAtTime(.0001, context.currentTime);
                    gain.gain.exponentialRampToValueAtTime(
                        .12,
                        context.currentTime + .015
                    );
                    gain.gain.exponentialRampToValueAtTime(
                        .0001,
                        context.currentTime + .22
                    );

                    oscillator.connect(gain);
                    gain.connect(context.destination);
                    oscillator.start();
                    oscillator.stop(context.currentTime + .24);
                } catch (error) {
                    console.debug('Audio notification unavailable.', error);
                }
            }

            function sendBrowserNotification(title, body) {
                if (
                    !notificationEnabled
                    || !('Notification' in window)
                    || Notification.permission !== 'granted'
                ) {
                    return;
                }

                new Notification(title, {
                    body,
                    tag: 'md-farma-inbox',
                    renotify: true,
                });
            }

            async function enableNotifications() {
                if (!('Notification' in window)) {
                    showToast(
                        'Notifikasi tidak tersedia',
                        'Browser ini tidak mendukung notifikasi sistem.',
                        '⚠️'
                    );
                    return;
                }

                const permission = await Notification.requestPermission();

                if (permission === 'granted') {
                    notificationEnabled = true;
                    localStorage.setItem(
                        'md-farma-admin-notifications',
                        'enabled'
                    );
                    notificationToggle.title = 'Notifikasi aktif';
                    showToast(
                        'Notifikasi aktif',
                        'Admin akan menerima pemberitahuan konsultasi dan pesan baru.',
                        '🔔'
                    );
                    playNotificationSound();
                } else {
                    notificationEnabled = false;
                    localStorage.removeItem('md-farma-admin-notifications');
                    showToast(
                        'Izin notifikasi tidak diberikan',
                        'Notifikasi toast di halaman tetap aktif.',
                        '⚠️'
                    );
                }
            }

            notificationToggle.addEventListener(
                'click',
                enableNotifications
            );

            const filterForm = document.querySelector(
                '[data-filter-form]'
            );

            filterForm
                ?.querySelectorAll('select')
                .forEach((select) => {
                    select.addEventListener('change', () => {
                        filterForm.requestSubmit();
                    });
                });

            function updateCounts(counts) {
                const mapping = {
                    all: counts.total,
                    unread: counts.unreadConversations,
                    waiting_admin: counts.waitingAdmin,
                    new: counts.new,
                    completed: counts.completed,
                };

                Object.entries(mapping).forEach(([key, value]) => {
                    document
                        .querySelectorAll(`[data-count-key="${key}"]`)
                        .forEach((element) => {
                            element.textContent = value;
                        });
                });

                topUnreadCount.textContent = counts.unreadMessages;
                topUnreadCount.style.display =
                    counts.unreadMessages > 0 ? 'grid' : 'none';
            }

            function currentFilterParameters() {
                const current = new URL(window.location.href);
                const parameters = new URLSearchParams(current.search);
                parameters.delete('inbox_page');

                if (activePublicId) {
                    parameters.set('active', activePublicId);
                }

                return parameters;
            }

            async function refreshList() {
                const url = new URL(liveUrl, window.location.origin);
                url.search = currentFilterParameters().toString();

                try {
                    const response = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Daftar percakapan gagal disinkronkan.');
                    }

                    const data = await response.json();
                    listElement.innerHTML = data.listHtml;
                    paginationElement.innerHTML = data.paginationHtml;
                    updateCounts(data.counts);
                    syncTime.textContent = `Sinkron ${data.syncedAt}`;
                } catch (error) {
                    syncTime.textContent = 'Sinkronisasi tertunda';
                    console.error(error);
                }
            }

            function scheduleListRefresh(delay = 180) {
                window.clearTimeout(refreshTimer);
                refreshTimer = window.setTimeout(refreshList, delay);
            }

            function setActiveListItem(publicId) {
                listElement
                    .querySelectorAll('[data-conversation-link]')
                    .forEach((link) => {
                        link.classList.toggle(
                            'active',
                            link.dataset.publicId === publicId
                        );
                    });
            }

            async function loadConversation(link, pushHistory = true) {
                const fragmentUrl = link.dataset.fragmentUrl;

                try {
                    conversationPane.style.opacity = '.55';

                    const response = await fetch(fragmentUrl, {
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Percakapan tidak dapat dibuka.');
                    }

                    const data = await response.json();
                    activePublicId = data.publicId;
                    conversationPane.innerHTML = data.conversationHtml;
                    patientPane.innerHTML = data.patientHtml;
                    document.title = data.pageTitle;
                    conversationPane.style.opacity = '1';
                    setActiveListItem(activePublicId);
                    updateCounts(data.readState);
                    bindConversationControls();
                    subscribeActiveConversation();
                    scrollMessagesToBottom();
                    workspace.classList.add('show-conversation');

                    if (pushHistory) {
                        window.history.pushState(
                            { publicId: activePublicId },
                            '',
                            link.href
                        );
                    }

                    scheduleListRefresh(50);
                } catch (error) {
                    conversationPane.style.opacity = '1';
                    showToast(
                        'Gagal membuka chat',
                        error.message,
                        '⚠️'
                    );
                }
            }

            listElement.addEventListener('click', (event) => {
                const link = event.target.closest('[data-conversation-link]');

                if (
                    !link
                    || event.ctrlKey
                    || event.metaKey
                    || event.shiftKey
                    || event.altKey
                ) {
                    return;
                }

                event.preventDefault();
                loadConversation(link);
            });

            window.addEventListener('popstate', () => {
                window.location.reload();
            });

            function formatMessageDate(isoDate) {
                const date = new Date(isoDate);
                return new Intl.DateTimeFormat('id-ID', {
                    timeZone: timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                }).format(date);
            }

            function messageDateKey(isoDate) {
                const parts = new Intl.DateTimeFormat('en-CA', {
                    timeZone: timezone,
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }).formatToParts(new Date(isoDate));

                const values = Object.fromEntries(
                    parts.map((part) => [part.type, part.value])
                );

                return `${values.year}-${values.month}-${values.day}`;
            }

            function formatMessageTime(isoDate) {
                return new Intl.DateTimeFormat('id-ID', {
                    timeZone: timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                }).format(new Date(isoDate)).replace(':', '.');
            }

            function appendMessage(data) {
                const stream = document.getElementById('messageStream');

                if (!stream || !data?.id) {
                    return;
                }

                if (stream.querySelector(`[data-message-id="${data.id}"]`)) {
                    return;
                }

                stream.querySelector('[data-empty-message]')?.remove();

                const dateKey = messageDateKey(data.created_at);

                if (stream.dataset.lastDate !== dateKey) {
                    const divider = document.createElement('div');
                    divider.className = 'date-divider';
                    divider.dataset.dateKey = dateKey;

                    const label = document.createElement('span');
                    label.textContent = formatMessageDate(data.created_at);
                    divider.appendChild(label);
                    stream.appendChild(divider);
                    stream.dataset.lastDate = dateKey;
                }

                const bubble = document.createElement('article');
                bubble.className = `message-bubble ${
                    data.sender === 'admin' ? 'admin' : 'patient'
                }`;
                bubble.dataset.messageId = data.id;

                const sender = document.createElement('span');
                sender.className = 'message-sender';
                sender.textContent =
                    data.sender === 'admin' ? 'Apoteker' : 'Pasien';
                bubble.appendChild(sender);

                if (data.message) {
                    const paragraph = document.createElement('p');
                    paragraph.textContent = data.message;
                    bubble.appendChild(paragraph);
                }

                if (data.attachment_url) {
                    const link = document.createElement('a');
                    link.className = 'message-attachment';
                    link.href = data.attachment_url;
                    link.target = '_blank';
                    link.rel = 'noopener';

                    const image = document.createElement('img');
                    image.src = data.attachment_url;
                    image.alt = 'Lampiran konsultasi';
                    image.loading = 'lazy';
                    link.appendChild(image);
                    bubble.appendChild(link);
                }

                const time = document.createElement('time');
                time.className = 'message-time';
                time.dateTime = data.created_at;
                time.textContent = `${formatMessageTime(data.created_at)} WIB`;
                bubble.appendChild(time);
                stream.appendChild(bubble);
                scrollMessagesToBottom();

                if (data.sender === 'user') {
                    scheduleMarkRead();
                }
            }

            function scrollMessagesToBottom() {
                const stream = document.getElementById('messageStream');

                if (stream) {
                    stream.scrollTop = stream.scrollHeight;
                }
            }

            async function markActiveRead() {
                const shell = document.querySelector('[data-active-conversation]');

                if (!shell) {
                    return;
                }

                try {
                    const response = await fetch(shell.dataset.readUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateCounts(data.counts);
                        scheduleListRefresh(80);
                    }
                } catch (error) {
                    console.debug('Read state sync failed.', error);
                }
            }

            function scheduleMarkRead() {
                window.clearTimeout(readTimer);
                readTimer = window.setTimeout(markActiveRead, 260);
            }

            function showComposerError(message) {
                const errorElement = document.querySelector('[data-composer-error]');

                if (!errorElement) {
                    return;
                }

                errorElement.textContent = message;
                errorElement.classList.add('visible');
            }

            function clearComposerError() {
                const errorElement = document.querySelector('[data-composer-error]');

                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.classList.remove('visible');
                }
            }

            function bindConversationControls() {
                const backButton = document.querySelector('[data-mobile-back]');
                const detailButton = document.querySelector('[data-toggle-patient]');
                const replyForm = document.querySelector('[data-reply-form]');
                const replyInput = document.querySelector('[data-reply-input]');
                const replyImage = document.querySelector('[data-reply-image]');
                const imagePreview = document.querySelector('[data-image-preview]');
                const imagePreviewSrc = document.querySelector(
                    '[data-image-preview-src]'
                );
                const imageName = document.querySelector('[data-image-name]');
                const removeImageButton = document.querySelector(
                    '[data-remove-image]'
                );
                let imageObjectUrl = null;

                backButton?.addEventListener('click', () => {
                    workspace.classList.remove('show-conversation');
                });

                detailButton?.addEventListener('click', () => {
                    patientPane.classList.toggle('open');
                });


                function clearSelectedImage() {
                    if (imageObjectUrl) {
                        URL.revokeObjectURL(imageObjectUrl);
                        imageObjectUrl = null;
                    }

                    if (replyImage) {
                        replyImage.value = '';
                    }

                    if (imagePreviewSrc) {
                        imagePreviewSrc.src = '';
                    }

                    if (imageName) {
                        imageName.textContent = '';
                    }

                    if (imagePreview) {
                        imagePreview.hidden = true;
                    }
                }

                replyImage?.addEventListener('change', () => {
                    clearComposerError();

                    const file = replyImage.files?.[0];

                    if (!file) {
                        clearSelectedImage();
                        return;
                    }

                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ];

                    if (!allowedTypes.includes(file.type)) {
                        clearSelectedImage();
                        showComposerError(
                            'Format gambar harus JPG, PNG, atau WebP.'
                        );
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        clearSelectedImage();
                        showComposerError(
                            'Ukuran gambar maksimal 2 MB.'
                        );
                        return;
                    }

                    if (imageObjectUrl) {
                        URL.revokeObjectURL(imageObjectUrl);
                    }

                    imageObjectUrl = URL.createObjectURL(file);

                    if (imagePreviewSrc) {
                        imagePreviewSrc.src = imageObjectUrl;
                    }

                    if (imageName) {
                        imageName.textContent = file.name;
                    }

                    if (imagePreview) {
                        imagePreview.hidden = false;
                    }
                });

                removeImageButton?.addEventListener(
                    'click',
                    clearSelectedImage
                );

                replyInput?.addEventListener('input', () => {
                    replyInput.style.height = 'auto';
                    replyInput.style.height = `${Math.min(
                        replyInput.scrollHeight,
                        130
                    )}px`;
                });

                replyInput?.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();
                        replyForm?.requestSubmit();
                    }
                });

                replyForm?.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    clearComposerError();

                    const hasMessage =
                        replyInput?.value.trim().length > 0;
                    const hasImage =
                        (replyImage?.files?.length ?? 0) > 0;

                    if (!hasMessage && !hasImage) {
                        showComposerError(
                            'Tulis pesan atau pilih gambar terlebih dahulu.'
                        );
                        replyInput?.focus();
                        return;
                    }

                    const sendButton = replyForm.querySelector('[data-send-button]');
                    sendButton.disabled = true;

                    try {
                        const response = await fetch(replyForm.action, {
                            method: 'POST',
                            body: new FormData(replyForm),
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
                                ?? 'Balasan gagal dikirim.'
                            );
                        }

                        appendMessage(result.message);
                        replyForm.reset();
                        clearSelectedImage();

                        if (replyInput) {
                            replyInput.style.height = 'auto';
                        }

                        scheduleListRefresh(70);
                    } catch (error) {
                        showComposerError(error.message);
                    } finally {
                        sendButton.disabled = false;
                        replyInput?.focus();
                    }
                });

                document
                    .querySelectorAll('[data-status-form]')
                    .forEach((form) => {
                        form.addEventListener('submit', async (event) => {
                            event.preventDefault();

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
                                    throw new Error(
                                        result.message
                                        ?? 'Status gagal diperbarui.'
                                    );
                                }

                                showToast(
                                    'Status diperbarui',
                                    result.message,
                                    '✓'
                                );

                                const activeLink = listElement.querySelector(
                                    `[data-public-id="${activePublicId}"]`
                                );

                                if (activeLink) {
                                    await loadConversation(activeLink, false);
                                } else {
                                    scheduleListRefresh(40);
                                }
                            } catch (error) {
                                showToast(
                                    'Gagal memperbarui status',
                                    error.message,
                                    '⚠️'
                                );
                            }
                        });
                    });

                scrollMessagesToBottom();
                scheduleMarkRead();
            }

            function subscribeActiveConversation() {
                if (!window.Echo) {
                    return;
                }

                if (activeChannelName) {
                    window.Echo.leave(activeChannelName);
                }

                if (!activePublicId) {
                    activeChannelName = null;
                    return;
                }

                activeChannelName = `consultation.${activePublicId}`;

                window.Echo
                    .private(activeChannelName)
                    .listen('.message.sent', (message) => {
                        appendMessage(message);
                        scheduleListRefresh(80);
                    });
            }

            function handleInboxActivity(event) {
                const sameConversation =
                    activePublicId
                    && event.consultation?.public_id === activePublicId;

                scheduleListRefresh();

                if (
                    event.notification?.should_notify
                    && (!sameConversation || document.hidden)
                ) {
                    showToast(
                        event.notification.title,
                        event.notification.body,
                        event.activity_type === 'consultation_created'
                            ? '🆕'
                            : '💬'
                    );

                    playNotificationSound();
                    sendBrowserNotification(
                        event.notification.title,
                        event.notification.body
                    );
                }
            }

            function initializeRealtime() {
                if (inboxInitialized || !window.Echo) {
                    return;
                }

                inboxInitialized = true;

                window.Echo
                    .private('admin.inbox')
                    .listen('.inbox.activity', handleInboxActivity);

                const connection = window.Echo
                    .connector
                    ?.pusher
                    ?.connection;

                connection?.bind('connected', () => {
                    setConnection('connected', 'Realtime terhubung');
                });

                connection?.bind('disconnected', () => {
                    setConnection('disconnected', 'Realtime terputus');
                });

                connection?.bind('unavailable', () => {
                    setConnection('disconnected', 'Realtime tidak tersedia');
                });

                connection?.bind('error', () => {
                    setConnection('disconnected', 'Koneksi bermasalah');
                });

                subscribeActiveConversation();
            }

            if (window.Echo) {
                initializeRealtime();
            } else {
                window.addEventListener(
                    'md-farma:echo-ready',
                    initializeRealtime,
                    { once: true }
                );
            }

            bindConversationControls();

            if (activePublicId) {
                workspace.classList.add('show-conversation');
            }

            document.addEventListener('click', (event) => {
                if (
                    patientPane.classList.contains('open')
                    && !patientPane.contains(event.target)
                    && !event.target.closest('[data-toggle-patient]')
                ) {
                    patientPane.classList.remove('open');
                }
            });

            window.addEventListener('beforeunload', () => {
                window.Echo?.leave('admin.inbox');

                if (activeChannelName) {
                    window.Echo?.leave(activeChannelName);
                }
            });
        })();
    </script>
</body>
</html>
