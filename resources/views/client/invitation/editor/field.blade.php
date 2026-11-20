{{--
    Ô nhập liệu gắn với một trường trong dữ liệu thiệp.
    live: chữ cập nhật ngay trên khung xem trước; reload: tải lại khung xem trước sau khi lưu.
--}}
@php
    $type = $type ?? 'text';
    $value = data_get($d, $path);
    $id = 'f-' . str_replace('.', '-', $path);
@endphp
<label class="ed-field" for="{{ $id }}">
    <span>{{ $label }}</span>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" data-path="{{ $path }}" rows="{{ $rows ?? 2 }}" maxlength="300"
            @if (!empty($live)) data-live @endif @if (!empty($reload)) data-reload @endif>{{ $value }}</textarea>
    @else
        <input id="{{ $id }}" type="{{ $type }}" data-path="{{ $path }}" value="{{ $value }}"
            @if ($type === 'text') maxlength="300" @endif
            @if (!empty($placeholder)) placeholder="{{ $placeholder }}" @endif
            @if (!empty($live)) data-live @endif @if (!empty($reload) || in_array($type, ['date', 'time', 'url'])) data-reload @endif>
    @endif
    @if (!empty($hint))
        <small>{{ $hint }}</small>
    @endif
</label>
