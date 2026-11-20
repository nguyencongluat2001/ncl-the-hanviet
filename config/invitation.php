<?php

/*
|--------------------------------------------------------------------------
| Mẫu thiệp cưới online
|--------------------------------------------------------------------------
|
| templates: mỗi mẫu dùng một view trong resources/views/client/invitation/templates
| và bộ màu riêng. Thêm tông màu mới chỉ cần thêm một khối ở đây.
|
| defaults: nội dung ban đầu khi tạo thiệp mới; người dùng sửa trong trình chỉnh sửa.
|
*/

return [
    'templates' => [
        'song-hy-xanh' => [
            'name' => 'Song Hỷ Xanh',
            'view' => 'song-hy',
            'colors' => ['primary' => '#1d3a28', 'accent' => '#cdeedd', 'bg' => '#fdf8ee', 'ink' => '#1d3a28'],
        ],
        'song-hy-do' => [
            'name' => 'Song Hỷ Đỏ',
            'view' => 'song-hy',
            'colors' => ['primary' => '#7d0f1d', 'accent' => '#f6d7a0', 'bg' => '#fdf6ec', 'ink' => '#6b0d18'],
        ],
        'song-hy-lam' => [
            'name' => 'Song Hỷ Lam',
            'view' => 'song-hy',
            'colors' => ['primary' => '#16305a', 'accent' => '#d6e4ff', 'bg' => '#f7f8fc', 'ink' => '#16305a'],
        ],
    ],

    // Ảnh dùng cho trang xem mẫu (thay bằng ảnh của bạn trong public/clients/assets/img)
    'sample_photos' => [
        'cover' => 'clients/assets/img/A2.jpg',
        'album' => [
            'clients/assets/img/A1.jpg',
            'clients/assets/img/A3.jpg',
            'clients/assets/img/A5.jpg',
            'clients/assets/img/A6.jpg',
            'clients/assets/img/A7.jpg',
            'clients/assets/img/A8.jpg',
        ],
    ],

    // Số ảnh album tối đa, dung lượng ảnh tối đa (KB)
    'max_album' => 20,
    'max_upload_kb' => 8192,

    'defaults' => [
        'texts' => [
            'welcome' => 'Welcome to our wedding',
            'love' => 'Love never fails',
            'announce' => 'Trân trọng báo tin lễ thành hôn của con chúng tôi',
            'closing' => 'Sự hiện diện của quý khách là niềm vinh hạnh của gia đình chúng tôi!',
        ],
        'groom' => ['name' => 'Nguyễn Minh Quân', 'short' => 'Minh Quân', 'title' => 'Trưởng Nam'],
        'bride' => ['name' => 'Trần Bảo Ngọc', 'short' => 'Bảo Ngọc', 'title' => 'Út Nữ'],
        'groom_family' => [
            'father' => 'Nguyễn Văn Hùng',
            'mother' => 'Lê Thị Mai',
            'address' => '12 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh',
        ],
        'bride_family' => [
            'father' => 'Trần Văn Long',
            'mother' => 'Phạm Thị Hoa',
            'address' => '45 Lê Lợi, Quận 3, TP. Hồ Chí Minh',
        ],
        'ceremony' => [
            'place' => 'Tư gia',
            'time' => '09:00',
            'date' => '2026-12-20',
        ],
        'party' => [
            'time' => '18:00',
            'open_time' => '18:30',
            'date' => '2026-12-20',
            'venue' => 'Trung tâm Tiệc cưới Hoa Sen',
            'address' => '123 Điện Biên Phủ, Quận Bình Thạnh, TP. Hồ Chí Minh',
        ],
        'photos' => [
            'cover' => null,
            'album' => [],
        ],
        'dress_code' => [
            'note' => 'Trang phục dự tiệc',
            'colors' => ['#8a9a5b', '#e5e5e5', '#e6d8c3'],
        ],
        'schedule' => [
            ['time' => '17:30', 'title' => 'Đón khách'],
            ['time' => '18:30', 'title' => 'Khai tiệc'],
            ['time' => '18:45', 'title' => 'Rót rượu, cắt bánh'],
            ['time' => '19:00', 'title' => 'Phục vụ món chính'],
            ['time' => '21:00', 'title' => 'Kết thúc tiệc'],
        ],
        'gift' => [
            'groom' => ['bank' => '', 'account' => '', 'holder' => '', 'qr' => null],
            'bride' => ['bank' => '', 'account' => '', 'holder' => '', 'qr' => null],
        ],
        'music' => null,
        // Các phần có thể bật/tắt
        'show' => [
            'album' => true,
            'map' => true,
            'dress_code' => true,
            'schedule' => true,
            'wishes' => true,
            'rsvp' => true,
            'gift' => true,
        ],
    ],
];
