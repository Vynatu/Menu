@foreach($menu->items() as $item)
    @if($item->hasSubItems())
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
               aria-expanded="false">{{ $item->title }}<span class="caret"></span></a>
            <ul class="dropdown-menu">
                @include('menu::bootstrap.default', ['menu' => $item])
            </ul>
        </li>
    @else
        <li @if($item->active()) class="active" @endif>
            <a href="{{ $item->url }}">{{ $item->title }}</a>
        </li>
    @endif
@endforeach
