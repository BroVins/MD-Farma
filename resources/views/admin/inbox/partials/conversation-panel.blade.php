@php
    $lastDateKey = null;
    $startedLocal = $consultation->created_at
        ->copy()
        ->timezone($timezone);
@endphp

<section
    class="conversation-shell"
    data-active-conversation="{{ $consultation->public_id }}"
    data-read-url="{{ route('admin.inbox.read', $consultation) }}"
    data-messages-url="{{ route('admin.inbox.messages', $consultation) }}"
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

            <div class="conversation-heading-meta">
                <span class="type-chip">
                    {{
                        $consultation->jenis_konsultasi === 'resep'
                            ? 'Resep dokter'
                            : 'Non resep'
                    }}
                </span>

                <span
                    class="state-chip state-{{
                        $consultation->inbox_state
                    }}"
                >
                    {{ $consultation->inbox_state_label }}
                </span>

                <span class="conversation-started">
                    Dimulai
                    {{
                        $startedLocal
                            ->locale('id')
                            ->isoFormat('D MMM YYYY, HH.mm')
                    }}
                    WIB
                </span>
            </div>
        </div>

        <div class="conversation-header-actions">
            @if ($consultation->status === 'aktif')
                <form
                    class="header-status-form"
                    action="{{ route('admin.chat.status', $consultation) }}"
                    method="POST"
                    data-status-form
                >
                    @csrf
                    <input type="hidden" name="status" value="selesai">

                    <button
                        type="submit"
                        class="header-action finish-action"
                        title="Tandai konsultasi sebagai selesai"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        <span>Tandai selesai</span>
                    </button>
                </form>
            @endif

            <button
                class="header-action secondary"
                type="button"
                data-toggle-patient
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                </svg>
                <span>Detail</span>
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
                data-message-sender="{{ $message->sender }}"
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
                    @if ($message->isImageAttachment())
                        <a
                            class="message-attachment"
                            href="{{ route('chat.attachment', [
                                'consultation' => $consultation,
                                'message' => $message,
                            ]) }}"
                            target="_blank"
                            rel="noopener"
                        >
                            <img
                                src="{{ route('chat.attachment', [
                                    'consultation' => $consultation,
                                    'message' => $message,
                                ]) }}"
                                alt="{{ $message->attachmentName() }}"
                                loading="lazy"
                            >
                        </a>
                    @else
                        <a
                            class="message-attachment document-attachment"
                            href="{{ route('chat.attachment', [
                                'consultation' => $consultation,
                                'message' => $message,
                            ]) }}"
                            target="_blank"
                            rel="noopener"
                            download
                        >
                            <span class="document-icon" aria-hidden="true">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M12 18v-6"/>
                                    <path d="m9 15 3 3 3-3"/>
                                </svg>
                            </span>

                            <span class="document-copy">
                                <strong>
                                    {{ $message->attachmentName() }}
                                </strong>
                                <small>
                                    {{
                                        $message->attachmentExtension()
                                            ?: 'dokumen'
                                    }}
                                </small>
                            </span>
                        </a>
                    @endif
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
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M21.44 11.05 12.25 20.24a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                    </svg>

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
                            <small>
                                Maksimum 2 MB · JPG, PNG, atau WebP
                            </small>
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
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m22 2-7 20-4-9-9-4Z"/>
                        <path d="M22 2 11 13"/>
                    </svg>
                    <span>Kirim</span>
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
