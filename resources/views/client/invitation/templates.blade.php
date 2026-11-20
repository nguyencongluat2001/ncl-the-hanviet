@extends('client.layouts.landing')
@section('title', 'Mẫu thiệp cưới online - ' . config('landing.brand'))
@section('body-client')
    @php $cover = config('invitation.sample_photos.cover'); @endphp
    <section class="ld-section ld-section--page">
        <div class="ld-container">
            <a href="{{ url('/') }}" class="ld-back"><i class="bi bi-arrow-left"></i> Trang chủ</a>
            <h1 class="ld-title">Mẫu thiệp cưới <em>online</em></h1>
            <p class="ld-subtitle">Chọn mẫu, điền thông tin, gửi link cho khách qua Zalo, Messenger. Khách xem bản đồ, lịch trình, gửi lời chúc và xác nhận tham dự ngay trên thiệp.</p>

            <div class="ld-iv-grid">
                @foreach ($templates as $key => $item)
                    @php $c = $item['colors']; @endphp
                    <article class="ld-iv-card" data-reveal style="--d: {{ $loop->index * 0.1 }}s">
                        <a href="{{ url('/mau-thiep/' . $key) }}" target="_blank" rel="noopener" class="ld-iv-card__phone"
                            style="--p: {{ $c['primary'] }}; --a: {{ $c['accent'] }}; --b: {{ $c['bg'] }}">
                            <span class="ld-iv-card__top">
                                <small>Welcome to our wedding</small>
                                <span class="ld-iv-card__xi">囍</span>
                            </span>
                            <span class="ld-iv-card__photo" style="background-image: url('{{ url($cover) }}')"></span>
                        </a>
                        <h3>{{ $item['name'] }}</h3>
                        <div class="ld-iv-card__actions">
                            <a href="{{ url('/mau-thiep/' . $key) }}" target="_blank" rel="noopener" class="ld-btn ld-btn--ghost">Xem mẫu</a>
                            <form method="POST" action="{{ url('/thiep/tao') }}">
                                @csrf
                                <input type="hidden" name="template" value="{{ $key }}">
                                <button type="submit" class="ld-btn">Dùng mẫu này</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
