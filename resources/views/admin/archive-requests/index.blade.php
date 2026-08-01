<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Salinan Arsip — MD Farma</title>
    <style>
        :root {
            --green-950:#1f2937; --green-900:#172554;
            --green-800:#1e3a8a; --green-700:#1238cc;
            --green-100:#dbeafe; --green-50:#eff6ff;
            --amber-800:#92400e; --amber-100:#fef3c7;
            --blue-800:#1e40af; --blue-100:#dbeafe;
            --red-800:#991b1b; --red-100:#fee2e2;
            --slate-950:#0f172a; --slate-800:#1e293b;
            --slate-700:#334155; --slate-600:#475569;
            --slate-500:#64748b; --slate-300:#cbd5e1;
            --slate-200:#e2e8f0; --slate-100:#f1f5f9;
            --slate-50:#f8fafc; --white:#fff;
        }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; color:var(--slate-950); background:var(--slate-50); }
        a { text-decoration:none; }
        button,input,select { font:inherit; }
        .topbar { position:sticky; top:0; z-index:30; display:flex; align-items:center; justify-content:space-between; gap:18px; min-height:64px; padding:0 clamp(18px,4vw,54px); color:#fff; background:linear-gradient(135deg,var(--green-950),var(--green-800)); box-shadow:0 8px 24px rgba(23, 37, 84, .18); }
        .brand,.nav,.actions { display:flex; align-items:center; gap:10px; }
        .brand { color:#fff; font-weight:900; }
        .brand-mark { width:38px; height:38px; display:grid; place-items:center; border:1px solid rgba(255,255,255,.2); border-radius:12px; background:rgba(255,255,255,.12); }
        .nav { gap:6px; }
        .nav a { min-height:38px; display:flex; align-items:center; padding:8px 12px; border-radius:10px; color:rgba(255,255,255,.78); font-size:13px; font-weight:800; }
        .nav a:hover,.nav a.active { color:#fff; background:rgba(255,255,255,.14); }
        .logout { min-height:36px; padding:7px 11px; border:1px solid rgba(255,255,255,.18); border-radius:10px; color:#fff; background:rgba(255,255,255,.08); cursor:pointer; }
        .page { width:min(1180px,94%); margin:30px auto 70px; }
        .hero { display:flex; align-items:end; justify-content:space-between; gap:24px; padding:28px; border-radius:22px; color:#fff; background:linear-gradient(145deg,var(--green-800),var(--green-950)); box-shadow:0 20px 55px rgba(23, 37, 84, .16); }
        .eyebrow { margin:0 0 7px; color:#bfdbfe; font-size:11px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
        h1 { margin:0; font-size:clamp(28px,4vw,42px); letter-spacing:-.04em; }
        .hero p:last-child { max-width:680px; margin:12px 0 0; color:#dbeafe; line-height:1.65; }
        .summary { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:12px; margin-top:18px; }
        .summary-card { padding:17px; border:1px solid var(--slate-200); border-radius:16px; background:#fff; box-shadow:0 10px 30px rgba(15,23,42,.05); }
        .summary-card span { display:block; color:var(--slate-500); font-size:11px; font-weight:800; }
        .summary-card strong { display:block; margin-top:5px; font-size:25px; }
        .panel { margin-top:18px; overflow:hidden; border:1px solid var(--slate-200); border-radius:20px; background:#fff; box-shadow:0 14px 40px rgba(15,23,42,.06); }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:17px; border-bottom:1px solid var(--slate-100); flex-wrap:wrap; }
        .filters { display:flex; gap:7px; flex-wrap:wrap; }
        .filter { min-height:36px; display:inline-flex; align-items:center; padding:7px 11px; border:1px solid var(--slate-200); border-radius:999px; color:var(--slate-600); background:#fff; font-size:12px; font-weight:850; }
        .filter.active { border-color:var(--green-700); color:var(--green-800); background:var(--green-50); }
        .search { display:flex; gap:8px; }
        .search input { min-height:40px; width:min(310px,65vw); padding:0 12px; border:1px solid var(--slate-300); border-radius:11px; outline:none; }
        .search input:focus { border-color:var(--green-700); box-shadow:0 0 0 3px rgba(18, 56, 204, .12); }
        .search button { min-height:40px; padding:0 14px; border:0; border-radius:11px; color:#fff; background:var(--green-700); font-weight:850; cursor:pointer; }
        .notice { margin:18px 0 0; padding:13px 15px; border:1px solid var(--green-100); border-radius:12px; color:var(--green-900); background:var(--green-50); }
        .request-list { display:grid; }
        .request-row { display:grid; grid-template-columns:minmax(0,1.5fr) minmax(170px,.7fr) minmax(170px,.7fr) auto; gap:18px; align-items:center; padding:18px; border-bottom:1px solid var(--slate-100); }
        .request-row:last-child { border-bottom:0; }
        .request-main strong { display:block; font-size:15px; }
        .request-main small,.meta small { display:block; margin-top:5px; color:var(--slate-500); line-height:1.45; }
        .request-id { color:var(--green-700); font-size:11px; font-weight:900; letter-spacing:.04em; }
        .status { display:inline-flex; align-items:center; padding:6px 9px; border-radius:999px; font-size:11px; font-weight:900; }
        .status.pending { color:var(--amber-800); background:var(--amber-100); }
        .status.verifying { color:var(--blue-800); background:var(--blue-100); }
        .status.approved { color:var(--green-900); background:var(--green-100); }
        .status.rejected { color:var(--red-800); background:var(--red-100); }
        .status.completed { color:var(--slate-700); background:var(--slate-100); }
        .review { min-height:40px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border-radius:11px; color:#fff; background:var(--green-700); font-size:12px; font-weight:900; }
        .empty { padding:52px 22px; text-align:center; color:var(--slate-500); }
        .pagination { display:flex; align-items:center; justify-content:center; gap:10px; padding:16px; border-top:1px solid var(--slate-100); }
        .pagination a,.pagination span { padding:8px 11px; border:1px solid var(--slate-200); border-radius:9px; color:var(--slate-700); font-size:12px; font-weight:800; }
        .pagination span.disabled { color:var(--slate-300); }
        @media(max-width:900px) { .summary { grid-template-columns:repeat(2,minmax(0,1fr)); } .request-row { grid-template-columns:1fr auto; } .request-row .meta { grid-column:1; } .topbar { flex-wrap:wrap; padding-top:10px; padding-bottom:10px; } .actions { margin-left:auto; } }
        @media(max-width:640px) { .brand > span:last-child { display:none; } .nav a { padding:8px 9px; } .hero { align-items:flex-start; } .summary { grid-template-columns:1fr 1fr; } .request-row { grid-template-columns:1fr; } .request-row .meta { grid-column:auto; } .review { width:100%; } .search { width:100%; } .search input { width:100%; } }
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/md-farma-logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/md-farma-logo-theme.css') }}">
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('admin.inbox') }}">
        <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/md-farma-logo.jpeg') }}" alt="" width="52" height="52"></span><span>MD Farma Admin</span>
    </a>
    <nav class="nav" aria-label="Navigasi admin">
        <a href="{{ route('admin.inbox') }}">💬 Inbox</a>
        <a href="{{ route('admin.archive-requests.index') }}" class="active">🗂 Permintaan Arsip</a>
        <a href="{{ route('admin.dashboard') }}">📊 Analitik</a>
    </nav>
    <div class="actions">
        <strong>{{ auth('admin')->user()->username }}</strong>
        <form action="{{ route('admin.logout') }}" method="POST">@csrf<button class="logout" type="submit">Logout</button></form>
    </div>
</header>
<main class="page">
    <section class="hero">
        <div>
            <p class="eyebrow">Verifikasi manual</p>
            <h1>Permintaan Salinan Arsip</h1>
            <p>Periksa identitas, alasan, dan konsultasi terkait sebelum salinan disiapkan atau diserahkan. Sistem ini tidak mengirim arsip secara otomatis.</p>
        </div>
    </section>

    @if (session('success'))<div class="notice">{{ session('success') }}</div>@endif

    <section class="summary">
        @foreach ([
            'all' => 'Semua',
            'pending' => 'Baru',
            'verifying' => 'Verifikasi',
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
        ] as $key => $label)
            <div class="summary-card"><span>{{ $label }}</span><strong>{{ $counts[$key] ?? 0 }}</strong></div>
        @endforeach
    </section>

    <section class="panel">
        <div class="toolbar">
            <div class="filters">
                <a class="filter {{ $selectedStatus === 'semua' ? 'active' : '' }}" href="{{ route('admin.archive-requests.index', ['q' => $search]) }}">Semua</a>
                @foreach ($statusOptions as $key => $label)
                    <a class="filter {{ $selectedStatus === $key ? 'active' : '' }}" href="{{ route('admin.archive-requests.index', ['status' => $key, 'q' => $search]) }}">{{ $label }}</a>
                @endforeach
            </div>
            <form class="search" method="GET" action="{{ route('admin.archive-requests.index') }}">
                <input type="hidden" name="status" value="{{ $selectedStatus }}">
                <input type="search" name="q" value="{{ $search }}" placeholder="Nama, nomor, atau ID permintaan">
                <button type="submit">Cari</button>
            </form>
        </div>

        @if ($requests->isEmpty())
            <div class="empty"><strong>Belum ada permintaan pada filter ini.</strong><p>Permintaan pasien yang masuk akan tampil di halaman ini.</p></div>
        @else
            <div class="request-list">
                @foreach ($requests as $item)
                    <article class="request-row">
                        <div class="request-main">
                            <span class="request-id">#{{ strtoupper(substr($item->public_id, 0, 8)) }}</span>
                            <strong>{{ $item->consultation->nama }}</strong>
                            <small>{{ $item->consultation->jenis_konsultasi === 'resep' ? 'Dengan resep' : 'Tanpa resep' }} · Konsultasi {{ $item->consultation->created_at->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('d M Y') }}</small>
                        </div>
                        <div class="meta">
                            <span class="status {{ $item->status }}">{{ $item->statusLabel() }}</span>
                            <small>Diajukan {{ $item->submitted_at->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('d M Y, H.i') }} WIB</small>
                        </div>
                        <div class="meta">
                            <strong>{{ $item->contactMethodLabel() }}</strong>
                            <small>{{ $item->contact_value }}</small>
                        </div>
                        <a class="review" href="{{ route('admin.archive-requests.show', $item) }}">Tinjau</a>
                    </article>
                @endforeach
            </div>
            @if ($requests->hasPages())
                <nav class="pagination">
                    @if ($requests->onFirstPage())<span class="disabled">← Sebelumnya</span>@else<a href="{{ $requests->previousPageUrl() }}">← Sebelumnya</a>@endif
                    <span>Halaman {{ $requests->currentPage() }} / {{ $requests->lastPage() }}</span>
                    @if ($requests->hasMorePages())<a href="{{ $requests->nextPageUrl() }}">Berikutnya →</a>@else<span class="disabled">Berikutnya →</span>@endif
                </nav>
            @endif
        @endif
    </section>
</main>
</body>
</html>
