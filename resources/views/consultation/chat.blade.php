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

    <title>Live Chat Apotek MD Farma</title>
    @vite('resources/js/app.js')

    <style>
        :root {
            --green-900:#064e3b;
            --green-800:#065f46;
            --green-700:#047857;
            --green-600:#059669;
            --green-100:#d1fae5;
            --green-50:#ecfdf5;
            --slate-950:#0f172a;
            --slate-700:#334155;
            --slate-600:#475569;
            --slate-500:#64748b;
            --slate-300:#cbd5e1;
            --slate-200:#e2e8f0;
            --slate-100:#f1f5f9;
            --white:#fff;
        }

        * { box-sizing:border-box; }

        body {
            margin:0;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,
                BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--slate-950);
            background:#f8fafc;
        }

        button,input { font:inherit; }

        nav {
            position:sticky;
            top:0;
            z-index:30;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:15px;
            padding:14px clamp(18px,5vw,70px);
            background:rgba(6,78,59,.96);
            color:#fff;
            backdrop-filter:blur(12px);
            box-shadow:0 8px 24px rgba(6,78,59,.15);
        }

        nav a,nav span {
            color:#fff;
            text-decoration:none;
        }

        nav form { display:inline; }

        .nav-links,.admin-menu {
            display:flex;
            align-items:center;
            gap:14px;
        }

        .link-button {
            padding:0;
            border:0;
            background:transparent;
            color:#fff;
            text-decoration:underline;
            cursor:pointer;
        }

        .container {
            width:min(900px,94%);
            margin:28px auto 55px;
        }

        .heading {
            margin-bottom:19px;
            text-align:center;
        }

        .heading h1 {
            margin:0 0 6px;
            color:var(--green-800);
            letter-spacing:-.03em;
        }

        .heading p {
            margin:0;
            color:var(--slate-500);
        }

        .connection-row {
            display:flex;
            justify-content:center;
            margin-top:11px;
        }

        .connection-status {
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:7px 10px;
            border-radius:999px;
            background:var(--slate-100);
            color:var(--slate-600);
            font-size:11px;
            font-weight:800;
        }

        .connection-dot {
            width:8px;
            height:8px;
            border-radius:50%;
            background:#d97706;
        }

        .connection-status.connected .connection-dot {
            background:#16a34a;
        }

        .connection-status.disconnected .connection-dot {
            background:#dc2626;
        }

        .card {
            margin-bottom:17px;
            padding:19px;
            border:1px solid var(--slate-200);
            border-radius:17px;
            background:#fff;
            box-shadow:0 13px 34px rgba(15,23,42,.07);
        }

        .consultation-head {
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:13px;
            margin-bottom:13px;
        }

        .consultation-head h3 { margin:0 0 7px; }

        .status {
            display:inline-flex;
            padding:5px 9px;
            border-radius:999px;
            background:#fef3c7;
            color:#92400e;
            font-size:10px;
            font-weight:800;
        }

        .status.finished {
            background:var(--slate-100);
            color:var(--slate-700);
        }

        .status-button {
            padding:8px 10px;
            border:1px solid var(--slate-300);
            border-radius:9px;
            background:#fff;
            color:var(--slate-700);
            font-size:11px;
            font-weight:800;
            cursor:pointer;
        }

        .patient-grid {
            display:grid;
            grid-template-columns:repeat(3,minmax(0,1fr));
            gap:11px;
        }

        .patient-item {
            padding:11px;
            border-radius:11px;
            background:var(--slate-100);
        }

        .patient-item span,.patient-item strong {
            display:block;
        }

        .patient-item span {
            margin-bottom:4px;
            color:var(--slate-500);
            font-size:10px;
            font-weight:700;
            letter-spacing:.05em;
            text-transform:uppercase;
        }

        .chat-box {
            height:min(520px,63vh);
            overflow-y:auto;
            padding:17px;
            border:1px solid var(--slate-200);
            border-radius:14px;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(16,185,129,.08),
                    transparent 30%
                ),
                #f8fafc;
            scroll-behavior:smooth;
        }

        .date-divider {
            display:flex;
            align-items:center;
            gap:9px;
            margin:20px 0 14px;
            color:var(--slate-500);
            font-size:10px;
            font-weight:800;
            letter-spacing:.04em;
            text-transform:uppercase;
        }

        .date-divider::before,.date-divider::after {
            content:"";
            flex:1;
            height:1px;
            background:var(--slate-200);
        }

        .message {
            width:fit-content;
            max-width:min(78%,620px);
            margin-bottom:11px;
            padding:10px 12px 7px;
            border-radius:14px 14px 14px 4px;
            background:linear-gradient(
                145deg,
                var(--green-700),
                var(--green-800)
            );
            color:#fff;
            overflow-wrap:anywhere;
            box-shadow:0 8px 20px rgba(4,120,87,.16);
        }

        .message.admin {
            margin-left:auto;
            border-radius:14px 14px 4px 14px;
            background:linear-gradient(145deg,#334155,#1e293b);
            box-shadow:0 8px 20px rgba(30,41,59,.17);
        }

        .message-sender {
            display:block;
            margin-bottom:4px;
            font-size:10px;
            opacity:.82;
        }

        .message-text {
            line-height:1.47;
            white-space:pre-wrap;
        }

        .message img {
            display:block;
            max-width:100%;
            margin-top:8px;
            border-radius:8px;
        }

        .message-time {
            display:block;
            margin-top:6px;
            font-size:9px;
            text-align:right;
            opacity:.77;
        }

        .form-row {
            display:flex;
            gap:9px;
        }

        input[type="text"] {
            flex:1;
            min-width:0;
            padding:11px 12px;
            border:1px solid var(--slate-300);
            border-radius:9px;
            outline:none;
        }

        input[type="text"]:focus {
            border-color:var(--green-600);
            box-shadow:0 0 0 3px rgba(5,150,105,.13);
        }

        input[type="file"] { margin-top:11px; }

        .send {
            padding:11px 18px;
            border:0;
            border-radius:9px;
            background:var(--green-700);
            color:#fff;
            font-weight:800;
            cursor:pointer;
        }

        .send:disabled {
            opacity:.55;
            cursor:wait;
        }

        .info {
            margin:12px 0 0;
            color:var(--slate-500);
            font-size:11px;
        }

        .error,.success,.form-error {
            margin-bottom:13px;
            padding:11px;
            border-radius:9px;
        }

        .error,.form-error {
            background:#fee2e2;
            color:#991b1b;
        }

        .success {
            background:var(--green-50);
            color:var(--green-900);
        }

        .form-error { display:none; }
        .form-error.visible { display:block; }

        @media (max-width:700px) {
            nav,.consultation-head {
                align-items:flex-start;
                flex-direction:column;
            }

            .patient-grid { grid-template-columns:1fr; }
            .form-row { flex-direction:column; }
            .message { max-width:92%; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-links">
            @if (auth('admin')->check())
                <a href="{{ route('admin.dashboard') }}">
                    ← Dashboard
                </a>
            @else
                <a href="{{ route('home') }}">← Beranda</a>
                <a href="{{ route('consultation.create') }}">
                    Konsultasi Baru
                </a>
            @endif
        </div>

        @if (auth('admin')->check())
            <div class="admin-menu">
                <span>{{ auth('admin')->user()->username }}</span>
                <form
                    action="{{ route('admin.logout') }}"
                    method="POST"
                >
                    @csrf
                    <button class="link-button" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('admin.login') }}">Login Admin</a>
        @endif
    </nav>

    @php
        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );
        $started = $consultation->created_at
            ->copy()
            ->timezone($timezone);
        $lastDate = null;
    @endphp

    <main class="container">
        <header class="heading">
            <h1>Konsultasi MD Farma</h1>
            <p>
                Dimulai {{ $started
                    ->locale('id')
                    ->isoFormat('dddd, D MMMM YYYY') }}
                pukul {{ $started->format('H.i') }} WIB
            </p>

            <div class="connection-row">
                <span
                    id="connectionStatus"
                    class="connection-status"
                    aria-live="polite"
                >
                    <span class="connection-dot"></span>
                    <span data-status-text>
                        Menghubungkan realtime...
                    </span>
                </span>
            </div>
        </header>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="card">
            <div class="consultation-head">
                <div>
                    <h3>Data Konsultasi</h3>
                    <span class="status {{
                        $consultation->status === 'selesai'
                            ? 'finished'
                            : ''
                    }}">
                        {{ ucfirst($consultation->status) }}
                    </span>
                </div>

                @if (auth('admin')->check())
                    <form
                        action="{{ route(
                            'admin.chat.status',
                            $consultation
                        ) }}"
                        method="POST"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="status"
                            value="{{
                                $consultation->status === 'aktif'
                                    ? 'selesai'
                                    : 'aktif'
                            }}"
                        >
                        <button
                            class="status-button"
                            type="submit"
                        >
                            {{
                                $consultation->status === 'aktif'
                                    ? 'Tandai Selesai'
                                    : 'Aktifkan Kembali'
                            }}
                        </button>
                    </form>
                @endif
            </div>

            <div class="patient-grid">
                <div class="patient-item">
                    <span>Nama pasien</span>
                    <strong>{{ $consultation->nama }}</strong>
                </div>
                <div class="patient-item">
                    <span>Umur</span>
                    <strong>{{ $consultation->umur }} tahun</strong>
                </div>
                <div class="patient-item">
                    <span>Nomor HP</span>
                    <strong>{{ $consultation->no_hp }}</strong>
                </div>
                <div class="patient-item">
                    <span>Jenis konsultasi</span>
                    <strong>
                        {{
                            $consultation->jenis_konsultasi === 'resep'
                                ? 'Resep Dokter'
                                : 'Non Resep'
                        }}
                    </strong>
                </div>
                <div class="patient-item">
                    <span>Tanggal dibuat</span>
                    <strong>
                        {{ $started
                            ->locale('id')
                            ->isoFormat('D MMMM YYYY') }}
                    </strong>
                </div>
                <div class="patient-item">
                    <span>Waktu dibuat</span>
                    <strong>{{ $started->format('H.i') }} WIB</strong>
                </div>
            </div>
        </section>

        <section class="card">
            <h3 style="margin-top:0">Riwayat Chat</h3>

            <div
                class="chat-box"
                id="chatBox"
                aria-live="polite"
            >
                @forelse ($consultation->messages as $chat)
                    @php
                        $local = $chat->created_at
                            ->copy()
                            ->timezone($timezone);
                        $dateKey = $local->format('Y-m-d');
                    @endphp

                    @if ($lastDate !== $dateKey)
                        <div
                            class="date-divider"
                            data-message-date="{{ $dateKey }}"
                        >
                            {{ $local
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                        @php($lastDate = $dateKey)
                    @endif

                    <div
                        class="message {{
                            $chat->sender === 'admin'
                                ? 'admin'
                                : ''
                        }}"
                        data-message-id="{{ $chat->id }}"
                        data-message-date="{{ $dateKey }}"
                    >
                        <strong class="message-sender">
                            {{
                                $chat->sender === 'admin'
                                    ? 'Apoteker'
                                    : 'Pasien'
                            }}
                        </strong>

                        @if ($chat->message)
                            <div class="message-text">
                                {{ $chat->message }}
                            </div>
                        @endif

                        @if ($chat->image)
                            <img
                                src="{{ route(
                                    'chat.attachment',
                                    [
                                        'consultation' => $consultation,
                                        'message' => $chat,
                                    ]
                                ) }}"
                                alt="Lampiran chat"
                                width="240"
                                loading="lazy"
                            >
                        @endif

                        <time
                            class="message-time"
                            datetime="{{ $chat->created_at->toIso8601String() }}"
                            title="{{ $local
                                ->locale('id')
                                ->isoFormat(
                                    'dddd, D MMMM YYYY [pukul] HH.mm.ss'
                                ) }} WIB"
                        >
                            {{ $local->format('H.i') }} WIB
                        </time>
                    </div>
                @empty
                    <p data-empty-chat>Belum ada pesan.</p>
                @endforelse
            </div>
        </section>

        @if ($consultation->status === 'selesai')
            <section class="card">
                <div class="success" style="margin:0">
                    Konsultasi telah selesai. Admin dapat mengaktifkannya
                    kembali melalui tombol di bagian Data Konsultasi.
                </div>
            </section>
        @elseif (auth('admin')->check())
            <section class="card">
                <h3 style="margin-top:0">Balasan Apoteker</h3>
                <div class="form-error" data-form-error></div>

                <form
                    class="realtime-form"
                    action="{{ route(
                        'admin.chat.reply',
                        $consultation
                    ) }}"
                    method="POST"
                >
                    @csrf
                    <div class="form-row">
                        <input
                            type="text"
                            name="message"
                            value="{{ old('message') }}"
                            placeholder="Balas pasien..."
                            maxlength="2000"
                            autocomplete="off"
                            required
                        >
                        <button class="send" type="submit">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </section>
        @else
            <section class="card">
                <h3 style="margin-top:0">Kirim Pesan Pasien</h3>
                <div class="form-error" data-form-error></div>

                <form
                    class="realtime-form"
                    action="{{ route(
                        'chat.send',
                        $consultation
                    ) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <div class="form-row">
                        <input
                            type="text"
                            name="message"
                            value="{{ old('message') }}"
                            placeholder="Tulis pesan..."
                            maxlength="2000"
                            autocomplete="off"
                        >
                        <button class="send" type="submit">
                            Kirim Pesan
                        </button>
                    </div>
                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >
                </form>

                <p class="info">
                    Akses chat terikat pada sesi browser pasien.
                    Waktu ditampilkan dalam zona WIB.
                </p>
            </section>
        @endif
    </main>

    <script>
        (() => {
            const publicId = @json($consultation->public_id);
            const timezone = @json($timezone);
            const channelName = `consultation.${publicId}`;
            const chatBox = document.getElementById('chatBox');
            const status = document.getElementById(
                'connectionStatus'
            );

            let initialized = false;
            let sessionTimer = null;

            const dateFormatter = new Intl.DateTimeFormat(
                'id-ID',
                {
                    timeZone: timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                }
            );

            const shortTime = new Intl.DateTimeFormat(
                'id-ID',
                {
                    timeZone: timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                }
            );

            const fullTime = new Intl.DateTimeFormat(
                'id-ID',
                {
                    timeZone: timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                }
            );

            function dateKey(value) {
                const parts = new Intl.DateTimeFormat(
                    'en-CA',
                    {
                        timeZone: timezone,
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    }
                ).formatToParts(new Date(value));

                const get = type => parts.find(
                    item => item.type === type
                )?.value;

                return `${get('year')}-${get('month')}-${get('day')}`;
            }

            function setStatus(state, text) {
                status.classList.remove(
                    'connected',
                    'disconnected'
                );

                if (state) status.classList.add(state);

                status.querySelector('[data-status-text]')
                    .textContent = text;
            }

            function scrollBottom() {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            function ensureDateDivider(createdAt) {
                const key = dateKey(createdAt);

                if (
                    chatBox.querySelector(
                        `.date-divider[data-message-date="${key}"]`
                    )
                ) return;

                const divider = document.createElement('div');
                divider.className = 'date-divider';
                divider.dataset.messageDate = key;
                divider.textContent = dateFormatter.format(
                    new Date(createdAt)
                );
                chatBox.appendChild(divider);
            }

            function appendMessage(data) {
                if (!data?.id || !data.created_at) return;

                if (
                    chatBox.querySelector(
                        `[data-message-id="${data.id}"]`
                    )
                ) return;

                chatBox.querySelector('[data-empty-chat]')?.remove();
                ensureDateDivider(data.created_at);

                const date = new Date(data.created_at);
                const bubble = document.createElement('div');
                bubble.className = data.sender === 'admin'
                    ? 'message admin'
                    : 'message';
                bubble.dataset.messageId = data.id;
                bubble.dataset.messageDate = dateKey(data.created_at);

                const sender = document.createElement('strong');
                sender.className = 'message-sender';
                sender.textContent = data.sender === 'admin'
                    ? 'Apoteker'
                    : 'Pasien';
                bubble.appendChild(sender);

                if (data.message) {
                    const text = document.createElement('div');
                    text.className = 'message-text';
                    text.textContent = data.message;
                    bubble.appendChild(text);
                }

                if (data.attachment_url) {
                    const image = document.createElement('img');
                    image.src = data.attachment_url;
                    image.alt = 'Lampiran chat';
                    image.width = 240;
                    image.loading = 'lazy';
                    bubble.appendChild(image);
                }

                const time = document.createElement('time');
                time.className = 'message-time';
                time.dateTime = data.created_at;
                time.textContent = `${shortTime.format(date)} WIB`;
                time.title = `${fullTime.format(date)} WIB`;
                bubble.appendChild(time);

                chatBox.appendChild(bubble);
                scrollBottom();
            }

            function errorBox(form) {
                return form.closest('.card')
                    ?.querySelector('[data-form-error]');
            }

            function showError(form, text) {
                const box = errorBox(form);
                if (!box) return;
                box.textContent = text;
                box.classList.add('visible');
            }

            function clearError(form) {
                const box = errorBox(form);
                if (!box) return;
                box.textContent = '';
                box.classList.remove('visible');
            }

            function expireSession() {
                window.Echo?.leave(channelName);
                setStatus(
                    'disconnected',
                    'Sesi pasien telah berakhir'
                );

                document.querySelectorAll(
                    '.realtime-form input, .realtime-form button'
                ).forEach(element => {
                    element.disabled = true;
                });
            }

            function scheduleExpiry(expiresAt) {
                if (!expiresAt) return;

                clearTimeout(sessionTimer);
                const delay = new Date(expiresAt).getTime()
                    - Date.now();

                if (delay <= 0) {
                    expireSession();
                    return;
                }

                sessionTimer = setTimeout(
                    expireSession,
                    delay
                );
            }

            document.querySelectorAll('.realtime-form')
                .forEach(form => {
                    form.addEventListener(
                        'submit',
                        async event => {
                            event.preventDefault();
                            clearError(form);

                            const button = form.querySelector(
                                'button[type="submit"]'
                            );
                            button.disabled = true;

                            try {
                                const response = await fetch(
                                    form.action,
                                    {
                                        method: 'POST',
                                        body: new FormData(form),
                                        credentials: 'same-origin',
                                        headers: {
                                            Accept: 'application/json',
                                            'X-Requested-With':
                                                'XMLHttpRequest',
                                        },
                                    }
                                );

                                const result = await response
                                    .json()
                                    .catch(() => ({}));

                                if (!response.ok) {
                                    const errors = result.errors
                                        ? Object.values(
                                            result.errors
                                        ).flat()
                                        : [];

                                    throw new Error(
                                        errors[0]
                                        ?? result.message
                                        ?? 'Pesan gagal dikirim.'
                                    );
                                }

                                appendMessage(result.message);
                                scheduleExpiry(
                                    result.access_expires_at
                                );
                                form.reset();

                                if (!result.realtime_delivered) {
                                    setStatus(
                                        'disconnected',
                                        'Pesan tersimpan; realtime offline'
                                    );
                                }
                            } catch (error) {
                                showError(form, error.message);
                            } finally {
                                button.disabled = false;
                            }
                        }
                    );
                });

            function initializeRealtime() {
                if (initialized || !window.Echo) return;
                initialized = true;

                window.Echo
                    .private(channelName)
                    .listen('.message.sent', appendMessage);

                const connection = window.Echo
                    .connector
                    ?.pusher
                    ?.connection;

                connection?.bind('connected', () => {
                    setStatus(
                        'connected',
                        'Realtime terhubung'
                    );
                });

                connection?.bind('disconnected', () => {
                    setStatus(
                        'disconnected',
                        'Realtime terputus'
                    );
                });

                connection?.bind('unavailable', () => {
                    setStatus(
                        'disconnected',
                        'Server realtime tidak tersedia'
                    );
                });

                connection?.bind('error', () => {
                    setStatus(
                        'disconnected',
                        'Koneksi realtime bermasalah'
                    );
                });
            }

            scrollBottom();

            @if (! auth('admin')->check())
                scheduleExpiry(
                    @json(
                        auth('patient')
                            ->user()
                            ?->expires_at
                            ?->toIso8601String()
                    )
                );
            @endif

            if (window.Echo) {
                initializeRealtime();
            } else {
                window.addEventListener(
                    'md-farma:echo-ready',
                    initializeRealtime,
                    { once:true }
                );
            }

            window.addEventListener('beforeunload', () => {
                window.Echo?.leave(channelName);
            });
        })();
    </script>
</body>
</html>
