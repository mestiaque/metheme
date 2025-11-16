<a href="{{ $route }}"
    class="d-sm-inline-block btn btn-sm {{ $class ?? '' }}
            bg-{{ $bg ?? 'encodex-secondary' }}
            text-white shadow-sm float-right ml-2"
            {{ $attribute ?? '' }}
            id="{{ $id ?? '' }}"
            style="vertical-align: middle !important;">
  @if(isset($icon))<i class="fas fa-{{ $icon }} me-1"></i>@endif{{ $text }}
</a>
