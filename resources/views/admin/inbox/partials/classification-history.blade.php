@php
    $classificationOptions =
        \App\Models\Consultation::serviceClassificationOptions();

    $classificationLogs = $consultation->classificationLogs;
@endphp

<details
    class="classification-history"
    data-classification-history-details
>
    <summary>
        <span>
            Riwayat klasifikasi
            <b data-classification-history-count>
                {{ $classificationLogs->count() }}
            </b>
        </span>

        <small>Audit internal</small>
    </summary>

    <div class="classification-history-body">
        @forelse ($classificationLogs as $log)
            @php
                $changedAtLocal = $log->created_at
                    ?->copy()
                    ->timezone($timezone);

                $previousLabel = $log->previous_classification
                    ? ($classificationOptions[
                        $log->previous_classification
                    ] ?? $log->previous_classification)
                    : null;

                $newLabel = $classificationOptions[
                    $log->new_classification
                ] ?? $log->new_classification;
            @endphp

            <article class="classification-history-item">
                <span
                    class="classification-history-dot"
                    aria-hidden="true"
                ></span>

                <div class="classification-history-content">
                    <div class="classification-history-change">
                        @if ($previousLabel)
                            <span>{{ $previousLabel }}</span>
                            <span aria-hidden="true">→</span>
                        @else
                            <span>Klasifikasi awal</span>
                            <span aria-hidden="true">→</span>
                        @endif

                        <strong>{{ $newLabel }}</strong>
                    </div>

                    <div class="classification-history-meta">
                        <span>
                            {{ $log->admin?->username ?? 'Admin tidak tersedia' }}
                        </span>

                        @if ($log->notice)
                            <span aria-hidden="true">•</span>
                            <span class="classification-history-notice">
                                Pemberitahuan terkirim
                            </span>
                        @endif

                        @if ($changedAtLocal)
                            <span aria-hidden="true">•</span>
                            <time datetime="{{ $log->created_at->toIso8601String() }}">
                                {{
                                    $changedAtLocal
                                        ->locale('id')
                                        ->isoFormat('D MMM YYYY, HH.mm')
                                }}
                                WIB
                            </time>
                        @endif
                    </div>

                    @if ($log->reason)
                        <p>{{ $log->reason }}</p>
                    @elseif (! $previousLabel)
                        <p>Kategori pertama setelah percakapan ditinjau.</p>
                    @endif
                </div>
            </article>
        @empty
            <p class="classification-history-empty">
                Belum ada perubahan klasifikasi yang tercatat.
            </p>
        @endforelse
    </div>
</details>
