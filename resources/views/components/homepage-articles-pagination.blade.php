{{--
----------------------------------------------------
    Jangan Lupa Set Di AppServiceProvider > boot
----------------------------------------------------
--}}

@if($paginator->hasPages())
<div class="col-12 mt-5 d-flex justify-content-center">
    <ul class="pager pagination">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link"><i class="fas fa-arrow-left mr-2" style="right: 10px;"></i> Previous</span></li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-arrow-left mr-2" style="right: 10px;"></i> Previous</a>
            </li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next <i class="fas fa-arrow-right ml-2" style="right: 10px;"></i> </a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next <i class="fas fa-arrow-right ml-2" style="right: 10px;"></i> </span></li>
        @endif
    </ul>

    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
</div>
@endif
