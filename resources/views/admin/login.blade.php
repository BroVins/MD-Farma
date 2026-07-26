<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Apotek MD Farma</title>

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
        }

        .container {
            width: min(380px, 92%);
            margin: 70px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        h2 {
            text-align: center;
            color: #198754;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 6px 0 15px;
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
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            color: #991b1b;
            background: #fee2e2;
        }

        .success {
            color: #166534;
            background: #dcfce7;
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">← Kembali ke Beranda</a>
    </nav>

    <main class="container">
        <h2>Login Admin</h2>

        @if (session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form
            action="{{ route('admin.authenticate') }}"
            method="POST"
        >
            @csrf

            <label for="username">Username</label>
            <input
                id="username"
                type="text"
                name="username"
                value="{{ old('username') }}"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
