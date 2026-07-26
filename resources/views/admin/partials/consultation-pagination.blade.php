@if ($consultations->hasPages())
    <div class="pagination">
        <div class="pager">
            <span>
                Halaman {{ $consultations->currentPage() }}
                dari {{ $consultations->lastPage() }}
            </span>

            <div class="pager-links">
                @if ($consultations->onFirstPage())
                    <span class="disabled">←</span>
                @else
                    <a
                        href="{{
                            $consultations
                                ->previousPageUrl()
                        }}"
                    >←</a>
                @endif

                @foreach (range(
                    max(
                        1,
                        $consultations
                            ->currentPage() - 2
                    ),
                    min(
                        $consultations
                            ->lastPage(),
                        $consultations
                            ->currentPage() + 2
                    )
                ) as $pageNumber)
                    @if (
                        $pageNumber
                        === $consultations->currentPage()
                    )
                        <span class="current">
                            {{ $pageNumber }}
                        </span>
                    @else
                        <a
                            href="{{
                                $consultations
                                    ->url($pageNumber)
                            }}"
                        >
                            {{ $pageNumber }}
                        </a>
                    @endif
                @endforeach

                @if ($consultations->hasMorePages())
                    <a
                        href="{{
                            $consultations
                                ->nextPageUrl()
                        }}"
                    >→</a>
                @else
                    <span class="disabled">→</span>
                @endif
            </div>
        </div>
    </div>
@endif
