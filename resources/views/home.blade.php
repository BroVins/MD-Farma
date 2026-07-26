<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek MD Farma</title>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7f9;
            color: #1f2937;
        }
        nav {
            background: #146c43;
            padding: 14px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        nav strong, nav a { color: white; }
        nav a { text-decoration: none; margin-left: 14px; }
        header {
            background: #198754;
            color: white;
            padding: 64px 20px;
            text-align: center;
        }
        .container {
            width: min(900px, 90%);
            margin: 30px auto;
        }
        .card {
            background: white;
            padding: 24px;
            margin: 18px 0;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        }
        .btn {
            background: white;
            color: #198754;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-top: 18px;
            font-weight: bold;
        }
        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 18px;
        }
    </style>
</head>
<body>
    <nav>
        <strong>MD Farma</strong>
        <div>
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('consultation.create') }}">Konsultasi</a>
            <a href="{{ route('admin.login') }}">Admin</a>
        </div>
    </nav>

    <header>
        <h1>Apotek MD Farma</h1>
        <p>Konsultasi obat online bersama apoteker terpercaya</p>
        <a href="{{ route('consultation.create') }}" class="btn">Konsultasi Sekarang</a>
    </header>

    <main class="container">
        <section class="card">
            <h2>Tentang Kami</h2>
            <p>
                Apotek MD Farma menyediakan layanan informasi obat dan konsultasi
                kesehatan secara online untuk membantu pasien mendapatkan informasi
                penggunaan obat yang tepat.
            </p>
        </section>

        <section class="card">
            <h2>Jam Operasional</h2>
            <p>Senin - Jumat: 08.00 - 20.00</p>
            <p>Sabtu: 08.00 - 18.00</p>
            <p>Minggu: Tutup</p>
        </section>

        <section class="card">
            <h2>Alamat</h2>
            <p>Alamat Apotek MD Farma</p>
        </section>

        <section class="card">
            <h2>Apoteker</h2>
            <p>Apoteker 1</p>
            <p>Apoteker 2</p>
        </section>
    </main>

    <footer>Copyright © 2026 Apotek MD Farma</footer>
</body>
</html>
