@php
    $lastDateKey = null;
@endphp

<section
    class="conversation-shell"
    data-active-conversation="{{ $consultation->public_id }}"
    data-read-url="{{ route('admin.inbox.read', $consultation) }}"
>
    <header class="conversation-header">
        <button
            class="mobile-back"
            type="button"
            data-mobile-back
            aria-label="Kembali ke daftar percakapan"
        >
            ←
        </button>

        <span class="conversation-avatar large" aria-hidden="true">
            {{ mb_strtoupper(mb_substr($consultation->nama, 0, 1)) }}
        </span>

        <div class="conversation-heading">
            <strong>{{ $consultation->nama }}</strong>
            <span>
                {{
                    $consultation->jenis_konsultasi === 'resep'
                        ? 'Resep dokter'
                        : 'Non resep'
                }}
                · {{ $consultation->inbox_state_label }}
            </span>
        </div>

        <div class="conversation-header-actions">
            <button
                class="header-action secondary"
                type="button"
                data-toggle-patient
            >
                Detail
            </button>
</div>
    </header>

    <div
        class="message-stream"
        id="messageStream"
        data-last-date="{{
            $consultation->messages->last()?->created_at
                ?->copy()
                ->timezone($timezone)
                ->format('Y-m-d')
        }}"
        aria-live="polite"
    >
        @forelse ($consultation->messages as $message)
            @php
                $localMessageTime = $message->created_at
                    ->copy()
                    ->timezone($timezone);
                $dateKey = $localMessageTime->format('Y-m-d');
            @endphp

            @if ($lastDateKey !== $dateKey)
                <div
                    class="date-divider"
                    data-date-key="{{ $dateKey }}"
                >
                    <span>
                        {{
                            $localMessageTime
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY')
                        }}
                    </span>
                </div>

                @php
                    $lastDateKey = $dateKey;
                @endphp
            @endif

            <article
                class="message-bubble {{
                    $message->sender === 'admin'
                        ? 'admin'
                        : 'patient'
                }}"
                data-message-id="{{ $message->id }}"
            >
                <span class="message-sender">
                    {{
                        $message->sender === 'admin'
                            ? 'Apoteker'
                            : $consultation->nama
                    }}
                </span>

                @if ($message->message)
                    <p>{{ $message->message }}</p>
                @endif

                @if ($message->image)
                    <a
                        class="message-attachment"
                        href="{{
                            route(
                                'chat.attachment',
                                [
                                    'consultation' => $consultation,
                                    'message' => $message,
                                ]
                            )
                        }}"
                        target="_blank"
                        rel="noopener"
                    >
                        <img
                            src="{{
                                route(
                                    'chat.attachment',
                                    [
                                        'consultation' => $consultation,
                                        'message' => $message,
                                    ]
                                )
                            }}"
                            alt="Lampiran konsultasi"
                            loading="lazy"
                        >
                    </a>
                @endif

                <time
                    class="message-time"
                    datetime="{{ $message->created_at->toIso8601String() }}"
                    title="{{
                        $localMessageTime
                            ->locale('id')
                            ->isoFormat(
                                'dddd, D MMMM YYYY [pukul] HH.mm.ss'
                            )
                    }} WIB"
                >
                    {{ $localMessageTime->format('H.i') }} WIB
                </time>
            </article>
        @empty
            <div class="message-empty" data-empty-message>
                <span>👋</span>
                <strong>Konsultasi baru</strong>
                <p>
                    Pasien belum mengirim pesan. Admin dapat
                    menunggu aktivitas berikutnya dari pasien.
                </p>
            </div>
        @endforelse
    </div>

    <footer class="composer-area">
        <div
            class="composer-error"
            data-composer-error
            role="alert"
        ></div>

        @if ($consultation->status === 'aktif')
            <form
                class="reply-form"
                action="{{ route('admin.chat.reply', $consultation) }}"
                method="POST"
                enctype="multipart/form-data"
                data-reply-form
            >
                @csrf

                <label
                    class="image-picker"
                    title="Kirim gambar"
                    aria-label="Pilih gambar"
                >
                    <span aria-hidden="true">📎</span>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        data-reply-image
                    >
                </label>

                <div class="composer-input-stack">
                    <textarea
                        name="message"
                        rows="1"
                        maxlength="2000"
                        placeholder="Tulis balasan sebagai apoteker..."
                        autocomplete="off"
                        data-reply-input
                    ></textarea>

                    <div
                        class="selected-image"
                        data-image-preview
                        hidden
                    >
                        <img
                            src=""
                            alt="Pratinjau gambar"
                            data-image-preview-src
                        >

                        <div>
                            <strong data-image-name></strong>
                            <small>Maksimum 2 MB · JPG, PNG, atau WebP</small>
                        </div>

                        <button
                            type="button"
                            data-remove-image
                            aria-label="Hapus gambar yang dipilih"
                        >
                            ×
                        </button>
                    </div>
                </div>

                <button
                    class="send-reply"
                    type="submit"
                    data-send-button
                >
                    Kirim
                </button>
            </form>

            <form
                class="status-inline-form"
                action="{{ route('admin.chat.status', $consultation) }}"
                method="POST"
                data-status-form
            >
                @csrf
                <input type="hidden" name="status" value="selesai">

                <button type="submit" class="finish-button">
                    ✓ Tandai selesai
                </button>
            </form>
        @else
            <div class="closed-conversation">
                <span>
                    Konsultasi ini sudah selesai. Aktifkan kembali
                    jika admin perlu mengirim balasan tambahan.
                </span>

                <form
                    action="{{ route('admin.chat.status', $consultation) }}"
                    method="POST"
                    data-status-form
                >
                    @csrf
                    <input type="hidden" name="status" value="aktif">

                    <button type="submit" class="reopen-button">
                        Aktifkan kembali
                    </button>
                </form>
            </div>
        @endif
    </footer>
</section>
