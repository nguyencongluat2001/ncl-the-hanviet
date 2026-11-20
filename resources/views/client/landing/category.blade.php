@extends('client.layouts.landing')
@section('title', $section['title'] . ' - NCL Wedding')
@section('body-client')
    <section class="ld-section ld-section--page">
        <div class="ld-container">
            <a href="{{ url('/') }}#{{ $section['code_category'] }}" class="ld-back"><i class="bi bi-arrow-left"></i> Trang chủ</a>
            <h1 class="ld-title">{{ $section['title'] }} <em>{{ $section['highlight'] }}</em></h1>
            <p class="ld-subtitle">{{ $section['subtitle'] }}</p>

            @if ($products->isNotEmpty())
                <div class="ld-grid ld-shape--{{ $section['shape'] ?? 'portrait' }}">
                    @foreach ($products as $product)
                        <div data-reveal style="--d: {{ ($loop->index % 4) * 0.08 }}s">
                            @include('client.landing.card', ['product' => $product, 'gallery' => $section['code_category']])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="ld-empty">
                    <i class="bi bi-hourglass-split"></i>
                    <p>Các mẫu {{ mb_strtolower($section['menu']) }} đang được cập nhật.</p>
                    <a href="#lien-he" class="ld-btn ld-btn--ghost">Liên hệ tư vấn</a>
                </div>
            @endif
        </div>
    </section>
@endsection

