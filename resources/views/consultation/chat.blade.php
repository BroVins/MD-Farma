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
        * {
            box-sizing: border-box;
        }

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

        nav a,
        nav span {
            color: white;
            text-decoration: none;
        }

        nav form {
            display: inline;
        }

        .nav-links,
        .admin-menu {
            display: flex;
            align-items: center;
            gap: 14px;
        }

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

        h1 {
            text-align: center;
            color: #198754;
            margin-bottom: 8px;
        }

        .connection-row {
            text-align: center;
            margin-bottom: 20px;
        }

        .connection-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            color: #4b5563;
        }

        .connection-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #d97706;
        }

        .connection-status.connected
        .connection-dot {
            background: #16a34a;
        }

        .connection-status.disconnected
        .connection-dot {
            background: #dc2626;
        }

        .patient p {
            margin: 8px 0;
        }

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

        .message-time {
            display: block;
            margin-top: 7px;
            font-size: 11px;
            opacity: .8;
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

        input[type="file"] {
            margin: 12px 0;
        }

        .send-button {
            padding: 12px 20px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .send-button:disabled {
            opacity: .55;
            cursor: wait;
        }

        .info {
            font-size: 14px;
            color: #666;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .form-error {
            display: none;
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
        }

        .form-error.visible {
            display: block;
        }

        @media (max-width: 600px) {
            nav {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-row {
                flex-direction: column;
            }

            .message {
                max-width: 90%;
            }
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
                <a href="{{ route('home') }}">
                    ← Beranda
                </a>

                <a href="{{ route('consultation.create') }}">
                    Konsultasi Baru
                </a>
            @endif
        </div>

        @if (auth('admin')->check())
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
                        class="link-button"
                        type="submit"
                    >
                        Logout
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('admin.login') }}">
                Login Admin
            </a>
        @endif
    </nav>

    <main class="container">
        <h1>Live Chat Apotek MD Farma</h1>

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

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="card patient">
            <h3>Data Pasien</h3>

            <p>
                <strong>Nama:</strong>
                {{ $consultation->nama }}
            </p>

            <p>
                <strong>Umur:</strong>
                {{ $consultation->umur }}
            </p>

            <p>
                <strong>No HP:</strong>
                {{ $consultation->no_hp }}
            </p>

            <p>
                <strong>Jenis Konsultasi:</strong>
                {{
                    $consultation->jenis_konsultasi === 'resep'
                        ? 'Resep Dokter'
                        : 'Non Resep'
                }}
            </p>
        </section>

        <section class="card">
            <h3>Riwayat Chat</h3>

            <div
                class="chat-box"
                id="chatBox"
                aria-live="polite"
            >
                @forelse ($consultation->messages as $chat)
                    <div
                        class="message {{
                            $chat->sender === 'admin'
                                ? 'admin'
                                : ''
                        }}"
                        data-message-id="{{ $chat->id }}"
                    >
                        <strong>
                            {{
                                $chat->sender === 'admin'
                                    ? 'Apoteker'
                                    : 'Pasien'
                            }}
                        </strong>

                        @if ($chat->message)
                            <br>
                            {{ $chat->message }}
                        @endif

                        @if ($chat->image)
                            <img
                                src="{{
                                    route(
                                        'chat.attachment',
                                        [
                                            'consultation' =>
                                                $consultation,
                                            'message' => $chat,
                                        ]
                                    )
                                }}"
                                alt="Lampiran chat"
                                width="180"
                            >
                        @endif

                        <small class="message-time">
                            {{
                                $chat->created_at
                                    ?->format('H:i')
                            }}
                        </small>
                    </div>
                @empty
                    <p data-empty-chat>
                        Belum ada pesan.
                    </p>
                @endforelse
            </div>
        </section>

        @if (auth('admin')->check())
            <section class="card">
                <h3>Balasan Apoteker</h3>

                <div
                    class="form-error"
                    data-form-error
                ></div>

                <form
                    class="realtime-form"
                    action="{{
                        route(
                            'admin.chat.reply',
                            $consultation
                        )
                    }}"
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

                        <button
                            class="send-button"
                            type="submit"
                        >
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </section>
        @else
            <section class="card">
                <h3>Kirim Pesan Pasien</h3>

                <div
                    class="form-error"
                    data-form-error
                ></div>

                <form
                    class="realtime-form"
                    action="{{
                        route(
                            'chat.send',
                            $consultation
                        )
                    }}"
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

                        <button
                            class="send-button"
                            type="submit"
                        >
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
                    Jangan membagikan perangkat saat chat aktif.
                </p>
            </section>
        @endif
    </main>

    <script>
        (() => {
            const consultationPublicId = @json(
                $consultation->public_id
            );

            const channelName =
                `consultation.${consultationPublicId}`;

            const chatBox =
                document.getElementById('chatBox');

            const connectionStatus =
                document.getElementById(
                    'connectionStatus'
                );

            let initialized = false;
            let sessionTimeoutId = null;

            function scrollToBottom() {
                chatBox.scrollTop =
                    chatBox.scrollHeight;
            }

            function setConnectionStatus(
                state,
                text
            ) {
                connectionStatus.classList.remove(
                    'connected',
                    'disconnected'
                );

                if (state) {
                    connectionStatus.classList.add(
                        state
                    );
                }

                connectionStatus
                    .querySelector('[data-status-text]')
                    .textContent = text;
            }

            function formatTime(value) {
                if (!value) {
                    return '';
                }

                const date = new Date(value);

                if (Number.isNaN(date.getTime())) {
                    return '';
                }

                return new Intl.DateTimeFormat(
                    'id-ID',
                    {
                        hour: '2-digit',
                        minute: '2-digit',
                    }
                ).format(date);
            }

            function appendMessage(data) {
                if (!data || !data.id) {
                    return;
                }

                const existing =
                    chatBox.querySelector(
                        `[data-message-id="${data.id}"]`
                    );

                if (existing) {
                    return;
                }

                chatBox
                    .querySelector('[data-empty-chat]')
                    ?.remove();

                const bubble =
                    document.createElement('div');

                bubble.className =
                    data.sender === 'admin'
                        ? 'message admin'
                        : 'message';

                bubble.dataset.messageId = data.id;

                const sender =
                    document.createElement('strong');

                sender.textContent =
                    data.sender === 'admin'
                        ? 'Apoteker'
                        : 'Pasien';

                bubble.appendChild(sender);

                if (data.message) {
                    bubble.appendChild(
                        document.createElement('br')
                    );

                    bubble.appendChild(
                        document.createTextNode(
                            data.message
                        )
                    );
                }

                if (data.attachment_url) {
                    const image =
                        document.createElement('img');

                    image.src = data.attachment_url;
                    image.alt = 'Lampiran chat';
                    image.width = 180;
                    image.loading = 'lazy';

                    bubble.appendChild(image);
                }

                const time =
                    document.createElement('small');

                time.className = 'message-time';
                time.textContent = formatTime(
                    data.created_at
                );

                bubble.appendChild(time);
                chatBox.appendChild(bubble);
                scrollToBottom();
            }

            function showFormError(
                form,
                message
            ) {
                const section =
                    form.closest('.card');

                const errorBox =
                    section?.querySelector(
                        '[data-form-error]'
                    );

                if (!errorBox) {
                    return;
                }

                errorBox.textContent = message;
                errorBox.classList.add('visible');
            }

            function clearFormError(form) {
                const section =
                    form.closest('.card');

                const errorBox =
                    section?.querySelector(
                        '[data-form-error]'
                    );

                if (!errorBox) {
                    return;
                }

                errorBox.textContent = '';
                errorBox.classList.remove(
                    'visible'
                );
            }

            function scheduleSessionExpiry(
                expiresAt
            ) {
                if (!expiresAt) {
                    return;
                }

                window.clearTimeout(
                    sessionTimeoutId
                );

                const delay =
                    new Date(expiresAt).getTime()
                    - Date.now();

                if (delay <= 0) {
                    expirePatientSession();
                    return;
                }

                sessionTimeoutId =
                    window.setTimeout(
                        expirePatientSession,
                        delay
                    );
            }

            function expirePatientSession() {
                window.Echo?.leave(channelName);

                setConnectionStatus(
                    'disconnected',
                    'Sesi pasien telah berakhir'
                );

                document
                    .querySelectorAll(
                        '.realtime-form input, ' +
                        '.realtime-form button'
                    )
                    .forEach((element) => {
                        element.disabled = true;
                    });
            }

            function bindForms() {
                document
                    .querySelectorAll(
                        '.realtime-form'
                    )
                    .forEach((form) => {
                        form.addEventListener(
                            'submit',
                            async (event) => {
                                event.preventDefault();
                                clearFormError(form);

                                const button =
                                    form.querySelector(
                                        'button[type="submit"]'
                                    );

                                button.disabled = true;

                                try {
                                    const response =
                                        await fetch(
                                            form.action,
                                            {
                                                method:
                                                    'POST',
                                                body:
                                                    new FormData(
                                                        form
                                                    ),
                                                credentials:
                                                    'same-origin',
                                                headers: {
                                                    Accept:
                                                        'application/json',
                                                    'X-Requested-With':
                                                        'XMLHttpRequest',
                                                },
                                            }
                                        );

                                    const result =
                                        await response
                                            .json()
                                            .catch(() => ({}));

                                    if (!response.ok) {
                                        const errors =
                                            result.errors
                                                ? Object
                                                    .values(
                                                        result.errors
                                                    )
                                                    .flat()
                                                : [];

                                        throw new Error(
                                            errors[0]
                                            ?? result.message
                                            ?? 'Pesan gagal dikirim.'
                                        );
                                    }

                                    /*
                                     * Event Reverb dapat tiba sebelum
                                     * response HTTP. appendMessage
                                     * memiliki deduplikasi berdasarkan ID.
                                     */
                                    appendMessage(
                                        result.message
                                    );

                                    scheduleSessionExpiry(
                                        result
                                            .access_expires_at
                                    );

                                    form.reset();

                                    if (
                                        !result
                                            .realtime_delivered
                                    ) {
                                        setConnectionStatus(
                                            'disconnected',
                                            'Pesan tersimpan; realtime sedang offline'
                                        );
                                    }
                                } catch (error) {
                                    showFormError(
                                        form,
                                        error.message
                                    );
                                } finally {
                                    button.disabled =
                                        false;
                                }
                            }
                        );
                    });
            }

            function initializeRealtime() {
                if (
                    initialized
                    || !window.Echo
                ) {
                    return;
                }

                initialized = true;

                const channel =
                    window.Echo.private(
                        channelName
                    );

                channel.listen(
                    '.message.sent',
                    appendMessage
                );

                const connection =
                    window.Echo
                        .connector
                        ?.pusher
                        ?.connection;

                connection?.bind(
                    'connected',
                    () => {
                        setConnectionStatus(
                            'connected',
                            'Realtime terhubung'
                        );
                    }
                );

                connection?.bind(
                    'disconnected',
                    () => {
                        setConnectionStatus(
                            'disconnected',
                            'Realtime terputus'
                        );
                    }
                );

                connection?.bind(
                    'unavailable',
                    () => {
                        setConnectionStatus(
                            'disconnected',
                            'Server realtime tidak tersedia'
                        );
                    }
                );

                connection?.bind(
                    'error',
                    () => {
                        setConnectionStatus(
                            'disconnected',
                            'Koneksi realtime bermasalah'
                        );
                    }
                );
            }

            bindForms();
            scrollToBottom();

            @if (! auth('admin')->check())
                scheduleSessionExpiry(
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
                    {
                        once: true,
                    }
                );
            }

            window.addEventListener(
                'beforeunload',
                () => {
                    window.Echo?.leave(
                        channelName
                    );
                }
            );
        })();
    </script>
</body>
</html>
