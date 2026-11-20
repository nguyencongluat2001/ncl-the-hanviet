<div class="modal-dialog modal-xl word-list-dialog" role="document">
    <div class="modal-content card word-list-modal">
        <div class="modal-header">
            <h5 class="modal-title">Danh sách chữ — {{ $product->name }}</h5>
            <button type="button" class="btn btn-sm btn-light" onclick="JS_Product.closeWords()" aria-label="Đóng">×</button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-success btn-sm" onclick="JS_Product.wordForm('{{ $product->id }}')">
                    <i class="fas fa-plus"></i> Thêm chữ
                </button>
            </div>
            <form class="word-filter-form" data-product-id="{{ $product->id }}" onsubmit="JS_Product.searchWords(); return false;">
                <select id="word_search_by" class="form-control" aria-label="Chọn cột để lọc">
                    <option value="name_chu_han" @selected($searchBy === 'name_chu_han')>Chữ Hán</option>
                    <option value="name_quan_hoa" @selected($searchBy === 'name_quan_hoa')>Quan thoại</option>
                    <option value="name_han_viet" @selected($searchBy === 'name_han_viet')>Hán Việt</option>
                </select>
                <input id="word_search_text" class="form-control" type="search" value="{{ $search }}" placeholder="Nhập nội dung cần tìm..." aria-label="Nội dung cần tìm">
                <button type="submit" class="btn btn-primary word-search-button" title="Tìm kiếm" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle word-list-table">
                    <thead>
                        <tr>
                            <th class="text-center word-order-column"><span class="word-desktop-heading">Số thứ tự</span><span class="word-mobile-heading">STT</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Chữ Hán</span><span class="word-mobile-heading">Chữ Hán</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Quan thoại</span><span class="word-mobile-heading">Quan thoại</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Hán Việt</span><span class="word-mobile-heading">Hán Việt</span></th>
                            <th class="text-center word-actions-column"><span class="word-desktop-heading">Thao tác</span><span class="word-mobile-heading">#</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $index => $detail)
                            <tr class="word-data-row">
                                <td class="text-center word-order-cell" data-label="STT">{{ $detail->sort_order }}</td>
                                <td class="text-center word-hanzi-cell word-copyable" data-label="Chữ Hán" style="font-size:35px; line-height:1.25;">{{ $detail->name_chu_han }}</td>
                                <td class="text-center word-language-cell word-copyable" data-label="Quan thoại">{{ $detail->name_quan_hoa }}</td>
                                <td class="text-center word-language-cell word-copyable" data-label="Hán Việt">{{ $detail->name_han_viet }}</td>
                                <td class="text-center word-actions-cell" data-label="Thao tác">
                                    <button type="button" class="btn btn-warning btn-sm word-action-button" onclick="JS_Product.wordForm('{{ $product->id }}', '{{ $detail->id }}')" title="Sửa"><i class="far fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm word-action-button" onclick="JS_Product.deleteWord('{{ $product->id }}', '{{ $detail->id }}')" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center word-empty-cell">{{ $search !== '' ? 'Không tìm thấy chữ phù hợp.' : 'Sản phẩm này chưa có chữ.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .word-list-dialog { max-width: 1050px; }
    .word-list-modal { overflow: hidden; border: 0; border-radius: 16px; box-shadow: 0 20px 55px rgba(15, 23, 42, .18); }
    .word-list-modal .modal-header { padding: 20px 24px; border-bottom: 1px solid #edf0f4; }
    .word-list-modal .modal-title { color: #263754; font-size: 20px; font-weight: 650; }
    .word-list-modal .modal-body { padding: 20px 24px 24px; }
    .word-list-modal .btn-success { border-radius: 9px; padding: 8px 14px; }
    .word-filter-form { display: grid; grid-template-columns: 145px minmax(180px, 1fr) 42px; gap: 8px; max-width: 520px; margin: 0 0 14px auto; }
    .word-filter-form .form-control { min-width: 0; height: 38px; border-color: #e2e8f0; border-radius: 9px; font-size: 13px; }
    .word-search-button { height: 38px; margin: 0; padding: 0; border-radius: 9px; }
    .word-list-table { width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0; border: 1px solid #e6ebf1; border-radius: 12px; overflow: hidden; }
    .word-list-table th, .word-list-table td { vertical-align: middle; overflow-wrap: anywhere; }
    .word-list-table thead th { padding: 12px 8px; background: #f3f6fa; color: #53627a; border: 0; border-bottom: 1px solid #e6ebf1; font-size: 12px; font-weight: 650; }
    .word-list-table tbody td { padding: 12px 8px; background: #fff !important; color: #263449; border: 0; border-bottom: 1px solid #edf1f5; }
    .word-list-table tbody tr:last-child td { border-bottom: 0; }
    .word-order-column, .word-order-cell { width: 90px; }
    .word-order-column, .word-actions-column { white-space: nowrap; }
    .word-actions-column, .word-actions-cell { width: 84px; }
    .word-hanzi-cell { font-size: 35px; line-height: 1.25; }
    .word-language-cell { font-size: 16px; }
    .word-copyable { -webkit-user-select: text !important; user-select: text !important; -webkit-touch-callout: default; cursor: text; }
    .word-actions-cell { white-space: nowrap; }
    .word-action-button { min-width: 30px; padding: 6px 8px; margin: 0 2px; border-radius: 8px; }
    .word-mobile-heading { display: none; }

    @media (max-width: 575.98px) {
        .word-list-dialog { width: calc(100% - 12px); max-width: none; margin: 6px auto; }
        .word-list-modal { border-radius: 14px; }
        .word-list-modal .modal-header { padding: 14px 15px; }
        .word-list-modal .modal-title { font-size: 16px; }
        .word-list-modal .modal-body { padding: 12px 10px 14px; background: #f8fafc; }
        .word-list-modal .modal-body > .d-flex { margin-bottom: 12px !important; }
        .word-list-modal .btn-success { padding: 7px 12px; font-size: 12px; }
        .word-filter-form { grid-template-columns: 104px minmax(0, 1fr) 36px; gap: 5px; width: 100%; max-width: none; margin-bottom: 11px; }
        .word-filter-form .form-control { height: 35px; padding: 6px 7px; font-size: 11px; }
        .word-search-button { width: 36px; height: 35px; font-size: 12px; }
        .word-list-modal .table-responsive { overflow: visible; }
        .word-list-table { display: block; border: 0; border-radius: 0; margin-bottom: 0; }
        .word-list-table thead { display: block; width: 100%; margin-bottom: 7px; }
        .word-list-table thead tr {
            display: grid;
            grid-template-columns: 38px repeat(3, minmax(0, 1fr)) 42px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #e3e9f0;
            border-radius: 9px;
            background: #edf2f7;
        }
        .word-list-table thead th {
            display: block;
            width: auto !important;
            min-width: 0;
            padding: 8px 2px;
            background: transparent;
            color: #526177;
            font-size: 9px;
            font-weight: 700;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
            border: 0;
            border-right: 1px solid #e0e7ef;
        }
        .word-list-table thead th:last-child { border-right: 0; }
        .word-desktop-heading { display: none; }
        .word-mobile-heading { display: inline; }
        .word-list-table tbody { display: block; width: 100%; }
        .word-list-table tbody .word-data-row {
            display: grid;
            grid-template-columns: 38px repeat(3, minmax(0, 1fr)) 42px;
            width: 100%;
            margin-bottom: 9px;
            border: 1px solid #e3e9f0;
            border-radius: 11px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 7px rgba(30, 50, 80, .06);
        }
        .word-list-table tbody td {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            padding: 9px 4px;
            border: 0;
            border-right: 1px solid #edf1f5;
            background: transparent !important;
            color: #263449;
            font-size: 13px;
            line-height: 1.25;
        }
        .word-list-table tbody td::before {
            content: attr(data-label);
            display: block;
            margin-bottom: 5px;
            color: #78808a;
            font-size: 9px;
            font-weight: 600;
            line-height: 1.1;
            white-space: nowrap;
        }
        .word-list-table tbody .word-order-cell::before,
        .word-list-table tbody .word-hanzi-cell::before,
        .word-list-table tbody .word-language-cell::before {
            display: none;
        }
        .word-list-table .word-order-cell { width: auto; color: #526177; font-size: 12px; }
        .word-list-table .word-hanzi-cell { font-size: 35px; }
        .word-list-table .word-language-cell { font-size: 14px; font-weight: 500; }
        .word-list-table .word-actions-cell {
            width: auto;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 4px;
            align-content: center;
            justify-content: center;
            border-right: 0;
        }
        .word-list-table .word-actions-cell::before { display: none; }
        .word-list-table .word-action-button {
            min-width: 24px;
            width: 24px;
            height: 24px;
            padding: 2px;
            margin: 0;
            font-size: 10px;
            line-height: 1;
            border-radius: 7px;
        }
        .word-list-table .word-empty-cell { display: block; padding: 14px 8px; border: 1px solid #e3e9f0; border-radius: 10px; }
    }
</style>
