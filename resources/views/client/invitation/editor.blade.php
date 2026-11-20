@php
    $token = $invitation->edit_token;
    $publicUrl = url('/thiep/' . $invitation->slug);
    $attending = $rsvps->where('attending', true);
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>Chỉnh sửa thiệp - {{ $d['groom']['short'] }} & {{ $d['bride']['short'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ url('/clients/assets/css/invitation-editor.css') }}?v={{ filemtime(public_path('clients/assets/css/invitation-editor.css')) }}" rel="stylesheet">
</head>
<body>
    <header class="ed-top">
        <a href="{{ url('/mau-thiep') }}" class="ed-logo"><i class="bi bi-arrow-left"></i> <span>{{ config('landing.brand') }}</span></a>
        <span class="ed-status" role="status"><i class="bi bi-cloud-check"></i> Đã lưu</span>
        <div class="ed-top__actions">
            <button type="button" class="ed-btn ed-btn--ghost" data-copy="{{ $publicUrl }}"><i class="bi bi-link-45deg"></i> <span>Sao chép link thiệp</span></button>
            <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="ed-btn"><i class="bi bi-box-arrow-up-right"></i> <span>Xem thiệp</span></a>
        </div>
    </header>

    <div class="ed-layout" data-view="form">
        <aside class="ed-panel">
            <div class="ed-notice">
                <i class="bi bi-shield-lock"></i>
                <p><b>Lưu lại link trang này</b> để sửa thiệp lần sau. Ai có link này đều sửa được thiệp, chỉ gửi cho khách <b>link thiệp</b>.</p>
            </div>

            <details class="ed-group" open>
                <summary><i class="bi bi-palette"></i> Mẫu & màu sắc</summary>
                <div class="ed-templates">
                    @foreach ($templates as $key => $item)
                        <label class="ed-template">
                            <input type="radio" name="template" value="{{ $key }}" @checked($key === $templateKey)>
                            <span class="ed-template__swatch" style="--p: {{ $item['colors']['primary'] }}; --a: {{ $item['colors']['accent'] }}; --b: {{ $item['colors']['bg'] }}">囍</span>
                            <span>{{ $item['name'] }}</span>
                        </label>
                    @endforeach
                </div>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-people"></i> Cô dâu & chú rể</summary>
                <h4>Chú rể</h4>
                @include('client.invitation.editor.field', ['label' => 'Họ và tên', 'path' => 'groom.name', 'live' => true])
                <div class="ed-row">
                    @include('client.invitation.editor.field', ['label' => 'Tên trên bìa', 'path' => 'groom.short', 'live' => true])
                    @include('client.invitation.editor.field', ['label' => 'Danh xưng', 'path' => 'groom.title', 'live' => true, 'placeholder' => 'Trưởng Nam'])
                </div>
                <h4>Cô dâu</h4>
                @include('client.invitation.editor.field', ['label' => 'Họ và tên', 'path' => 'bride.name', 'live' => true])
                <div class="ed-row">
                    @include('client.invitation.editor.field', ['label' => 'Tên trên bìa', 'path' => 'bride.short', 'live' => true])
                    @include('client.invitation.editor.field', ['label' => 'Danh xưng', 'path' => 'bride.title', 'live' => true, 'placeholder' => 'Út Nữ'])
                </div>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-house-heart"></i> Gia đình hai bên</summary>
                <h4>Nhà trai</h4>
                <div class="ed-row">
                    @include('client.invitation.editor.field', ['label' => 'Bố', 'path' => 'groom_family.father', 'live' => true])
                    @include('client.invitation.editor.field', ['label' => 'Mẹ', 'path' => 'groom_family.mother', 'live' => true])
                </div>
                @include('client.invitation.editor.field', ['label' => 'Địa chỉ', 'path' => 'groom_family.address', 'live' => true])
                <h4>Nhà gái</h4>
                <div class="ed-row">
                    @include('client.invitation.editor.field', ['label' => 'Bố', 'path' => 'bride_family.father', 'live' => true])
                    @include('client.invitation.editor.field', ['label' => 'Mẹ', 'path' => 'bride_family.mother', 'live' => true])
                </div>
                @include('client.invitation.editor.field', ['label' => 'Địa chỉ', 'path' => 'bride_family.address', 'live' => true])
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-calendar-heart"></i> Lễ thành hôn</summary>
                @include('client.invitation.editor.field', ['label' => 'Địa điểm cử hành', 'path' => 'ceremony.place', 'live' => true, 'placeholder' => 'Tư gia'])
                <div class="ed-row">
                    @include('client.invitation.editor.field', ['label' => 'Ngày', 'path' => 'ceremony.date', 'type' => 'date'])
                    @include('client.invitation.editor.field', ['label' => 'Giờ', 'path' => 'ceremony.time', 'type' => 'time'])
                </div>
                <small class="ed-hint">Ngày âm lịch được tự tính theo ngày dương.</small>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-cup-hot"></i> Tiệc cưới</summary>
                @include('client.invitation.editor.field', ['label' => 'Nhà hàng / địa điểm', 'path' => 'party.venue', 'live' => true, 'reload' => true])
                @include('client.invitation.editor.field', ['label' => 'Địa chỉ', 'path' => 'party.address', 'live' => true, 'reload' => true, 'hint' => 'Bản đồ và nút chỉ đường dùng địa chỉ này.'])
                <div class="ed-row ed-row--3">
                    @include('client.invitation.editor.field', ['label' => 'Ngày', 'path' => 'party.date', 'type' => 'date'])
                    @include('client.invitation.editor.field', ['label' => 'Đón khách', 'path' => 'party.time', 'type' => 'time'])
                    @include('client.invitation.editor.field', ['label' => 'Khai tiệc', 'path' => 'party.open_time', 'type' => 'time'])
                </div>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-images"></i> Ảnh cưới</summary>
                <h4>Ảnh bìa</h4>
                <div class="ed-photo" data-photo="photos.cover">
                    <div class="ed-photo__preview"></div>
                    <label class="ed-btn ed-btn--ghost ed-upload"><i class="bi bi-upload"></i> Chọn ảnh<input type="file" accept="image/jpeg,image/png,image/webp" hidden></label>
                    <button type="button" class="ed-link-danger" data-remove>Xoá</button>
                </div>
                <h4>Album <small class="ed-count"></small></h4>
                <div class="ed-album"></div>
                <label class="ed-btn ed-btn--ghost ed-upload ed-upload--album"><i class="bi bi-plus-lg"></i> Thêm ảnh album<input type="file" accept="image/jpeg,image/png,image/webp" multiple hidden></label>
                <small class="ed-hint">Ảnh JPG, PNG, WEBP tối đa {{ config('invitation.max_upload_kb') / 1024 }}MB, tối đa {{ config('invitation.max_album') }} ảnh. Kéo thả để đổi thứ tự.</small>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-fonts"></i> Lời trên thiệp</summary>
                @include('client.invitation.editor.field', ['label' => 'Dòng chào trên bìa', 'path' => 'texts.welcome', 'live' => true])
                @include('client.invitation.editor.field', ['label' => 'Chữ uốn cong trên bìa', 'path' => 'texts.love', 'live' => true])
                @include('client.invitation.editor.field', ['label' => 'Lời báo tin', 'path' => 'texts.announce', 'type' => 'textarea', 'live' => true])
                @include('client.invitation.editor.field', ['label' => 'Lời kết', 'path' => 'texts.closing', 'type' => 'textarea', 'live' => true])
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-clock-history"></i> Lịch trình ngày cưới</summary>
                <div class="ed-schedule"></div>
                <button type="button" class="ed-btn ed-btn--ghost ed-add-schedule"><i class="bi bi-plus-lg"></i> Thêm mốc</button>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-droplet-half"></i> Dress code</summary>
                @include('client.invitation.editor.field', ['label' => 'Ghi chú', 'path' => 'dress_code.note', 'live' => true])
                <div class="ed-colors"></div>
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-gift"></i> Hộp quà mừng</summary>
                @foreach (['groom' => 'Chú rể', 'bride' => 'Cô dâu'] as $side => $label)
                    <h4>{{ $label }}</h4>
                    <div class="ed-row">
                        @include('client.invitation.editor.field', ['label' => 'Ngân hàng', 'path' => "gift.$side.bank", 'reload' => true])
                        @include('client.invitation.editor.field', ['label' => 'Số tài khoản', 'path' => "gift.$side.account", 'reload' => true])
                    </div>
                    @include('client.invitation.editor.field', ['label' => 'Chủ tài khoản', 'path' => "gift.$side.holder", 'reload' => true])
                    <div class="ed-photo ed-photo--qr" data-photo="gift.{{ $side }}.qr">
                        <div class="ed-photo__preview"></div>
                        <label class="ed-btn ed-btn--ghost ed-upload"><i class="bi bi-qr-code"></i> Ảnh mã QR<input type="file" accept="image/jpeg,image/png,image/webp" hidden></label>
                        <button type="button" class="ed-link-danger" data-remove>Xoá</button>
                    </div>
                @endforeach
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-music-note-beamed"></i> Nhạc nền</summary>
                @include('client.invitation.editor.field', ['label' => 'Link file nhạc (mp3)', 'path' => 'music', 'type' => 'url', 'placeholder' => 'https://...', 'hint' => 'Để trống nếu không dùng nhạc. Nhạc phát khi khách chạm vào thiệp lần đầu.'])
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-toggles"></i> Hiện / ẩn các phần</summary>
                @foreach (['album' => 'Album ảnh', 'map' => 'Bản đồ', 'dress_code' => 'Dress code', 'schedule' => 'Lịch trình', 'wishes' => 'Sổ lưu bút', 'rsvp' => 'Xác nhận tham dự', 'gift' => 'Hộp quà mừng'] as $key => $label)
                    <label class="ed-switch">
                        <input type="checkbox" data-path="show.{{ $key }}" data-reload @checked($d['show'][$key])>
                        <span></span> {{ $label }}
                    </label>
                @endforeach
            </details>

            <details class="ed-group">
                <summary><i class="bi bi-person-check"></i> Khách xác nhận tham dự <span class="ed-badge">{{ $rsvps->count() }}</span></summary>
                @if ($rsvps->isEmpty())
                    <p class="ed-hint">Chưa có khách nào xác nhận.</p>
                @else
                    <p class="ed-hint">{{ $attending->count() }} lượt sẽ đến, tổng {{ $attending->sum('guests') }} người · {{ $rsvps->count() - $attending->count() }} lượt không đến được.</p>
                    <div class="ed-table">
                        <table>
                            <thead><tr><th>Tên</th><th>Tham dự</th><th>Số người</th><th>Bên</th><th>SĐT</th><th>Ghi chú</th></tr></thead>
                            <tbody>
                                @foreach ($rsvps as $rsvp)
                                    <tr>
                                        <td>{{ $rsvp->name }}</td>
                                        <td>{{ $rsvp->attending ? 'Có' : 'Không' }}</td>
                                        <td>{{ $rsvp->attending ? $rsvp->guests : '' }}</td>
                                        <td>{{ ['nha-trai' => 'Nhà trai', 'nha-gai' => 'Nhà gái'][$rsvp->side] ?? '' }}</td>
                                        <td>{{ $rsvp->phone }}</td>
                                        <td>{{ $rsvp->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </details>
        </aside>

        <section class="ed-preview">
            <div class="ed-phone">
                <iframe src="{{ url('/thiep/sua/' . $token . '/xem') }}" title="Xem trước thiệp"></iframe>
            </div>
            <p class="ed-hint ed-preview__hint"><i class="bi bi-hand-index"></i> Bấm vào chữ trên thiệp để sửa nhanh</p>
        </section>
    </div>

    <nav class="ed-tabs">
        <button type="button" data-tab="form" class="is-active"><i class="bi bi-pencil-square"></i> Chỉnh sửa</button>
        <button type="button" data-tab="preview"><i class="bi bi-phone"></i> Xem trước</button>
    </nav>

    <script>
        window.EDITOR = {
            template: @json($templateKey),
            data: @json($d),
            saveUrl: @json(url('/thiep/sua/' . $token)),
            uploadUrl: @json(url('/thiep/sua/' . $token . '/anh')),
            baseUrl: @json(url('/')),
            maxAlbum: @json(config('invitation.max_album')),
        };
    </script>
    <script src="{{ url('/clients/assets/js/invitation-editor.js') }}?v={{ filemtime(public_path('clients/assets/js/invitation-editor.js')) }}"></script>
</body>
</html>
