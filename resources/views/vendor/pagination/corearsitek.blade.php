{{-- Paginasi ringkas bergaya CoreArsitek; bawaan Laravel memakai kelas
     Tailwind yang tidak dipakai proyek ini. --}}
@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pager-link is-disabled" aria-disabled="true"><i class="fa-solid fa-chevron-left"></i></span>
        @else
            <a class="pager-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pager-gap">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pager-link is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pager-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pager-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @else
            <span class="pager-link is-disabled" aria-disabled="true"><i class="fa-solid fa-chevron-right"></i></span>
        @endif
    </nav>
@endif
