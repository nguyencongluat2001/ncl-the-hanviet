@php
    $c = $template['colors'];
    $g = $d['groom'];
    $b = $d['bride'];
    $show = $d['show'];
    $album = $d['photos']['album'];
    $gift = $d['gift'];
    $hasGift = collect([$gift['groom'], $gift['bride']])->contains(fn ($side) => $side['account'] || $side['qr']);
    $slug = $invitation->slug ?? null;
    // Bản xem trước (trình chỉnh sửa / xem mẫu) không gửi lời chúc, xác nhận thật
    $readonly = $editing || !empty($isSample) || !$slug;
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thiệp cưới {{ $g['short'] }} & {{ $b['short'] }}</title>
    <meta name="description" content="Trân trọng kính mời bạn đến dự lễ cưới của {{ $g['short'] }} & {{ $b['short'] }} - {{ $partyDate['day'] }}/{{ $partyDate['month'] }}/{{ $partyDate['year'] }}">
    <meta property="og:title" content="Thiệp cưới {{ $g['short'] }} & {{ $b['short'] }}">
    <meta property="og:description" content="{{ $partyDate['weekday'] }}, {{ $partyDate['day'] }}/{{ $partyDate['month'] }}/{{ $partyDate['year'] }} - {{ $d['party']['venue'] }}">
    @if ($d['photos']['cover'])
        <meta property="og:image" content="{{ $photoUrl($d['photos']['cover']) }}">
    @endif
    @if ($editing || !empty($isSample))
        <meta name="robots" content="noindex">
    @endif
    <script>document.documentElement.classList.add('js');</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ url('/clients/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ url('/clients/assets/css/invitation.css') }}?v={{ filemtime(public_path('clients/assets/css/invitation.css')) }}" rel="stylesheet">
    <style>
        :root {
            --iv-primary: {{ $c['primary'] }};
            --iv-accent: {{ $c['accent'] }};
            --iv-bg: {{ $c['bg'] }};
            --iv-ink: {{ $c['ink'] }};
        }
    </style>
</head>

<body class="{{ $editing ? 'is-editing' : '' }}">
<div class="iv">
    {{-- ===== Bìa ===== --}}
    <header class="iv-cover">
        <div class="iv-cover__top">
            <p class="iv-cover__welcome" data-bind="texts.welcome">{{ $d['texts']['welcome'] }}</p>
            <div class="iv-cover__names">
                <div>
                    <small data-bind="groom.title">{{ $g['title'] }}</small>
                    <strong data-bind="groom.short">{{ $g['short'] }}</strong>
                </div>
                <span class="iv-xi" aria-hidden="true">囍</span>
                <div>
                    <small data-bind="bride.title">{{ $b['title'] }}</small>
                    <strong data-bind="bride.short">{{ $b['short'] }}</strong>
                </div>
            </div>
            <svg class="iv-cover__arc" viewBox="0 0 400 90" aria-hidden="true">
                <path id="iv-arc" d="M 60 80 Q 200 -10 340 80" fill="none" />
                <text><textPath href="#iv-arc" startOffset="50%" text-anchor="middle" data-bind="texts.love">{{ $d['texts']['love'] }}</textPath></text>
            </svg>
        </div>
        <div class="iv-cover__photo">
            @if ($d['photos']['cover'])
                <img src="{{ $photoUrl($d['photos']['cover']) }}" alt="{{ $g['short'] }} & {{ $b['short'] }}">
            @else
                <div class="iv-placeholder"><i class="bi bi-image"></i><span>Ảnh bìa</span></div>
            @endif
            <div class="iv-petals" aria-hidden="true">
                @for ($i = 0; $i < 18; $i++)
                    <span style="--x: {{ ($i * 37) % 100 }}%; --delay: {{ ($i * 0.7) % 9 }}s; --dur: {{ 7 + ($i % 5) }}s; --size: {{ 8 + ($i % 4) * 3 }}px"></span>
                @endfor
            </div>
        </div>
    </header>

    {{-- ===== Thông tin lễ cưới ===== --}}
    <h2 class="iv-band" data-reveal>Thông tin lễ cưới</h2>
    <section class="iv-section">
        <div class="iv-parents" data-reveal>
            <div>
                <small>Ông Bà</small>
                <b data-bind="groom_family.father">{{ $d['groom_family']['father'] }}</b>
                <b data-bind="groom_family.mother">{{ $d['groom_family']['mother'] }}</b>
                <p data-bind="groom_family.address">{{ $d['groom_family']['address'] }}</p>
            </div>
            <div>
                <small>Ông Bà</small>
                <b data-bind="bride_family.father">{{ $d['bride_family']['father'] }}</b>
                <b data-bind="bride_family.mother">{{ $d['bride_family']['mother'] }}</b>
                <p data-bind="bride_family.address">{{ $d['bride_family']['address'] }}</p>
            </div>
        </div>

        <p class="iv-caps" data-reveal data-bind="texts.announce">{{ $d['texts']['announce'] }}</p>
        <div class="iv-couple" data-reveal>
            <h1 data-bind="groom.name">{{ $g['name'] }}</h1>
            <small data-bind="groom.title">{{ $g['title'] }}</small>
            <span class="iv-amp">&amp;</span>
            <h1 data-bind="bride.name">{{ $b['name'] }}</h1>
            <small data-bind="bride.title">{{ $b['title'] }}</small>
        </div>

        <p class="iv-caps" data-reveal>Lễ thành hôn được cử hành tại<br><span data-bind="ceremony.place">{{ $d['ceremony']['place'] }}</span></p>
        <p class="iv-caps iv-caps--sm" data-reveal>Vào lúc {{ $ceremonyDate['time'] }}</p>
        @include('client.invitation.templates.partials.date', ['date' => $ceremonyDate])
    </section>

    {{-- ===== Album ===== --}}
    @if ($show['album'])
        <h2 class="iv-band" data-reveal>Album ảnh</h2>
        <section class="iv-section iv-section--album">
            @if (count($album))
                <div class="ld-ring" data-ring data-reveal tabindex="0" aria-label="Album ảnh">
                    <div class="ld-ring__stage">
                        @foreach ($album as $photo)
                            <div class="ld-ring__item">
                                <a class="iv-album__photo glightbox" href="{{ $photoUrl($photo) }}" data-gallery="album">
                                    <img src="{{ $photoUrl($photo) }}" alt="" loading="lazy">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button class="ld-ring__nav ld-ring__nav--prev" type="button" aria-label="Ảnh trước"><i class="bi bi-chevron-left"></i></button>
                    <button class="ld-ring__nav ld-ring__nav--next" type="button" aria-label="Ảnh sau"><i class="bi bi-chevron-right"></i></button>
                    <div class="ld-ring__dots"></div>
                </div>
            @else
                <div class="iv-placeholder iv-placeholder--album"><i class="bi bi-images"></i><span>Thêm ảnh album trong trình chỉnh sửa</span></div>
            @endif
        </section>
    @endif

    {{-- ===== Tiệc cưới ===== --}}
    <h2 class="iv-band" data-reveal>Thông tin tiệc cưới</h2>
    <section class="iv-section">
        <p class="iv-caps" data-reveal>Tiệc cưới sẽ diễn ra vào lúc:</p>
        <p class="iv-time" data-reveal>{{ $partyDate['time'] }}</p>
        @include('client.invitation.templates.partials.date', ['date' => $partyDate])
        <p class="iv-caps iv-caps--sm" data-reveal>Khai tiệc</p>
        <p class="iv-time" data-reveal>{{ $d['party']['open_time'] }}</p>

        <div class="iv-calendar" data-reveal>
            <div class="iv-calendar__title">{{ $calendar['title'] }}</div>
            <div class="iv-calendar__grid">
                @foreach (['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $weekday)
                    <span class="iv-calendar__head">{{ $weekday }}</span>
                @endforeach
                @foreach ($calendar['weeks'] as $week)
                    @foreach ($week as $day)
                        <span class="{{ $day === $calendar['day'] ? 'is-day' : '' }}">{{ $day }}</span>
                    @endforeach
                @endforeach
            </div>
        </div>
        <a class="iv-link" href="{{ $calendarLink }}" target="_blank" rel="noopener" data-reveal>Thêm vào lịch</a>

        @if ($show['rsvp'])
            <div data-reveal>
                <button class="iv-btn" type="button" data-open="#iv-rsvp">Xác nhận tham dự</button>
            </div>
        @endif
    </section>

    {{-- ===== Địa điểm ===== --}}
    <h2 class="iv-band" data-reveal>Tiệc cưới sẽ tổ chức tại</h2>
    <section class="iv-section">
        <p class="iv-venue" data-reveal data-bind="party.venue">{{ $d['party']['venue'] }}</p>
        <p class="iv-muted" data-reveal data-bind="party.address">{{ $d['party']['address'] }}</p>
        @if ($show['map'])
            <div class="iv-map" data-reveal>
                <iframe src="{{ $mapEmbed }}" title="Bản đồ" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <a class="iv-link" href="{{ $mapLink }}" target="_blank" rel="noopener" data-reveal><i class="bi bi-cursor"></i> Chỉ đường</a>
        @endif
    </section>

    {{-- ===== Dress code ===== --}}
    @if ($show['dress_code'] && count($d['dress_code']['colors']))
        <section class="iv-section">
            <h2 class="iv-heading" data-reveal>Dress code</h2>
            <p class="iv-muted" data-reveal data-bind="dress_code.note">{{ $d['dress_code']['note'] }}</p>
            <div class="iv-swatches" data-reveal>
                @foreach ($d['dress_code']['colors'] as $color)
                    <span style="background: {{ $color }}"></span>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Lịch trình ===== --}}
    @if ($show['schedule'] && count($d['schedule']))
        <section class="iv-section">
            <h2 class="iv-heading" data-reveal>Lịch trình ngày cưới</h2>
            <ol class="iv-timeline">
                @foreach ($d['schedule'] as $row)
                    <li data-reveal style="--d: {{ $loop->index * 0.08 }}s"><time>{{ $row['time'] }}</time><span>{{ $row['title'] }}</span></li>
                @endforeach
            </ol>
        </section>
    @endif

    {{-- ===== Sổ lưu bút ===== --}}
    @if ($show['wishes'])
        <h2 class="iv-band" data-reveal>Sổ lưu bút</h2>
        <section class="iv-section">
            <form class="iv-card iv-wish-form" data-reveal data-action="{{ $slug ? url('/thiep/' . $slug . '/loi-chuc') : '' }}">
                <input name="name" maxlength="100" placeholder="Nhập tên của bạn*" required>
                <textarea name="message" rows="4" maxlength="1000" placeholder="Nhập lời chúc của bạn*" required></textarea>
                <div class="iv-form__foot">
                    <span class="iv-form__msg" role="status"></span>
                    <button class="iv-btn" type="submit">Gửi lời chúc</button>
                </div>
            </form>
            <div class="iv-wishes">
                @foreach ($wishes as $wish)
                    <article class="iv-card iv-wish">
                        <header><b>{{ $wish->name }}</b><time>{{ $wish->created_at->format('H:i d/m/Y') }}</time></header>
                        <p>{{ $wish->message }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Hộp quà mừng ===== --}}
    @if ($show['gift'])
        <section class="iv-section">
            <h2 class="iv-heading" data-reveal>Hộp quà mừng</h2>
            <button class="iv-gift" type="button" data-open="#iv-gift" data-reveal aria-label="Mở hộp quà">
                <span class="iv-gift__lid"></span>
                <span class="iv-gift__box"><span class="iv-xi">囍</span></span>
                <span class="iv-gift__bow"></span>
            </button>
            <p class="iv-muted iv-gift__hint">Nhấn để mở</p>
        </section>
    @endif

    <section class="iv-section iv-closing">
        <p data-reveal data-bind="texts.closing">{{ $d['texts']['closing'] }}</p>
    </section>

    <footer class="iv-footer">
        <a href="{{ url('/mau-thiep') }}"><i class="bi bi-heart"></i> {{ config('landing.brand') }}</a>
    </footer>
</div>

{{-- ===== Hộp thoại xác nhận tham dự ===== --}}
@if ($show['rsvp'])
    <dialog class="iv-modal" id="iv-rsvp">
        <form class="iv-rsvp-form" data-action="{{ $slug ? url('/thiep/' . $slug . '/tham-du') : '' }}">
            <button class="iv-modal__close" type="button" data-close aria-label="Đóng"><i class="bi bi-x-lg"></i></button>
            <h3>Xác nhận tham dự</h3>
            <input name="name" maxlength="100" placeholder="Họ và tên*" required>
            <input name="phone" maxlength="30" placeholder="Số điện thoại">
            <div class="iv-choice">
                <label><input type="radio" name="attending" value="1" checked> Sẽ tham dự</label>
                <label><input type="radio" name="attending" value="0"> Rất tiếc không thể đến</label>
            </div>
            <div class="iv-row">
                <select name="side">
                    <option value="">Bạn là khách của...</option>
                    <option value="nha-trai">Nhà trai</option>
                    <option value="nha-gai">Nhà gái</option>
                </select>
                <select name="guests">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} người</option>
                    @endfor
                </select>
            </div>
            <textarea name="note" rows="2" maxlength="500" placeholder="Ghi chú"></textarea>
            <div class="iv-form__foot">
                <span class="iv-form__msg" role="status"></span>
                <button class="iv-btn" type="submit">Gửi xác nhận</button>
            </div>
        </form>
    </dialog>
@endif

{{-- ===== Hộp thoại quà mừng ===== --}}
@if ($show['gift'])
    <dialog class="iv-modal" id="iv-gift">
        <button class="iv-modal__close" type="button" data-close aria-label="Đóng"><i class="bi bi-x-lg"></i></button>
        <h3>Gửi quà mừng</h3>
        @if ($hasGift)
            <div class="iv-gift-list">
                @foreach (['groom' => 'Chú rể', 'bride' => 'Cô dâu'] as $side => $label)
                    @php $account = $gift[$side]; @endphp
                    @if ($account['account'] || $account['qr'])
                        <div class="iv-card iv-bank">
                            <small>Mừng cưới {{ mb_strtolower($label) }}</small>
                            @if ($account['qr'])
                                <img src="{{ $photoUrl($account['qr']) }}" alt="Mã QR {{ $label }}">
                            @endif
                            <b>{{ $account['bank'] }}</b>
                            @if ($account['account'])
                                <button type="button" class="iv-copy" data-copy="{{ $account['account'] }}">{{ $account['account'] }} <i class="bi bi-copy"></i></button>
                            @endif
                            <span>{{ $account['holder'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="iv-muted">Cô dâu chú rể chưa thêm thông tin nhận quà.</p>
        @endif
    </dialog>
@endif

@if ($d['music'])
    <audio id="iv-music" src="{{ $d['music'] }}" loop preload="none"></audio>
    <button class="iv-music" type="button" aria-label="Bật/tắt nhạc"><i class="bi bi-music-note-beamed"></i></button>
@endif

<script>
    window.IV = { readonly: @json($readonly), editing: @json($editing) };
</script>
<script src="{{ url('/clients/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ url('/clients/assets/js/landing.js') }}?v={{ filemtime(public_path('clients/assets/js/landing.js')) }}"></script>
<script src="{{ url('/clients/assets/js/invitation.js') }}?v={{ filemtime(public_path('clients/assets/js/invitation.js')) }}"></script>
</body>
</html>
