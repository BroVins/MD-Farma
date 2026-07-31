@php
    $screeningSnapshots = $consultation->classificationScreenings;
    $activeScreeningId = $currentScreening?->id;
@endphp

<section class="screening-history" aria-label="Riwayat snapshot skrining">
    <details data-screening-history-details>
        <summary>
            <span class="screening-history-heading">
                <strong>Riwayat skrining</strong>
                <small>
                    Seluruh snapshot tersimpan sebagai catatan internal dan
                    tidak ditampilkan kepada pasien.
                </small>
            </span>

            <span class="screening-history-count">
                {{ $screeningSnapshots->count() }} snapshot
            </span>
        </summary>

        @if ($screeningSnapshots->isEmpty())
            <p class="screening-history-empty">
                Belum ada snapshot skrining yang tersimpan.
            </p>
        @else
            <div class="screening-history-list">
                @foreach ($screeningSnapshots as $snapshot)
                    @php
                        $snapshotTemplate =
                            \App\Models\Consultation::screeningTemplate(
                                $snapshot->service_classification
                            );

                        $snapshotClassificationLabel =
                            \App\Models\Consultation::SERVICE_CLASSIFICATIONS[
                                $snapshot->service_classification
                            ] ?? $snapshot->service_classification;

                        $snapshotAnswers = is_array($snapshot->answers)
                            ? $snapshot->answers
                            : [];

                        $snapshotLocal = $snapshot->created_at
                            ?->copy()
                            ->timezone($timezone);

                        $snapshotStatusClass = $snapshot->is_complete
                            ? 'complete'
                            : ($snapshot->completed_count > 0
                                ? 'partial'
                                : 'pending');

                        $isActiveSnapshot = (int) $activeScreeningId
                            === (int) $snapshot->id;
                    @endphp

                    <details
                        class="screening-history-entry screening-history-{{ $snapshotStatusClass }} {{ $isActiveSnapshot ? 'is-current' : '' }}"
                        data-screening-snapshot-details="{{ $snapshot->id }}"
                        @if ($loop->first) open @endif
                    >
                        <summary>
                            <span class="screening-history-entry-title">
                                <span class="screening-history-entry-row">
                                    <strong>
                                        {{ $snapshotClassificationLabel }}
                                    </strong>

                                    @if ($isActiveSnapshot)
                                        <span class="screening-history-current">
                                            Snapshot aktif
                                        </span>
                                    @endif
                                </span>

                                <small>
                                    Disimpan oleh
                                    <b>
                                        {{
                                            $snapshot->admin?->username
                                                ?? 'Admin tidak tersedia'
                                        }}
                                    </b>
                                    @if ($snapshotLocal)
                                        pada
                                        {{
                                            $snapshotLocal
                                                ->locale('id')
                                                ->isoFormat('D MMM YYYY, HH.mm')
                                        }}
                                        WIB
                                    @endif
                                </small>
                            </span>

                            <span
                                class="screening-history-progress screening-{{ $snapshotStatusClass }}"
                            >
                                {{
                                    $snapshot->is_complete
                                        ? 'Lengkap'
                                        : $snapshot->completed_count.'/'
                                            .$snapshot->required_count
                                }}
                            </span>
                        </summary>

                        <div class="screening-history-content">
                            @if ($snapshotTemplate)
                                <div class="screening-history-items">
                                    @foreach ($snapshotTemplate['items'] as $answerKey => $answerLabel)
                                        @php
                                            $answerChecked = (bool) (
                                                $snapshotAnswers[$answerKey]
                                                    ?? false
                                            );
                                        @endphp

                                        <div
                                            class="screening-history-item {{ $answerChecked ? 'checked' : 'unchecked' }}"
                                        >
                                            <span aria-hidden="true">
                                                {{ $answerChecked ? '✓' : '–' }}
                                            </span>
                                            <p>{{ $answerLabel }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="screening-history-template-missing">
                                    Template skrining untuk kategori ini tidak
                                    lagi tersedia. Data mentah snapshot tetap
                                    tersimpan.
                                </p>
                            @endif

                            <div class="screening-history-notes">
                                <strong>Catatan petugas</strong>
                                <p>
                                    {{
                                        filled($snapshot->notes)
                                            ? $snapshot->notes
                                            : 'Tidak ada catatan tambahan.'
                                    }}
                                </p>
                            </div>

                            <div class="screening-history-footer">
                                <span>Snapshot #{{ $snapshot->id }}</span>
                                <span>
                                    {{ $snapshot->completed_count }}/{{ $snapshot->required_count }} item terpenuhi
                                </span>
                                <span>
                                    {{
                                        $snapshot->is_complete
                                            ? 'Status akhir: lengkap'
                                            : 'Status akhir: belum lengkap'
                                    }}
                                </span>
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </details>
</section>
