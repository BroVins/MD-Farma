<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Apotek MD Farma</title>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f5f7f9;
            margin: 0;
            color: #1f2937;
        }
        nav {
            background: #198754;
            padding: 14px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        nav a, nav span { color: white; text-decoration: none; }
        nav form { display: inline; }
        .link-button {
            background: transparent;
            padding: 0;
            border: 0;
            color: white;
            text-decoration: underline;
            cursor: pointer;
        }
        .container {
            width: min(760px, 94%);
            margin: 30px auto;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
        }
        h1 { text-align: center; color: #198754; }
        .patient p { margin: 8px 0; }
        .chat-box {
            height: 350px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            background: #fafafa;
            border-radius: 8px;
        }
        .message {
            background: #198754;
            color: white;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            width: fit-content;
            max-width: 75%;
            overflow-wrap: anywhere;
        }
        .message.admin {
            background: #4b5563;
            margin-left: auto;
        }
        .message img {
            display: block;
            margin-top: 10px;
            border-radius: 6px;
            max-width: 100%;
        }
        .form-row {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            min-width: 0;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        input[type="file"] { margin: 12px 0; }
        .send-button {
            padding: 12px 20px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .info { font-size: 14px; color: #666; }
        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
        }
        @media (max-width: 600px) {
            .form-row { flex-direction: column; }
            .message { max-width: 90%; }
        }
    </style>
</head>
<body>
    <nav>
        <div>
            @if (session()->has('admin_id'))
                <a href="{{ route('admin.dashboard') }}">← Dashboard</a>
            @else
                <a href="{{ route('home') }}">← Beranda</a>
                <a href="{{ route('consultation.create') }}">Konsultasi Baru</a>
            @endif
        </div>

        @if (session()->has('admin_id'))
            <div>
                <span>{{ session('admin_username') }}</span>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="link-button" type="submit">Logout</button>
                </form>
            </div>
        @else
            <a href="{{ route('admin.login') }}">Login Admin</a>
        @endif
    </nav>

    <main class="container">
        <h1>Live Chat Apotek MD Farma</h1>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="card patient">
            <h3>Data Pasien</h3>
            <p><strong>Nama:</strong> {{ $consultation->nama }}</p>
            <p><strong>Umur:</strong> {{ $consultation->umur }}</p>
            <p><strong>No HP:</strong> {{ $consultation->no_hp }}</p>
            <p>
                <strong>Jenis Konsultasi:</strong>
                {{ $consultation->jenis_konsultasi === 'resep' ? 'Resep Dokter' : 'Non Resep' }}
            </p>
        </section>

        <section class="card">
            <h3>Riwayat Chat</h3>

            <div class="chat-box" id="chatBox">
                @forelse ($consultation->messages as $chat)
                    <div class="message {{ $chat->sender === 'admin' ? 'admin' : '' }}">
                        <strong>{{ $chat->sender === 'admin' ? 'Apoteker' : 'Pasien' }}</strong>

                        @if ($chat->message)
                            <br>{{ $chat->message }}
                        @endif

                        @if ($chat->image)
                            <img src="{{ asset('storage/'.$chat->image) }}" alt="Lampiran chat" width="180">
                        @endif
                    </div>
                @empty
                    <p>Belum ada pesan.</p>
                @endforelse
            </div>
        </section>

        @if (session()->has('admin_id'))
            <section class="card">
                <h3>Balasan Apoteker</h3>

                <form action="{{ route('admin.chat.reply', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="message" value="{{ old('message') }}" placeholder="Balas pasien..." required>
                        <button class="send-button" type="submit">Kirim Balasan</button>
                    </div>
                </form>
            </section>
        @else
            <section class="card">
                <h3>Kirim Pesan Pasien</h3>

                <form action="{{ route('chat.send', $consultation->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="message" value="{{ old('message') }}" placeholder="Tulis pesan...">
                        <button class="send-button" type="submit">Kirim Pesan</button>
                    </div>
                    <input type="file" name="image" accept="image/*">
                </form>

                <p class="info">
                    Pesan akan dibalas oleh apoteker sesuai jam operasional Apotek MD Farma.
                </p>
            </section>
        @endif
    </main>

    <script>
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>
</html>
