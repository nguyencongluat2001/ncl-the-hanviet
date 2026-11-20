<?php

namespace Modules\Client\Page\Invitation\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Modules\Client\Page\Invitation\Helpers\LunarCalendar;
use Modules\Client\Page\Invitation\Models\InvitationModel;

class InvitationService
{
    const WEEKDAYS = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];

    // Các trường dạng danh sách: không trộn với mặc định mà thay toàn bộ
    const LIST_FIELDS = ['photos.album', 'dress_code.colors', 'schedule'];

    public function templates(): array
    {
        return config('invitation.templates');
    }

    public function template(string $key): ?array
    {
        return $this->templates()[$key] ?? null;
    }

    /**
     * Tạo thiệp mới từ mẫu, nội dung ban đầu là dữ liệu mặc định
     */
    public function create(string $template): InvitationModel
    {
        $data = config('invitation.defaults');
        return InvitationModel::create([
            'slug' => $this->makeSlug(),
            'edit_token' => Str::random(40),
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * Chuẩn hoá dữ liệu người dùng gửi lên theo khung dữ liệu mặc định:
     * bỏ trường lạ, giới hạn độ dài, kiểm tra ngày/giờ/màu/đường dẫn ảnh.
     */
    public function normalize(array $input, InvitationModel $invitation): array
    {
        return $this->normalizeNode(config('invitation.defaults'), $input, '', $invitation);
    }

    private function normalizeNode(array $schema, $input, string $path, InvitationModel $invitation): array
    {
        $input = is_array($input) ? $input : [];
        $result = [];
        foreach ($schema as $key => $default) {
            $fieldPath = ltrim($path . '.' . $key, '.');
            $value = $input[$key] ?? null;

            if ($fieldPath === 'schedule') {
                $result[$key] = collect(is_array($value) ? $value : [])
                    ->filter(fn ($row) => is_array($row))
                    ->map(fn ($row) => [
                        'time' => $this->time($row['time'] ?? '') ?? '',
                        'title' => $this->text($row['title'] ?? '', 100),
                    ])
                    ->filter(fn ($row) => $row['time'] !== '' || $row['title'] !== '')
                    ->take(15)->values()->all();
            } elseif ($fieldPath === 'dress_code.colors') {
                $result[$key] = collect(is_array($value) ? $value : [])
                    ->filter(fn ($color) => is_string($color) && preg_match('/^#[0-9a-f]{6}$/i', $color))
                    ->take(8)->values()->all();
            } elseif ($fieldPath === 'photos.album') {
                $result[$key] = collect(is_array($value) ? $value : [])
                    ->map(fn ($photo) => $this->photo($photo, $invitation))
                    ->filter()->take(config('invitation.max_album'))->values()->all();
            } elseif (is_array($default)) {
                $result[$key] = $this->normalizeNode($default, $value, $fieldPath, $invitation);
            } elseif (is_bool($default)) {
                $result[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif (Str::endsWith($fieldPath, ['cover', 'qr']) || $fieldPath === 'music') {
                $result[$key] = $fieldPath === 'music' ? $this->url($value) : $this->photo($value, $invitation);
            } elseif (Str::endsWith($fieldPath, 'date')) {
                $result[$key] = $this->date($value) ?? $default;
            } elseif (Str::endsWith($fieldPath, 'time')) {
                $result[$key] = $this->time($value) ?? $default;
            } else {
                $result[$key] = $this->text($value, 300);
            }
        }
        return $result;
    }

    private function text($value, int $max): string
    {
        return is_scalar($value) ? Str::limit(trim(strip_tags((string) $value)), $max, '') : '';
    }

    private function date($value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        [$y, $m, $d] = array_map('intval', explode('-', $value));
        return checkdate($m, $d, $y) ? $value : null;
    }

    private function time($value): ?string
    {
        return is_string($value) && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) ? $value : null;
    }

    private function url($value): ?string
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_URL) && Str::startsWith($value, ['http://', 'https://'])
            ? Str::limit($value, 500, '') : null;
    }

    /**
     * Chỉ chấp nhận ảnh đã upload vào thư mục của chính thiệp này hoặc ảnh mẫu
     */
    private function photo($value, InvitationModel $invitation): ?string
    {
        if (!is_string($value) || str_contains($value, '..')) {
            return null;
        }
        $own = $this->uploadDir($invitation) . '/';
        return Str::startsWith($value, [$own, 'clients/assets/img/']) && is_file(public_path($value)) ? $value : null;
    }

    public function uploadDir(InvitationModel $invitation): string
    {
        return 'file-image-client/invitation/' . $invitation->id;
    }

    /**
     * Lưu ảnh upload, trả về đường dẫn tương đối trong public/
     */
    public function storePhoto(InvitationModel $invitation, UploadedFile $file): string
    {
        $dir = $this->uploadDir($invitation);
        $name = date('Ymd_His') . '_' . Str::random(8) . '.' . strtolower($file->guessExtension() ?: 'jpg');
        $file->move(public_path($dir), $name);
        return $dir . '/' . $name;
    }

    /**
     * Mã ngẫu nhiên cho link thiệp; không lấy theo tên vì tên còn sửa sau khi đã gửi link
     */
    private function makeSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(10));
        } while (InvitationModel::where('slug', $slug)->exists());
        return $slug;
    }

    /**
     * Dữ liệu cho view thiệp: nội dung + các giá trị tính sẵn (thứ, âm lịch, lịch tháng, bản đồ...)
     */
    public function viewData(string $templateKey, array $data, array $extra = []): array
    {
        $template = $this->template($templateKey);
        $data = $this->withDefaults(config('invitation.defaults'), $data);

        $party = Carbon::parse($data['party']['date'] . ' ' . $data['party']['time']);
        $ceremony = Carbon::parse($data['ceremony']['date'] . ' ' . $data['ceremony']['time']);
        $address = trim($data['party']['venue'] . ', ' . $data['party']['address'], ', ');

        return array_merge([
            'templateKey' => $templateKey,
            'template' => $template,
            'd' => $data,
            'ceremonyDate' => $this->dateParts($ceremony),
            'partyDate' => $this->dateParts($party),
            'calendar' => $this->monthGrid($party),
            'photoUrl' => fn ($path) => $path ? url($path) : null,
            'mapEmbed' => 'https://maps.google.com/maps?q=' . urlencode($address) . '&z=15&output=embed',
            'mapLink' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address),
            'calendarLink' => 'https://calendar.google.com/calendar/render?' . http_build_query([
                'action' => 'TEMPLATE',
                'text' => 'Tiệc cưới ' . $data['groom']['short'] . ' & ' . $data['bride']['short'],
                'dates' => $party->copy()->subHours(7)->format('Ymd\THis\Z') . '/' . $party->copy()->subHours(3)->format('Ymd\THis\Z'),
                'location' => $address,
            ]),
            'wishes' => collect(),
            'editing' => false,
            'invitation' => null,
        ], $extra);
    }

    /**
     * Bổ sung trường còn thiếu từ mặc định (thiệp tạo trước khi thêm trường mới), giữ nguyên các danh sách
     */
    private function withDefaults(array $defaults, array $data, string $path = ''): array
    {
        foreach ($defaults as $key => $default) {
            $fieldPath = ltrim($path . '.' . $key, '.');
            if (!array_key_exists($key, $data)) {
                $data[$key] = $default;
            } elseif (is_array($default) && is_array($data[$key]) && !in_array($fieldPath, self::LIST_FIELDS)) {
                $data[$key] = $this->withDefaults($default, $data[$key], $fieldPath);
            }
        }
        return $data;
    }

    private function dateParts(Carbon $date): array
    {
        return [
            'weekday' => self::WEEKDAYS[$date->dayOfWeek],
            'day' => $date->format('d'),
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
            'time' => $date->format('H:i'),
            'lunar' => LunarCalendar::format($date->day, $date->month, $date->year),
        ];
    }

    /**
     * Lịch tháng bắt đầu từ thứ Hai; ô trống là null
     */
    private function monthGrid(Carbon $date): array
    {
        $first = $date->copy()->startOfMonth();
        $cells = array_fill(0, ($first->dayOfWeekIso - 1), null);
        for ($day = 1; $day <= $date->daysInMonth; $day++) {
            $cells[] = $day;
        }
        return [
            'title' => 'Tháng ' . $date->month . ' / ' . $date->year,
            'weeks' => array_chunk(array_pad($cells, (int) ceil(count($cells) / 7) * 7, null), 7),
            'day' => $date->day,
        ];
    }
}
