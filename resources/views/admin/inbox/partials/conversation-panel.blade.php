@php
    $lastDateKey = null;
    $startedLocal = $consultation->created_at
        ->copy()
        ->timezone($timezone);

    $classificationOptions =
        \App\Models\Consultation::serviceClassificationOptions();

    $classificationNoticeTemplates =
        \App\Models\Consultation::CLASSIFICATION_NOTICE_TEMPLATES;

    $classifiedAtLocal = $consultation->classified_at
        ?->copy()
        ->timezone($timezone);

    $screeningProgress = $consultation->screeningProgress();
    $outcomeProgress = $consultation->outcomeProgress();
    $isReadOnly = $consultation->status === 'selesai';
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
                        data-finish-button
                        title="{{
                            $screeningProgress['is_complete']
                                && $outcomeProgress['is_complete']
                                ? 'Tandai konsultasi sebagai selesai'
                                : 'Lengkapi klasifikasi, skrining, dan hasil akhir sebelum menyelesaikan konsultasi'
                        }}"
                        @disabled(
                            ! $screeningProgress['is_complete']
                            || ! $outcomeProgress['is_complete']
                        )
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

    <section
        class="classification-bar"
        aria-label="Klasifikasi pelayanan kefarmasian"
    >
        <div class="classification-summary">
            <span class="classification-eyebrow">
                Klasifikasi pelayanan
            </span>

            <div class="classification-status-row">
                <strong
                    class="classification-chip classification-{{
                        $consultation->service_classification
                            ?: 'unset'
                    }}"
                    data-classification-label
                >
                    {{ $consultation->serviceClassificationLabel() }}
                </strong>

                <span class="classification-origin">
                    Pilihan pasien:
                    <b>
                        {{
                            $consultation->jenis_konsultasi === 'resep'
                                ? 'Memiliki resep'
                                : 'Tanpa resep'
                        }}
                    </b>
                </span>


                <span
                    class="screening-chip screening-{{
                        $screeningProgress['class']
                    }}"
                    data-screening-chip
                    data-screening-complete="{{
                        $screeningProgress['is_complete'] ? '1' : '0'
                    }}"
                >
                    {{ $screeningProgress['label'] }}
                </span>

                <span
                    class="outcome-chip outcome-{{
                        $outcomeProgress['class']
                    }}"
                    data-outcome-chip
                    data-outcome-complete="{{
                        $outcomeProgress['is_complete'] ? '1' : '0'
                    }}"
                >
                    {{ $outcomeProgress['label'] }}
                </span>
            </div>

            <small data-classification-meta>
                @if ($classifiedAtLocal)
                    Terakhir ditetapkan
                    {{
                        $classifiedAtLocal
                            ->locale('id')
                            ->isoFormat('D MMM YYYY, HH.mm')
                    }}
                    WIB
                @else
                    Tetapkan kategori aktual setelah meninjau percakapan.
                @endif
            </small>
        </div>

        @if (! $isReadOnly)
        <form
            class="classification-form"
            action="{{ route(
                'admin.inbox.classification',
                $consultation
            ) }}"
            method="POST"
            data-classification-form
            data-current-classification="{{
                $consultation->service_classification ?? ''
            }}"
        >
            @csrf

            <label for="serviceClassification">
                Ubah klasifikasi
            </label>

            <div class="classification-control">
                <select
                    id="serviceClassification"
                    name="service_classification"
                    required
                    data-classification-select
                >
                    <option value="" disabled {{
                        $consultation->service_classification
                            ? ''
                            : 'selected'
                    }}>
                        Pilih kategori pelayanan
                    </option>

                    @foreach ($classificationOptions as $value => $label)
                        <option
                            value="{{ $value }}"
                            data-notice-message="{{
                                $classificationNoticeTemplates[$value]['message']
                                    ?? ''
                            }}"
                            @selected(
                                $consultation->service_classification
                                    === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button
                    type="submit"
                    data-classification-submit
                >
                    Simpan
                </button>
            </div>

            <div
                class="classification-reason"
                data-classification-reason
                hidden
            >
                <label for="classificationReason">
                    Alasan perubahan
                    <span>Wajib</span>
                </label>

                <textarea
                    id="classificationReason"
                    name="classification_reason"
                    rows="2"
                    maxlength="1000"
                    placeholder="Jelaskan alasan kategori pelayanan diubah."
                    data-classification-reason-input
                ></textarea>

                <small>
                    Alasan tersimpan pada audit internal dan tidak dikirim
                    kepada pasien.
                </small>
            </div>

            <div
                class="classification-notice-preview"
                data-classification-notice-preview
                hidden
            >
                <label class="classification-notice-toggle">
                    <input
                        type="checkbox"
                        name="send_classification_notice"
                        value="1"
                        @checked($consultation->status === 'aktif')
                        @disabled($consultation->status !== 'aktif')
                        data-classification-notice-toggle
                    >
                    <span>
                        {{
                            $consultation->status === 'aktif'
                                ? 'Kirim pemberitahuan ini kepada pasien'
                                : 'Aktifkan kembali konsultasi untuk mengirim pemberitahuan'
                        }}
                    </span>
                </label>

                <div class="classification-notice-card">
                    <strong>
                        Pratinjau pesan otomatis
                    </strong>
                    <p data-classification-notice-text></p>
                    <small>
                        Pesan dikirim sebagai pemberitahuan berdasarkan
                        keputusan petugas yang menangani dan disimpan sebagai
                        snapshot audit. Isi pratinjau tidak dapat diedit.
                    </small>
                </div>
            </div>

            <span
                class="classification-feedback"
                data-classification-feedback
                aria-live="polite"
            ></span>
        </form>
        @else
            <div class="consultation-readonly-notice">
                <strong>Konsultasi selesai — hanya-baca</strong>
                <p>
                    Klasifikasi, skrining, dan hasil akhir dikunci untuk menjaga
                    integritas catatan. Aktifkan kembali konsultasi dengan alasan
                    yang jelas sebelum melakukan perubahan.
                </p>
            </div>
        @endif

        <div
            class="screening-slot"
            data-screening-slot
        >
            @include(
                'admin.inbox.partials.screening-panel',
                compact('consultation', 'timezone')
            )
        </div>

        <div
            class="outcome-slot"
            data-outcome-slot
        >
            @include(
                'admin.inbox.partials.outcome-panel',
                compact('consultation', 'timezone')
            )
        </div>

        <div
            class="classification-history-slot"
            data-classification-history-slot
        >
            @include(
                'admin.inbox.partials.classification-history',
                compact('consultation', 'timezone')
            )
        </div>

        @include(
            'admin.inbox.partials.status-history',
            compact('consultation', 'timezone')
        )
    </section>

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
                }} {{
                    $message->isClassificationNotice()
                        ? 'classification-notice'
                        : ''
                }}"
                data-message-id="{{ $message->id }}"
                data-message-sender="{{ $message->sender }}"
            >
                <span class="message-sender">
                    {{
                        $message->isClassificationNotice()
                            ? 'Pemberitahuan layanan · MD Farma'
                            : ($message->sender === 'admin'
                                ? 'Apoteker'
                                : $consultation->nama)
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
                    <label class="reopen-reason-field">
                        <span>Alasan mengaktifkan kembali</span>
                        <textarea
                            name="status_reason"
                            rows="2"
                            minlength="10"
                            maxlength="1000"
                            required
                            placeholder="Contoh: pasien memberikan informasi tambahan yang perlu ditinjau."
                        ></textarea>
                    </label>
                    <button type="submit" class="reopen-button">
                        Aktifkan kembali
                    </button>
                </form>
            </div>
        @endif
    </footer>
</section>
