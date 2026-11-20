<?php

/*
|--------------------------------------------------------------------------
| Nội dung trang chủ
|--------------------------------------------------------------------------
|
| Sửa chữ, số điện thoại, video... tại đây, không cần sửa view.
|
*/

return [
    'brand' => 'Nguyễn Xuân Ngọc Wedding',

    // Danh mục cha trong quản trị chứa các danh mục sản phẩm bên dưới
    'cate' => 'DM_ANH_01',

    'hero' => [
        'title' => 'Cổng cưới, thiệp cưới, rạp cưới trọn gói',
        'quote' => '“Một ngày trọn vẹn, một đời hạnh phúc”',
        'lead' => 'Chúng tôi lo trọn từ tấm thiệp mời, cổng hoa đến rạp cưới, đồng bộ một phong cách cho ngày vui của gia đình bạn.',
        'note' => 'Tư vấn miễn phí · Báo giá trong ngày · Thi công tận nơi',
        'cta' => 'Xem mẫu ngay',
    ],

    /*
    | Mỗi đầu mục là một section có carousel riêng. Sản phẩm lấy từ bảng
    | product theo code_category. Thêm đầu mục: tạo danh mục trong quản trị
    | (thuộc DM_ANH_01) rồi khai báo thêm ở đây. icon: tên Bootstrap Icons.
    | shape: 'portrait' (thẻ dọc 9:16, mặc định) hoặc 'landscape' (thẻ ngang 4:3 cho ảnh ngang).
    */
    'sections' => [
        [
            'code_category' => 'cong-cuoi',
            'menu' => 'Cổng cưới',
            'icon' => 'bi-flower1',
            'shape' => 'landscape',
            'title' => 'Mẫu cổng cưới',
            'highlight' => 'ấn tượng',
            'subtitle' => 'Cổng hoa tươi, cổng lụa, cổng hoa giả được thiết kế theo concept riêng của bạn',
        ],
        [
            'code_category' => 'thiep-cuoi',
            'menu' => 'Thiệp cưới',
            'icon' => 'bi-envelope-heart',
            'title' => 'Mẫu thiệp cưới',
            'highlight' => 'đẹp nhất',
            'subtitle' => 'Khám phá những mẫu thiệp cưới được thiết kế tinh tế và hiện đại',
        ],
        [
            'code_category' => 'rap-cuoi',
            'menu' => 'Rạp cưới',
            'icon' => 'bi-house-heart',
            'title' => 'Mẫu rạp cưới',
            'highlight' => 'sang trọng',
            'subtitle' => 'Rạp cưới, bàn ghế, sân khấu trọn gói cho ngày vui của gia đình',
        ],
    ],

    'steps' => [
        'title' => 'Đặt dịch vụ cưới chỉ với',
        'highlight' => '3 bước đơn giản',
        'subtitle' => 'Giữ nét đẹp truyền thống · Tiện lợi thời hiện đại',
        'items' => [
            ['icon' => 'bi-images', 'title' => 'Chọn mẫu', 'text' => 'Chọn cổng, thiệp, rạp cưới bạn yêu thích'],
            ['icon' => 'bi-chat-heart', 'title' => 'Nhận tư vấn', 'text' => 'Gọi hoặc nhắn Zalo, báo giá ngay trong ngày'],
            ['icon' => 'bi-truck', 'title' => 'Thi công tận nơi', 'text' => 'Dựng rạp, cổng và giao thiệp đúng hẹn'],
        ],
        // ID video YouTube (phần sau "v=" trong link). Để null sẽ hiện ảnh minh hoạ.
        'youtube_id' => null,
    ],

    'support' => [
        'title' => 'Khi bạn cần,',
        'highlight' => 'chúng tôi luôn có mặt',
        'subtitle' => 'Gọi điện hoặc nhắn Zalo bất cứ lúc nào bạn cần tư vấn.',
        'items' => [
            ['icon' => 'bi-lightning-charge', 'label' => 'Tư vấn', 'value' => 'Hoàn toàn miễn phí'],
            ['icon' => 'bi-check2-circle', 'label' => 'Chỉnh sửa mẫu', 'value' => 'Đến khi bạn hài lòng'],
        ],
    ],

    'why' => [
        'title' => 'Trọn gói một nơi,',
        'highlight' => 'đồng bộ một phong cách',
        'subtitle' => 'Cổng cưới, thiệp mời và rạp cưới cùng tông màu, cùng concept, bạn chỉ cần làm việc với một đầu mối.',
        'items' => [
            'Thiết kế đồng bộ màu sắc và hoa văn giữa thiệp, cổng và rạp.',
            'Báo giá rõ ràng từng hạng mục, không phát sinh chi phí ẩn.',
            'Thi công tận nơi, tháo dỡ gọn gàng sau tiệc.',
        ],
    ],

    /*
    | Đánh giá khách hàng: chỉ thêm đánh giá THẬT. Danh sách rỗng thì ẩn section.
    | Ví dụ: ['name' => 'Anh Minh & chị Lan', 'source' => 'Facebook', 'date' => 'Tháng 9/2026', 'content' => '...'],
    */
    'testimonials' => [],

    'contact' => [
        'name' => 'Nguyễn Công Luật',
        'phone' => '0386358006',
        'zalo' => '0386358006',
    ],
];
