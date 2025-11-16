
<div class="d-flex justify-content-sm-end justify-content-start">
    <button class="btn btn-sm {{ $class ?? '' }}
                bg-{{ $bg ?? 'encodex-secondary' }}
                text-white shadow-sm ml-2"
                {{ $attribute ?? '' }}
                id="{{ $id ?? '' }}"
                style="vertical-align: middle !important;">
        @if(isset($icon))<i class="fas fa-{{ $icon }} me-1"></i>@endif{{ $text }}
    </button>
</div>
