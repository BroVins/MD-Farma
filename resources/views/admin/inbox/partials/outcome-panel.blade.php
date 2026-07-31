@php
    $outcomeTemplate = \App\Models\Consultation::finalOutcomeTemplate(
        $consultation->service_classification
    );
    $screeningProgress = $consultation->screeningProgress();
    $outcomeProgress = $consultation->outcomeProgress();
    $currentOutcome = $outcomeProgress['outcome'];
    $currentOutcomeAt = $currentOutcome?->created_at
        ?->copy()
        ->timezone($timezone);
    $outcomeSnapshots = $consultation->consultationOutcomes;
@endphp

<section
    class="outcome-panel outcome-{{ $outcomeProgress['class'] }}"
    data-outcome-panel
>
    @if (! $outcomeTemplate)
        <div class="outcome-empty">
            <div>
                <strong>Hasil akhir belum tersedia</strong>
                <p>
                    Tetapkan klasifikasi pelayanan terlebih dahulu.
                </p>
            </div>
        </div>
    @elseif (! $screeningProgress['is_complete'])
        <div class="outcome-empty">
            <div>
                <strong>Menunggu skrining lengkap</strong>
                <p>
                    Hasil akhir dapat ditetapkan setelah seluruh item skrining
                    wajib dan catatan yang diperlukan sudah lengkap.
                </p>
            </div>
        </div>
    @else
        <details
            class="outcome-details"
            data-outcome-details
            @if (! $outcomeProgress['is_complete']) open @endif
        >
            <summary>
                <span class="outcome-summary-copy">
                    <strong>{{ $outcomeTemplate['title'] }}</strong>
                    <small>{{ $outcomeTemplate['description'] }}</small>
                </span>

                <span
                    class="outcome-summary-status outcome-{{
                        $outcomeProgress['class']
                    }}"
                >
                    {{
                        $outcomeProgress['is_complete']
                            ? 'Sudah ditetapkan'
                            : 'Belum ditetapkan'
                    }}
                </span>
            </summary>

            <form
                class="outcome-form"
                action="{{ route(
                    'admin.inbox.outcome',
                    $consultation
                ) }}"
                method="POST"
                data-outcome-form
            >
                @csrf

                <div class="outcome-field">
                    <label for="consultationOutcome">
                        Hasil utama pelayanan
                        <span>Wajib</span>
                    </label>

                    <select
                        id="consultationOutcome"
                        name="outcome_code"
                        required
                    >
                        <option value="" disabled @selected(! $currentOutcome)>
                            Pilih hasil akhir pelayanan
                        </option>

                        @foreach ($outcomeTemplate['options'] as $code => $label)
                            <option
                                value="{{ $code }}"
                                @selected(
                                    $currentOutcome?->outcome_code === $code
                                )
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="outcome-field">
                    <label for="outcomeNotes">
                        {{ $outcomeTemplate['notes_label'] }}
                        @if ($outcomeProgress['notes_required'])
                            <span>Wajib</span>
                        @else
                            <small>Opsional</small>
                        @endif
                    </label>

                    <textarea
                        id="outcomeNotes"
                        name="notes"
                        rows="3"
                        maxlength="3000"
                        placeholder="Tuliskan ringkasan keputusan, produk atau arahan yang diberikan, serta tindak lanjut penting."
                        @required($outcomeProgress['notes_required'])
                    >{{ $currentOutcome?->notes }}</textarea>
                </div>

                <div class="outcome-form-footer">
                    <div class="outcome-audit-meta">
                        @if ($currentOutcome && $currentOutcomeAt)
                            <span>
                                Hasil aktif ditetapkan oleh
                                <b>
                                    {{
                                        $currentOutcome->admin?->username
                                            ?? 'Admin tidak tersedia'
                                    }}
                                </b>
                                pada
                                {{
                                    $currentOutcomeAt
                                        ->locale('id')
                                        ->isoFormat('D MMM YYYY, HH.mm')
                                }}
                                WIB.
                            </span>
                        @else
                            <span>
                                Belum ada hasil akhir untuk snapshot skrining
                                aktif.
                            </span>
                        @endif

                        <small>
                            Menyimpan hasil baru membuat snapshot audit. Jika
                            skrining diperbarui, hasil akhir harus ditetapkan
                            kembali sebelum konsultasi dapat diselesaikan.
                        </small>
                    </div>

                    <div class="outcome-submit-area">
                        <span
                            class="outcome-feedback"
                            data-outcome-feedback
                            aria-live="polite"
                        ></span>

                        <button type="submit" data-outcome-submit>
                            {{
                                $currentOutcome
                                    ? 'Simpan pembaruan'
                                    : 'Tetapkan hasil akhir'
                            }}
                        </button>
                    </div>
                </div>
            </form>
        </details>

        <div class="outcome-history">
            <details data-outcome-history-details>
                <summary>
                    <span>
                        <strong>Riwayat hasil akhir</strong>
                        <small>
                            Seluruh keputusan yang pernah disimpan untuk
                            konsultasi ini.
                        </small>
                    </span>
                    <b>{{ $outcomeSnapshots->count() }} snapshot</b>
                </summary>

                @if ($outcomeSnapshots->isEmpty())
                    <p class="outcome-history-empty">
                        Belum ada snapshot hasil akhir.
                    </p>
                @else
                    <div class="outcome-history-list">
                        @foreach ($outcomeSnapshots as $snapshot)
                            @php
                                $snapshotAt = $snapshot->created_at
                                    ->copy()
                                    ->timezone($timezone);
                                $isCurrent = $currentOutcome
                                    && (int) $currentOutcome->id
                                        === (int) $snapshot->id;
                                $classificationLabel =
                                    \App\Models\Consultation::SERVICE_CLASSIFICATIONS[
                                        $snapshot->service_classification
                                    ] ?? $snapshot->service_classification;
                            @endphp

                            <article class="outcome-history-entry {{
                                $isCurrent ? 'is-current' : ''
                            }}">
                                <div class="outcome-history-heading">
                                    <div>
                                        <strong>
                                            {{ $snapshot->outcome_label }}
                                        </strong>
                                        <small>
                                            {{ $classificationLabel }} ·
                                            {{
                                                $snapshotAt
                                                    ->locale('id')
                                                    ->isoFormat('D MMM YYYY, HH.mm')
                                            }} WIB
                                        </small>
                                    </div>

                                    @if ($isCurrent)
                                        <span>Hasil aktif</span>
                                    @endif
                                </div>

                                <p>
                                    {{
                                        $snapshot->notes
                                            ?: 'Tidak ada catatan tambahan.'
                                    }}
                                </p>

                                <footer>
                                    Disimpan oleh
                                    <b>
                                        {{
                                            $snapshot->admin?->username
                                                ?? 'Admin tidak tersedia'
                                        }}
                                    </b>
                                    · Snapshot #{{ $snapshot->id }}
                                </footer>
                            </article>
                        @endforeach
                    </div>
                @endif
            </details>
        </div>
    @endif
</section>
