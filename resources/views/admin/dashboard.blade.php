<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin MD Farma</title>

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
            padding: 14px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        nav a,
        nav span {
            color: white;
            text-decoration: none;
        }

        nav form {
            display: inline;
        }

        .admin-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logout-button {
            background: transparent;
            border: 0;
            padding: 0;
            color: white;
            text-decoration: underline;
            cursor: pointer;
        }

        .container {
            width: min(1100px, 94%);
            margin: 30px auto;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f0fdf4;
        }

        .chat-link {
            color: #198754;
            font-weight: bold;
        }

        @media (max-width: 800px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr;
            }

            nav {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">MD Farma</a>

        <div class="admin-menu">
            <span>
                {{ auth('admin')->user()->username }}
            </span>

            <form
                action="{{ route('admin.logout') }}"
                method="POST"
            >
                @csrf

                <button
                    class="logout-button"
                    type="submit"
                >
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="container">
        <h1>Dashboard Admin Apotek MD Farma</h1>

        <section class="stats">
            <div class="card">
                <h3>Total Konsultasi</h3>
                <h2>{{ $totalConsultation }}</h2>
            </div>

            <div class="card">
                <h3>Chat Aktif</h3>
                <h2>{{ $activeChat }}</h2>
            </div>

            <div class="card">
                <h3>Resep</h3>
                <h2>{{ $resep }}</h2>
            </div>

            <div class="card">
                <h3>Non Resep</h3>
                <h2>{{ $nonResep }}</h2>
            </div>
        </section>

        <section class="card">
            <h2>Daftar Konsultasi</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($consultations as $data)
                            <tr>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->umur }}</td>
                                <td>
                                    {{
                                        $data->jenis_konsultasi === 'resep'
                                            ? 'Resep Dokter'
                                            : 'Non Resep'
                                    }}
                                </td>
                                <td>{{ ucfirst($data->status) }}</td>
                                <td>
                                    <a
                                        class="chat-link"
                                        href="{{ route('chat.show', $data->id) }}"
                                    >
                                        Buka Chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    Belum ada konsultasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
