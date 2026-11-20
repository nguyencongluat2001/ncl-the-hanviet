@extends('client.layouts.landing')
@section('body-client')
    @php
        $hero = config('landing.hero');
        $steps = config('landing.steps');
        $support = config('landing.support');
        $why = config('landing.why');
        $testimonials = config('landing.testimonials');
        $firstSection = $sections[0]['code_category'] ?? '';
    @endphp

    {{-- ===== Hero ===== --}}
    <section class="ld-hero">
        <div class="ld-hero__bg" aria-hidden="true">
            @foreach ($showcase->take(10) as $image)
                <span style="background-image:url('{{ $image }}')"></span>
            @endforeach
        </div>
        <div class="ld-container ld-hero__inner">
            <div class="ld-hero__text">
                <div class="ld-hero__brand">{{ config('landing.brand') }}</div>
                <h1>{{ $hero['title'] }}</h1>
                <p class="ld-hero__quote">{{ $hero['quote'] }}</p>
                <p class="ld-hero__lead">{{ $hero['lead'] }}</p>
                <p class="ld-hero__note">{{ $hero['note'] }}</p>
                <a href="#{{ $firstSection }}" class="ld-btn ld-btn--lg">{{ $hero['cta'] }}</a>
            </div>
            <div class="ld-hero__phones">
                <div class="ld-float ld-float--back">
                    @include('client.landing.phone', ['image' => $showcase[0] ?? null, 'variant' => 'cream', 'class' => 'ld-phone--back'])
                </div>
                <div class="ld-float ld-float--front">
                    @include('client.landing.phone', ['image' => $showcase[1] ?? null, 'variant' => 'red', 'class' => 'ld-phone--front'])
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Các đầu mục: cổng cưới, thiệp cưới, rạp cưới ===== --}}
    @foreach ($sections as $item)
        <section class="ld-section" id="{{ $item['code_category'] }}">
            <div class="ld-container">
                <h2 class="ld-title" data-reveal>{{ $item['title'] }} <em>{{ $item['highlight'] }}</em></h2>
                <p class="ld-subtitle" data-reveal style="--d: .1s">{{ $item['subtitle'] }}</p>
            </div>

            @if ($item['products']->isNotEmpty())
                <div class="ld-ring ld-shape--{{ $item['shape'] ?? 'portrait' }}" data-ring data-reveal style="--d: .15s" tabindex="0" aria-roledescription="carousel" aria-label="{{ $item['title'] }}">
                    <div class="ld-ring__stage">
                        @foreach ($item['products'] as $product)
                            <div class="ld-ring__item">
                                @include('client.landing.card', ['product' => $product, 'gallery' => $item['code_category']])
                            </div>
                        @endforeach
                    </div>
                    <button class="ld-ring__nav ld-ring__nav--prev" type="button" aria-label="Trước"><i class="bi bi-chevron-left"></i></button>
                    <button class="ld-ring__nav ld-ring__nav--next" type="button" aria-label="Sau"><i class="bi bi-chevron-right"></i></button>
                    <div class="ld-ring__dots"></div>
                </div>

                <div class="ld-section__action" data-reveal>
                    <a href="{{ url('/danh-muc/' . $item['code_category']) }}" class="ld-btn">Xem tất cả {{ mb_strtolower($item['menu']) }} <i class="bi bi-chevron-right"></i></a>
                    <p class="ld-hint">Những mẫu {{ mb_strtolower($item['menu']) }} độc đáo đang chờ bạn</p>
                    @if ($item['code_category'] === 'thiep-cuoi')
                        <p class="ld-hint"><a href="{{ url('/mau-thiep') }}" class="ld-link">Tạo thiệp cưới online miễn phí →</a></p>
                    @endif
                </div>
            @else
                <div class="ld-container">
                    <div class="ld-empty" data-reveal>
                        <i class="bi bi-hourglass-split"></i>
                        <p>Các mẫu {{ mb_strtolower($item['menu']) }} đang được cập nhật.</p>
                        <a href="#lien-he" class="ld-btn ld-btn--ghost">Liên hệ tư vấn</a>
                    </div>
                </div>
            @endif
        </section>
    @endforeach

    {{-- ===== 3 bước ===== --}}
    <section class="ld-section">
        <div class="ld-container">
            <h2 class="ld-title" data-reveal>{{ $steps['title'] }} <em>{{ $steps['highlight'] }}</em></h2>
            <p class="ld-subtitle" data-reveal style="--d: .1s">{{ $steps['subtitle'] }}</p>

            <div class="ld-steps" data-reveal>
                <ol class="ld-steps__list">
                    @foreach ($steps['items'] as $index => $step)
                        <li class="ld-step" style="--d: {{ 0.2 + $index * 0.2 }}s">
                            <span class="ld-step__icon"><i class="bi {{ $step['icon'] }}"></i></span>
                            <div>
                                <small>Bước {{ $index + 1 }}</small>
                                <h3>{{ $step['title'] }}</h3>
                                <p>{{ $step['text'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
                <div class="ld-steps__media">
                    @if (!empty($steps['youtube_id']))
                        <iframe src="https://www.youtube.com/embed/{{ $steps['youtube_id'] }}" title="Video giới thiệu"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                    @else
                        <div class="ld-steps__poster">
                            @include('client.landing.phone', ['image' => $showcase[2] ?? null, 'variant' => 'green', 'class' => 'ld-phone--sm'])
                            <p>Khách mời xem địa điểm và thông tin lễ cưới ngay trên thiệp.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ld-section__action" data-reveal>
                <a href="tel:{{ config('landing.contact.phone') }}" class="ld-btn ld-btn--lg">Bắt đầu ngay</a>
                <p class="ld-hint">Gọi {{ config('landing.contact.phone') }} để được tư vấn miễn phí</p>
            </div>
        </div>
    </section>

    {{-- ===== Hỗ trợ ===== --}}
    <section class="ld-section ld-section--tint">
        <div class="ld-container">
            <h2 class="ld-title" data-reveal>{{ $support['title'] }} <em>{{ $support['highlight'] }}</em></h2>
            <p class="ld-subtitle" data-reveal style="--d: .1s">
                {{ $support['subtitle'] }}
                <a href="https://zalo.me/{{ config('landing.contact.zalo') }}" target="_blank" rel="noopener" class="ld-link">Nhắn Zalo ngay</a>
            </p>
            <div class="ld-support" data-reveal style="--d: .2s">
                @foreach ($support['items'] as $stat)
                    <div class="ld-support__item">
                        <span class="ld-support__icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                        <div>
                            <small>{{ $stat['label'] }}</small>
                            <strong>{{ $stat['value'] }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Đánh giá khách hàng (chỉ hiện khi đã khai báo trong config) ===== --}}
    @if (!empty($testimonials))
        <section class="ld-section ld-section--tint">
            <div class="ld-container">
                <h2 class="ld-title" data-reveal>Đánh giá từ <em>khách hàng</em></h2>
                <p class="ld-subtitle" data-reveal style="--d: .1s">Những lời nhắn thật lòng từ các cặp đôi đã tin tưởng {{ config('landing.brand') }}.</p>
            </div>
            <div class="ld-reviews">
                @foreach ($testimonials as $review)
                    <article class="ld-review" data-reveal style="--d: {{ ($loop->index % 4) * 0.1 }}s">
                        <header>
                            <span class="ld-review__source">{{ $review['source'] ?? '' }}</span>
                            <span>{{ $review['date'] ?? '' }}</span>
                        </header>
                        <div class="ld-review__stars">★★★★★</div>
                        <p>{{ $review['content'] }}</p>
                        <footer>{{ $review['name'] }}</footer>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Vì sao chọn ===== --}}
    <section class="ld-section">
        <div class="ld-container ld-why">
            <div class="ld-why__text">
                <h2 class="ld-title" data-reveal>{{ $why['title'] }} <em>{{ $why['highlight'] }}</em></h2>
                <p class="ld-subtitle" data-reveal style="--d: .1s">{{ $why['subtitle'] }}</p>
                <ul class="ld-checklist">
                    @foreach ($why['items'] as $point)
                        <li data-reveal style="--d: {{ 0.15 + $loop->index * 0.1 }}s"><i class="bi bi-check-circle"></i>{{ $point }}</li>
                    @endforeach
                </ul>
                <div class="ld-why__actions" data-reveal style="--d: .4s">
                    <a href="tel:{{ config('landing.contact.phone') }}" class="ld-btn">Nhận báo giá</a>
                    <a href="#{{ $firstSection }}" class="ld-link-strong">Xem tất cả mẫu</a>
                </div>
                <p class="ld-hint">{{ $hero['note'] }}</p>
            </div>
            <div class="ld-why__media" data-reveal style="--d: .2s">
                <div class="swiper-container js-fade">
                    <div class="swiper-wrapper">
                        @forelse ($showcase->take(5) as $image)
                            <div class="swiper-slide">
                                @include('client.landing.phone', ['image' => $image, 'variant' => 'red', 'class' => 'ld-phone--flat'])
                            </div>
                        @empty
                            @foreach (['green', 'red', 'cream'] as $variant)
                                <div class="swiper-slide">
                                    @include('client.landing.phone', ['image' => null, 'variant' => $variant, 'class' => 'ld-phone--flat'])
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                    <div class="ld-carousel__dots"></div>
                </div>
            </div>
        </div>
    </section>
@endsection
