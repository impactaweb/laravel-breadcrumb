<nav aria-label="breadcrumb">
    <ol class="breadcrumb">

    @foreach ($items as $item)

        <li class="breadcrumb-item @if ($loop->last) active @endif">
            @if (empty($item['url']))
                {{ $item['title'] }}
            @else
                <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
            @endif
        </li>

    @endforeach

    </ol>
</nav>
