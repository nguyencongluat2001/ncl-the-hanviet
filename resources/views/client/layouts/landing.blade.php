<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('landing.brand') . ' - Cổng cưới, thiệp cưới, rạp cưới')</title>
    <meta name="description" content="{{ config('landing.hero.lead') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('landing.brand') }} - {{ config('landing.hero.title') }}">
    <meta property="og:description" content="{{ config('landing.hero.lead') }}">
    <link href="{{ url('/clients/assets/img/A2.jpg') }}" rel="icon">
    <script>
        // Áp theme trước khi vẽ trang để không bị nháy
        try { var t = localStorage.getItem('ld-theme'); if (t) document.documentElement.dataset.theme = t; } catch (e) {}
        // Chỉ ẩn sẵn nội dung chờ hiệu ứng khi có JS
        document.documentElement.classList.add('js');
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Lobster&family=Lora:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ url('/clients/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ url('/clients/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ url('/clients/assets/css/landing.css') }}?v={{ filemtime(public_path('clients/assets/css/landing.css')) }}" rel="stylesheet">
</head>

<body>
    <header class="ld-header">
        <div class="ld-header__inner">
            <a href="{{ url('/') }}" class="ld-logo">
                <span class="ld-logo__mark"><i class="bi bi-suit-heart-fill"></i></span>
                <span class="ld-logo__text">{{ config('landing.brand') }}</span>
            </a>
            <nav class="ld-nav">
                @foreach ($sections as $item)
                    <a href="{{ url('/') }}#{{ $item['code_category'] }}">{{ $item['menu'] }}</a>
                @endforeach
                <a href="{{ url('/mau-thiep') }}">Thiệp online</a>
            </nav>
            <div class="ld-header__actions">
                <button class="ld-theme-toggle" type="button" aria-label="Đổi giao diện sáng/tối">
                    <i class="bi bi-moon"></i><i class="bi bi-sun"></i>
                </button>
                <a href="#lien-he" class="ld-header__cta">Liên hệ</a>
            </div>
        </div>
    </header>

    <main>
        @yield('body-client')
    </main>

    <footer class="ld-footer" id="lien-he">
        <div class="ld-container ld-footer__inner">
            <div>
                <div class="ld-logo ld-logo--light">
                    <span class="ld-logo__mark"><i class="bi bi-suit-heart-fill"></i></span>
                    <span class="ld-logo__text">{{ config('landing.brand') }}</span>
                </div>
                <p>{{ collect($sections)->pluck('menu')->implode(' · ') }} trọn gói</p>
            </div>
            <div class="ld-footer__contact">
                <div><i class="bi bi-person"></i> {{ config('landing.contact.name') }}</div>
                <a href="tel:{{ config('landing.contact.phone') }}"><i class="bi bi-telephone"></i> {{ config('landing.contact.phone') }}</a>
                <a href="https://zalo.me/{{ config('landing.contact.zalo') }}" target="_blank" rel="noopener"><i class="bi bi-chat-dots"></i> Zalo {{ config('landing.contact.zalo') }}</a>
            </div>
        </div>
        <div class="ld-container ld-footer__copy">© {{ date('Y') }} {{ config('landing.brand') }}</div>
    </footer>

    <a class="ld-call" href="tel:{{ config('landing.contact.phone') }}" aria-label="Gọi điện"><i class="bi bi-telephone-fill"></i></a>

    {{-- Thanh tab dưới cùng trên mobile --}}
    <nav class="ld-tabbar">
        @foreach ($sections as $item)
            <a href="{{ url('/') }}#{{ $item['code_category'] }}"><i class="bi {{ $item['icon'] }}"></i><span>{{ $item['menu'] }}</span></a>
        @endforeach
        <a href="tel:{{ config('landing.contact.phone') }}" class="is-accent"><i class="bi bi-telephone"></i><span>Gọi ngay</span></a>
    </nav>

    <script src="{{ url('/clients/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ url('/clients/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script>
        document.querySelector('.ld-theme-toggle').addEventListener('click', function () {
            var root = document.documentElement;
            var isDark = root.dataset.theme
                ? root.dataset.theme === 'dark'
                : window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.dataset.theme = isDark ? 'light' : 'dark';
            try { localStorage.setItem('ld-theme', root.dataset.theme); } catch (e) {}
        });
    </script>
    @yield('script')
    <script src="{{ url('/clients/assets/js/landing.js') }}?v={{ filemtime(public_path('clients/assets/js/landing.js')) }}"></script>
</body>
</html>
