@if ($consultations->hasPages())
    <nav class="inbox-pagination" aria-label="Navigasi daftar chat">
        @if ($consultations->onFirstPage())
            <span class="pagination-button disabled">←</span>
        @else
            <a
                class="pagination-button"
                href="{{ $consultations->previousPageUrl() }}"
            >←</a>
        @endif

        <span class="pagination-label">
            {{ $consultations->currentPage() }} /
            {{ $consultations->lastPage() }}
        </span>

        @if ($consultations->hasMorePages())
            <a
                class="pagination-button"
                href="{{ $consultations->nextPageUrl() }}"
            >→</a>
        @else
            <span class="pagination-button disabled">→</span>
        @endif
    </nav>
@endif
