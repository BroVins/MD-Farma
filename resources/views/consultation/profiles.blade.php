<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pasien — MD Farma</title>
    <style>
        :root {
            --green-950:#052e2b;
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --amber-700:#b45309;
            --amber-50:#fffbeb;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --slate-50:#f8fafc;
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
                radial-gradient(circle at 92% 3%,rgba(16,185,129,.15),transparent 24%),
                var(--slate-50);
        }

        .topbar {
            border-bottom:1px solid rgba(203,213,225,.75);
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(14px);
        }

        nav {
            width:min(1100px,92%);
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
            width:min(1100px,92%);
            margin:32px auto 72px;
        }

        .hero {
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:22px;
            align-items:end;
            padding:30px;
            border-radius:24px;
            color:#fff;
            background:
                radial-gradient(circle at 96% 4%,rgba(255,255,255,.15),transparent 30%),
                linear-gradient(145deg,var(--green-800),var(--green-950));
            box-shadow:0 22px 62px rgba(6,78,59,.18);
        }

        .eyebrow {
            margin:0 0 9px;
            color:#a7f3d0;
            font-size:11px;
            font-weight:900;
            letter-spacing:.1em;
            text-transform:uppercase;
        }

        .hero h1 {
            margin:0;
            font-size:clamp(29px,5vw,43px);
            line-height:1.06;
            letter-spacing:-.04em;
        }

        .hero p:last-child {
            max-width:680px;
            margin:14px 0 0;
            color:#d1fae5;
            font-size:13px;
            line-height:1.65;
        }

        .button {
            min-height:44px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 16px;
            border:1px solid transparent;
            border-radius:12px;
            font:inherit;
            font-size:12px;
            font-weight:900;
            text-decoration:none;
            cursor:pointer;
        }

        .button.primary { background:#fff; color:var(--green-900); }
        .button.green { background:var(--green-700); color:#fff; }
        .button.soft {
            border-color:var(--slate-200);
            background:#fff;
            color:var(--green-800);
        }

        .notice,
        .errors {
            margin-top:18px;
            padding:14px 16px;
            border-radius:13px;
            font-size:12px;
            line-height:1.55;
        }

        .notice {
            border:1px solid #a7f3d0;
            background:var(--green-50);
            color:var(--green-900);
        }

        .errors {
            border:1px solid #fecaca;
            background:#fef2f2;
            color:#991b1b;
        }

        .errors ul { margin:6px 0 0; padding-left:19px; }

        .layout {
            display:grid;
            grid-template-columns:minmax(0,1.35fr) minmax(280px,.65fr);
            gap:20px;
            margin-top:20px;
            align-items:start;
        }

        .panel {
            overflow:hidden;
            border:1px solid var(--slate-200);
            border-radius:21px;
            background:#fff;
            box-shadow:0 15px 44px rgba(15,23,42,.06);
        }

        .panel-head {
            padding:20px 22px;
            border-bottom:1px solid var(--slate-100);
        }

        .panel-head h2 {
            margin:0;
            font-size:18px;
            letter-spacing:-.02em;
        }

        .panel-head p {
            margin:5px 0 0;
            color:var(--slate-500);
            font-size:11px;
            line-height:1.5;
        }

        .profile-list { display:grid; }

        .profile-card {
            padding:21px 22px;
            border-bottom:1px solid var(--slate-100);
        }

        .profile-card:last-child { border-bottom:0; }

        .profile-title {
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:8px;
            margin-bottom:16px;
        }

        .profile-title strong { font-size:15px; }

        .badge {
            padding:4px 8px;
            border-radius:999px;
            background:var(--green-100);
            color:var(--green-900);
            font-size:9px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.04em;
        }

        .usage {
            margin-left:auto;
            color:var(--slate-500);
            font-size:10px;
            font-weight:800;
        }

        form { margin:0; }

        .form-grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:13px;
        }

        .field.full { grid-column:1 / -1; }

        label {
            display:block;
            margin-bottom:7px;
            color:var(--slate-700);
            font-size:11px;
            font-weight:900;
        }

        input,
        select {
            width:100%;
            min-height:43px;
            padding:0 12px;
            border:1px solid var(--slate-300);
            border-radius:10px;
            background:#fff;
            color:var(--slate-950);
            font:inherit;
            font-size:13px;
            outline:none;
        }

        input:focus,
        select:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 4px rgba(5,150,105,.11);
        }

        .checkbox {
            display:flex;
            align-items:flex-start;
            gap:9px;
            margin:14px 0;
            color:var(--slate-600);
            font-size:11px;
            line-height:1.45;
        }

        .checkbox input {
            width:17px;
            min-height:17px;
            margin:0;
        }

        .actions {
            display:flex;
            flex-wrap:wrap;
            gap:9px;
        }

        .add-body { padding:21px 22px; }

        .privacy {
            margin-top:16px;
            padding:14px 15px;
            border-radius:12px;
            background:var(--amber-50);
            color:#78350f;
            font-size:11px;
            line-height:1.55;
        }

        .empty {
            padding:42px 22px;
            color:var(--slate-500);
            text-align:center;
            font-size:12px;
            line-height:1.6;
        }

        @media (max-width:850px) {
            .hero { grid-template-columns:1fr; }
            .layout { grid-template-columns:1fr; }
        }

        @media (max-width:560px) {
            .page { width:94%; margin-top:20px; }
            .hero { padding:24px 21px; }
            .form-grid { grid-template-columns:1fr; }
            .field.full { grid-column:auto; }
            .profile-card,.add-body,.panel-head { padding-left:18px; padding-right:18px; }
            .usage { width:100%; margin-left:0; }
            .actions .button { width:100%; }
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
            <a class="back" href="{{ route('consultation.entry') }}">
                ← Dashboard konsultasi
            </a>
        </nav>
    </header>

    <main class="page">
        <section class="hero" aria-labelledby="profiles-title">
            <div>
                <p class="eyebrow">Profil pasien</p>
                <h1 id="profiles-title">Pisahkan konsultasi setiap anggota keluarga.</h1>
                <p>
                    Satu akses riwayat dapat menyimpan beberapa profil. Nomor HP
                    yang sama tetap boleh digunakan, sedangkan setiap konsultasi
                    tetap terhubung ke pasien yang dipilih.
                </p>
            </div>
            <a class="button primary" href="{{ route('consultation.create') }}">
                + Buat konsultasi
            </a>
        </section>

        @if (session('status'))
            <div class="notice" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="layout">
            <section class="panel" aria-labelledby="saved-profiles-title">
                <header class="panel-head">
                    <h2 id="saved-profiles-title">Profil tersimpan</h2>
                    <p>
                        Perubahan profil hanya berlaku untuk konsultasi berikutnya.
                        Data pada konsultasi lama tetap menggunakan snapshot saat itu.
                    </p>
                </header>

                @if ($profiles->isEmpty())
                    <div class="empty">
                        Belum ada profil pasien. Tambahkan profil pertama melalui
                        formulir di samping.
                    </div>
                @else
                    <div class="profile-list">
                        @foreach ($profiles as $profile)
                            <article class="profile-card">
                                <div class="profile-title">
                                    <strong>{{ $profile->name }}</strong>
                                    <span class="badge">{{ $profile->relationshipLabel() }}</span>
                                    @if ($profile->is_default)
                                        <span class="badge">Profil utama</span>
                                    @endif
                                    <span class="usage">
                                        {{ $profile->consultations_count }} konsultasi
                                    </span>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('consultation.profiles.update', $profile) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="form-grid">
                                        <div class="field full">
                                            <label for="name_{{ $profile->public_id }}">Nama pasien</label>
                                            <input
                                                id="name_{{ $profile->public_id }}"
                                                type="text"
                                                name="name"
                                                value="{{ $profile->name }}"
                                                maxlength="100"
                                                required
                                            >
                                        </div>
                                        <div class="field">
                                            <label for="age_{{ $profile->public_id }}">Umur</label>
                                            <input
                                                id="age_{{ $profile->public_id }}"
                                                type="number"
                                                name="age"
                                                value="{{ $profile->age }}"
                                                min="1"
                                                max="120"
                                                required
                                            >
                                        </div>
                                        <div class="field">
                                            <label for="relationship_{{ $profile->public_id }}">Hubungan</label>
                                            <select
                                                id="relationship_{{ $profile->public_id }}"
                                                name="relationship"
                                                required
                                            >
                                                @foreach ($relationshipOptions as $value => $label)
                                                    <option
                                                        value="{{ $value }}"
                                                        @selected($profile->relationship === $value)
                                                    >
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field full">
                                            <label for="phone_{{ $profile->public_id }}">Nomor HP</label>
                                            <input
                                                id="phone_{{ $profile->public_id }}"
                                                type="tel"
                                                name="phone"
                                                value="{{ $profile->phone }}"
                                                maxlength="25"
                                                required
                                            >
                                        </div>
                                    </div>

                                    @if (! $profile->is_default)
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_default" value="1">
                                            <span>Jadikan profil utama setelah data disimpan.</span>
                                        </label>
                                    @endif

                                    <div class="actions">
                                        <button class="button green" type="submit">
                                            Simpan perubahan
                                        </button>
                                    </div>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="panel" aria-labelledby="add-profile-title">
                <header class="panel-head">
                    <h2 id="add-profile-title">Tambah profil pasien</h2>
                    <p>Data yang sama tidak akan digabung otomatis.</p>
                </header>

                <div class="add-body">
                    <form
                        method="POST"
                        action="{{ route('consultation.profiles.store') }}"
                    >
                        @csrf

                        <div class="form-grid">
                            <div class="field full">
                                <label for="new_name">Nama pasien</label>
                                <input
                                    id="new_name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    maxlength="100"
                                    required
                                >
                            </div>
                            <div class="field">
                                <label for="new_age">Umur</label>
                                <input
                                    id="new_age"
                                    type="number"
                                    name="age"
                                    value="{{ old('age') }}"
                                    min="1"
                                    max="120"
                                    required
                                >
                            </div>
                            <div class="field">
                                <label for="new_relationship">Hubungan</label>
                                <select id="new_relationship" name="relationship" required>
                                    <option value="">Pilih hubungan</option>
                                    @foreach ($relationshipOptions as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            @selected(old('relationship') === $value)
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field full">
                                <label for="new_phone">Nomor HP</label>
                                <input
                                    id="new_phone"
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    maxlength="25"
                                    required
                                >
                            </div>
                        </div>

                        <label class="checkbox">
                            <input type="checkbox" name="is_default" value="1">
                            <span>Jadikan profil utama untuk konsultasi berikutnya.</span>
                        </label>

                        <button class="button green" type="submit">
                            Tambahkan profil
                        </button>
                    </form>

                    <div class="privacy">
                        Nama, umur, dan nomor HP merupakan data profil, bukan
                        metode untuk membuka riwayat. Akses tetap dilindungi oleh
                        perangkat tepercaya dan Password Riwayat.
                    </div>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>
