@if ($paginator->hasPages())
    <div class="layui-box layui-laypage">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="layui-laypage-prev layui-disabled">&laquo;</a>
        @else
            <a class="layui-laypage-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="layui-laypage-spr">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>{{ $page }}</em></a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="layui-laypage-next" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <a class="layui-laypage-next layui-disabled">&raquo;</a>
        @endif
    </div>
@endif
