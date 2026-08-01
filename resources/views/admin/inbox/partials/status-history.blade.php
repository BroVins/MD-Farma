@php
    $statusLogs = $consultation->statusLogs;
@endphp

<details class="status-history">
    <summary>
        <span>
            Riwayat status
            <b>{{ $statusLogs->count() }}</b>
        </span>
        <small>Audit internal</small>
    </summary>

    <div class="status-history-body">
        @forelse ($statusLogs as $log)
            @php
                $changedAt = $log->created_at
                    ?->copy()
                    ->timezone($timezone);
            @endphp

            <article class="status-history-item">
                <div>
                    <strong>
                        {{ ucfirst($log->previous_status) }}
                        →
                        {{ ucfirst($log->new_status) }}
                    </strong>
                    <small>
                        {{ $log->admin?->username ?? 'Admin tidak tersedia' }}
                        @if ($changedAt)
                            · {{ $changedAt->locale('id')->isoFormat('D MMM YYYY, HH.mm') }} WIB
                        @endif
                    </small>
                </div>
                <p>{{ $log->reason ?: 'Tidak ada alasan tambahan.' }}</p>
            </article>
        @empty
            <p class="status-history-empty">
                Belum ada perubahan status yang tercatat setelah fitur audit diaktifkan.
            </p>
        @endforelse
    </div>
</details>
