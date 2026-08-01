@php
    $screeningTemplate = \App\Models\Consultation::screeningTemplate(
        $consultation->service_classification
    );
    $screeningProgress = $consultation->screeningProgress();
    $currentScreening = $screeningProgress['screening'];
    $currentClassificationLog = $consultation
        ->currentClassificationLog();

    $screeningRevisionCount = $consultation
        ->classificationScreenings
        ->filter(function ($screening) use (
            $consultation,
            $currentClassificationLog
        ) {
            if (
                $screening->service_classification
                    !== $consultation->service_classification
            ) {
                return false;
            }

            if ($currentClassificationLog) {
                return (int) $screening->classification_log_id
                    === (int) $currentClassificationLog->id;
            }

            return $screening->classification_log_id === null;
        })
        ->count();

    $screeningSavedAt = $currentScreening?->created_at
        ?->copy()
        ->timezone($timezone);
    $isReadOnly = $consultation->status === 'selesai';
@endphp

<div
    class="screening-panel screening-{{ $screeningProgress['class'] }}"
    data-screening-panel
>
    @if (! $screeningTemplate)
        <div class="screening-empty">
            <div>
                <strong>Skrining belum tersedia</strong>
                <p>
                    Tetapkan klasifikasi pelayanan terlebih dahulu agar
                    checklist yang sesuai dapat ditampilkan.
                </p>
            </div>
        </div>
    @else
        <details
            class="screening-details"
            data-screening-details
            @if (! $screeningProgress['is_complete']) open @endif
        >
            <summary>
                <span class="screening-summary-copy">
                    <strong>{{ $screeningTemplate['title'] }}</strong>
                    <small>
                        {{ $screeningTemplate['description'] }}
                    </small>
                </span>

                <span
                    class="screening-summary-progress screening-{{
                        $screeningProgress['class']
                    }}"
                >
                    {{
                        $screeningProgress['is_complete']
                            ? 'Lengkap'
                            : $screeningProgress['completed'].'/'
                                .$screeningProgress['required']
                    }}
                </span>
            </summary>

            <form
                class="screening-form"
                action="{{ route(
                    'admin.inbox.screening',
                    $consultation
                ) }}"
                method="POST"
                data-screening-form
            >
                @csrf

                <div class="screening-checklist">
                    @foreach (
                        $screeningTemplate['items'] as $key => $label
                    )
                        <label class="screening-check-item">
                            <input
                                type="checkbox"
                                name="answers[{{ $key }}]"
                                value="1"
                                @checked(
                                    (bool) (
                                        $screeningProgress['answers'][$key]
                                            ?? false
                                    )
                                )
                                data-screening-check
                                @disabled($isReadOnly)
                            >
                            <span aria-hidden="true"></span>
                            <b>{{ $label }}</b>
                        </label>
                    @endforeach
                </div>

                <div class="screening-notes-field">
                    <label for="screeningNotes">
                        {{ $screeningTemplate['notes_label'] }}
                        @if ($screeningProgress['notes_required'])
                            <span>Wajib agar lengkap</span>
                        @else
                            <small>Opsional</small>
                        @endif
                    </label>

                    <textarea
                        id="screeningNotes"
                        name="notes"
                        rows="3"
                        maxlength="2000"
                        placeholder="Tambahkan ringkasan keputusan, temuan penting, atau tindak lanjut."
                        data-screening-notes
                        @readonly($isReadOnly)
                    >{{ $screeningProgress['notes'] }}</textarea>
                </div>

                <div class="screening-form-footer">
                    <div class="screening-audit-meta">
                        @if ($currentScreening && $screeningSavedAt)
                            <span>
                                Terakhir disimpan oleh
                                <b>
                                    {{
                                        $currentScreening->admin?->username
                                            ?? 'Admin tidak tersedia'
                                    }}
                                </b>
                                pada
                                {{
                                    $screeningSavedAt
                                        ->locale('id')
                                        ->isoFormat('D MMM YYYY, HH.mm')
                                }}
                                WIB.
                            </span>

                            <span>
                                {{ $screeningRevisionCount }} snapshot
                                skrining tersimpan.
                            </span>
                        @else
                            <span>
                                Belum ada hasil skrining yang disimpan untuk
                                klasifikasi ini.
                            </span>
                        @endif

                        <small>
                            Setiap penyimpanan membuat snapshot audit baru.
                            Konsultasi hanya dapat ditandai selesai setelah
                            skrining lengkap.
                        </small>
                    </div>

                    <div class="screening-submit-area">
                        <span
                            class="screening-feedback"
                            data-screening-feedback
                            aria-live="polite"
                        ></span>

                        <button
                            type="submit"
                            data-screening-submit
                            @disabled($isReadOnly)
                        >
                            {{
                                $isReadOnly
                                    ? 'Hanya-baca'
                                    : ($screeningProgress['is_complete']
                                        ? 'Simpan pembaruan'
                                        : 'Simpan progres')
                            }}
                        </button>
                    </div>
                </div>
            </form>
        </details>

        @include(
            'admin.inbox.partials.screening-history',
            compact(
                'consultation',
                'timezone',
                'currentScreening'
            )
        )
    @endif
</div>
