@php
    $image = !empty($product->image) ? url('/file-image-client/product/' . rawurlencode($product->image)) : null;
@endphp
<a class="ld-card {{ $image ? 'glightbox' : 'ld-card--empty' }}" @if ($image) href="{{ $image }}" data-gallery="{{ $gallery }}" data-title="{{ $product->name }}" @endif>
    @if ($image)
        <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
    @else
        <span class="ld-card__placeholder"><i class="bi bi-image"></i></span>
    @endif
    <span class="ld-card__info">
        <span class="ld-card__name">{{ $product->name }}</span>
        @if (!empty($product->price))
            <span class="ld-card__price">
                @if (!empty($product->sale))<s>{{ $product->price }}</s> {{ $product->sale }}@else{{ $product->price }}@endif
            </span>
        @endif
    </span>
</a>
