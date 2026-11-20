{{-- Mockup điện thoại: dùng ảnh sản phẩm nếu có, không thì vẽ thiệp mẫu bằng CSS --}}
<div class="ld-phone {{ $class ?? '' }}">
    <div class="ld-phone__screen">
        @if (!empty($image))
            <img src="{{ $image }}" alt="">
        @else
            <div class="ld-invite ld-invite--{{ $variant }}">
                <small>Welcome to our wedding</small>
                <div class="ld-invite__xi">囍</div>
                <div class="ld-invite__names">Chú Rể<span>&amp;</span>Cô Dâu</div>
                <div class="ld-invite__box">Thông tin lễ cưới</div>
                <div class="ld-invite__parents">
                    <div><b>Nhà trai</b>Ông Bà ...</div>
                    <div><b>Nhà gái</b>Ông Bà ...</div>
                </div>
                <div class="ld-invite__date">Chủ nhật · 24.11</div>
            </div>
        @endif
    </div>
</div>
