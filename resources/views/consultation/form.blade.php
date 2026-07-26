<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Obat - Apotek MD Farma</title>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7f9;
            color: #1f2937;
        }
        nav {
            background: #198754;
            padding: 14px 8%;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 16px;
        }
        .container {
            width: min(440px, 92%);
            margin: 40px auto;
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }
        input, select {
            width: 100%;
            padding: 11px;
            margin: 6px 0 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        h2 { text-align: center; }
        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">← Beranda</a>
        <a href="{{ route('admin.login') }}">Login Admin</a>
    </nav>

    <main class="container">
        <h2>Konsultasi Obat</h2>

        @if ($errors->any())
            <div class="error-box">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('consultation.store') }}" method="POST">
            @csrf

            <label for="nama">Nama Lengkap</label>
            <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required>

            <label for="umur">Umur</label>
            <input id="umur" type="number" name="umur" value="{{ old('umur') }}" min="1" max="120" required>

            <label for="no_hp">Nomor HP</label>
            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required>

            <label for="jenis_konsultasi">Jenis Konsultasi</label>
            <select id="jenis_konsultasi" name="jenis_konsultasi" required>
                <option value="resep" @selected(old('jenis_konsultasi') === 'resep')>Resep Dokter</option>
                <option value="non_resep" @selected(old('jenis_konsultasi') === 'non_resep')>Non Resep</option>
            </select>

            <button type="submit">Mulai Konsultasi</button>
        </form>
    </main>
</body>
</html>
