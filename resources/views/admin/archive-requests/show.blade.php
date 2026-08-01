<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinjau Permintaan Arsip — MD Farma</title>
    <style>
        :root { --green-950:#1f2937;--green-900:#172554;--green-800:#1e3a8a;--green-700:#1238cc;--green-100:#dbeafe;--green-50:#eff6ff;--amber-800:#92400e;--amber-100:#fef3c7;--blue-800:#1e40af;--blue-100:#dbeafe;--red-800:#991b1b;--red-100:#fee2e2;--slate-950:#0f172a;--slate-800:#1e293b;--slate-700:#334155;--slate-600:#475569;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc; }
        *{box-sizing:border-box} body{margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:var(--slate-950);background:var(--slate-50)} a{text-decoration:none} button,select,textarea{font:inherit}
        .topbar{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:64px;padding:0 clamp(18px,4vw,54px);color:#fff;background:linear-gradient(135deg,var(--green-950),var(--green-800))}.brand,.nav,.actions{display:flex;align-items:center;gap:10px}.brand{color:#fff;font-weight:900}.brand-mark{width:38px;height:38px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.2);border-radius:12px;background:rgba(255,255,255,.12)}.nav a{padding:8px 11px;border-radius:10px;color:rgba(255,255,255,.78);font-size:13px;font-weight:800}.nav a.active,.nav a:hover{color:#fff;background:rgba(255,255,255,.14)}.logout{padding:8px 11px;border:1px solid rgba(255,255,255,.18);border-radius:10px;color:#fff;background:rgba(255,255,255,.08);cursor:pointer}
        .page{width:min(1080px,94%);margin:28px auto 70px}.back{display:inline-flex;margin-bottom:14px;color:var(--green-800);font-size:13px;font-weight:900}.hero{display:flex;align-items:start;justify-content:space-between;gap:20px;padding:25px;border:1px solid var(--slate-200);border-radius:21px;background:#fff;box-shadow:0 14px 40px rgba(15,23,42,.06)}.eyebrow{margin:0 0 6px;color:var(--green-700);font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase}.hero h1{margin:0;font-size:clamp(25px,4vw,38px);letter-spacing:-.035em}.hero p{margin:10px 0 0;color:var(--slate-500)}.status{display:inline-flex;padding:7px 10px;border-radius:999px;font-size:11px;font-weight:900;white-space:nowrap}.status.pending{color:var(--amber-800);background:var(--amber-100)}.status.verifying{color:var(--blue-800);background:var(--blue-100)}.status.approved{color:var(--green-900);background:var(--green-100)}.status.rejected{color:var(--red-800);background:var(--red-100)}.status.completed{color:var(--slate-700);background:var(--slate-100)}
        .alert{margin-top:16px;padding:13px 15px;border-radius:12px}.alert.success{border:1px solid var(--green-100);color:var(--green-900);background:var(--green-50)}.alert.error{border:1px solid var(--red-100);color:var(--red-800);background:#fff5f5}.grid{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(300px,.8fr);gap:18px;margin-top:18px}.panel{padding:21px;border:1px solid var(--slate-200);border-radius:19px;background:#fff;box-shadow:0 12px 35px rgba(15,23,42,.05)}.panel h2{margin:0 0 16px;font-size:18px}.detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.detail{padding:13px;border:1px solid var(--slate-100);border-radius:12px;background:var(--slate-50)}.detail span{display:block;color:var(--slate-500);font-size:10px;font-weight:850;text-transform:uppercase;letter-spacing:.06em}.detail strong{display:block;margin-top:5px;font-size:13px;line-height:1.5}.reason{margin-top:15px;padding:15px;border-left:4px solid var(--green-700);border-radius:0 12px 12px 0;background:var(--green-50);line-height:1.65}.chat-link{display:inline-flex;align-items:center;justify-content:center;min-height:42px;margin-top:15px;padding:0 15px;border-radius:11px;color:#fff;background:var(--green-700);font-size:12px;font-weight:900}
        label{display:block;margin:0 0 7px;font-size:12px;font-weight:850}select,textarea{width:100%;border:1px solid var(--slate-300);border-radius:11px;background:#fff;outline:none}select{min-height:44px;padding:0 11px}textarea{min-height:130px;padding:11px;resize:vertical;line-height:1.55}select:focus,textarea:focus{border-color:var(--green-700);box-shadow:0 0 0 3px rgba(18, 56, 204, .12)}.field{margin-top:14px}.hint{margin:6px 0 0;color:var(--slate-500);font-size:11px;line-height:1.5}.submit{width:100%;min-height:45px;margin-top:16px;border:0;border-radius:12px;color:#fff;background:var(--green-700);font-weight:900;cursor:pointer}.locked{padding:15px;border:1px solid var(--slate-200);border-radius:12px;color:var(--slate-600);background:var(--slate-50);line-height:1.6}
        .timeline{display:grid;gap:0}.event{position:relative;padding:0 0 19px 25px;border-left:2px solid var(--slate-200)}.event:last-child{padding-bottom:0}.event::before{content:"";position:absolute;left:-6px;top:3px;width:10px;height:10px;border:2px solid #fff;border-radius:50%;background:var(--green-700);box-shadow:0 0 0 2px var(--green-100)}.event strong{display:block;font-size:13px}.event small{display:block;margin-top:4px;color:var(--slate-500);line-height:1.5}.event p{margin:7px 0 0;color:var(--slate-700);font-size:12px;line-height:1.55}.full{grid-column:1/-1}
        @media(max-width:820px){.topbar{flex-wrap:wrap;padding-top:10px;padding-bottom:10px}.grid{grid-template-columns:1fr}.detail-grid{grid-template-columns:1fr}.hero{flex-direction:column}.brand>span:last-child{display:none}.actions{margin-left:auto}}
    </style>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/md-farma-logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('css/md-farma-logo-theme.css') }}">
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('admin.inbox') }}"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/md-farma-logo.jpeg') }}" alt="" width="52" height="52"></span><span>MD Farma Admin</span></a>
    <nav class="nav"><a href="{{ route('admin.inbox') }}">💬 Inbox</a><a class="active" href="{{ route('admin.archive-requests.index') }}">🗂 Permintaan Arsip</a><a href="{{ route('admin.dashboard') }}">📊 Analitik</a></nav>
    <div class="actions"><strong>{{ auth('admin')->user()->username }}</strong><form action="{{ route('admin.logout') }}" method="POST">@csrf<button class="logout" type="submit">Logout</button></form></div>
</header>
<main class="page">
    <a class="back" href="{{ route('admin.archive-requests.index') }}">← Kembali ke daftar</a>
    <section class="hero">
        <div><p class="eyebrow">Permintaan #{{ strtoupper(substr($archiveRequest->public_id, 0, 8)) }}</p><h1>{{ $archiveRequest->consultation->nama }}</h1><p>Diajukan {{ $archiveRequest->submitted_at->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('d M Y, H.i') }} WIB</p></div>
        <span class="status {{ $archiveRequest->status }}">{{ $archiveRequest->statusLabel() }}</span>
    </section>

    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if(session('warning'))<div class="alert error">{{ session('warning') }}</div>@endif
    @if($errors->any())<div class="alert error"><strong>Periksa kembali data:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="grid">
        <section class="panel">
            <h2>Data permintaan</h2>
            <div class="detail-grid">
                <div class="detail"><span>Pasien</span><strong>{{ $archiveRequest->consultation->nama }} · {{ $archiveRequest->consultation->umur }} tahun</strong></div>
                <div class="detail"><span>Profil</span><strong>{{ $archiveRequest->patientProfile?->relationshipLabel() ?? 'Profil lama' }}</strong></div>
                <div class="detail"><span>Konsultasi</span><strong>{{ $archiveRequest->consultation->jenis_konsultasi === 'resep' ? 'Dengan resep' : 'Tanpa resep' }} · {{ $archiveRequest->consultation->created_at->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('d M Y') }}</strong></div>
                <div class="detail"><span>Metode tindak lanjut</span><strong>{{ $archiveRequest->contactMethodLabel() }} · {{ $archiveRequest->contact_value }}</strong></div>
                @php
                    $availableUntil = $archiveRequest->consultation
                        ->patientHistoryAvailableUntil();
                @endphp
                <div class="detail">
                    <span>Akses pasien berakhir</span>
                    <strong>
                        {{ $availableUntil
                            ? $availableUntil
                                ->timezone(config('analytics.timezone', 'Asia/Jakarta'))
                                ->format('d M Y, H.i').' WIB'
                            : 'Tidak tersedia' }}
                    </strong>
                </div>
                <div class="detail"><span>Diproses oleh</span><strong>{{ $archiveRequest->processedByAdmin?->username ?? 'Belum ditangani' }}</strong></div>
            </div>
            <div class="reason"><strong>Alasan pasien</strong><br>{{ $archiveRequest->reason }}</div>
            <a class="chat-link" href="{{ route('admin.inbox.show', $archiveRequest->consultation) }}">Buka konsultasi dan arsip chat</a>
        </section>

        <aside class="panel">
            <h2>Proses permintaan</h2>
            @if($allowedTransitions === [])
                <div class="locked">Permintaan ini sudah selesai. Catatan dan riwayat proses tetap tersedia sebagai audit.</div>
            @else
                <form method="POST" action="{{ route('admin.archive-requests.update', $archiveRequest) }}">
                    @csrf @method('PUT')
                    <div class="field"><label for="status">Status berikutnya</label><select id="status" name="status" required><option value="">Pilih tindakan</option>@foreach($allowedTransitions as $value => $label)<option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>@endforeach</select></div>
                    <div class="field"><label for="decision_notes">Catatan proses/keputusan</label><textarea id="decision_notes" name="decision_notes" placeholder="Catat hasil verifikasi, dasar persetujuan/penolakan, atau cara penyerahan salinan.">{{ old('decision_notes', $archiveRequest->decision_notes) }}</textarea><p class="hint">Wajib minimal 10 karakter jika permintaan ditolak atau ditandai selesai.</p></div>
                    <button class="submit" type="submit">Simpan status</button>
                </form>
            @endif
        </aside>

        <section class="panel full">
            <h2>Riwayat proses</h2>
            <div class="timeline">
                @foreach($archiveRequest->logs as $log)
                    <article class="event">
                        <strong>{{ \App\Models\ConsultationArchiveCopyRequest::STATUSES[$log->new_status] ?? $log->new_status }}</strong>
                        <small>{{ $log->actor_type === 'patient' ? 'Pasien' : ($log->admin?->username ?? 'Sistem') }} · {{ $log->created_at->timezone(config('analytics.timezone', 'Asia/Jakarta'))->format('d M Y, H.i') }} WIB</small>
                        @if($log->notes)<p>{{ $log->notes }}</p>@endif
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</main>
</body>
</html>
