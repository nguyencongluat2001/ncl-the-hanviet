<div class="modal-dialog modal-xl word-list-dialog" role="document">
    <div class="modal-content card word-list-modal">
        <div class="modal-header">
            <h5 class="modal-title">Danh sách chữ — {{ $product->name }}</h5>
            <button type="button" class="btn btn-sm btn-light" onclick="JS_Product.closeWords()" aria-label="Đóng">×</button>
        </div>
        <div class="modal-body">
            <div class="word-list-toolbar d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm word-print-button" onclick="JS_Product.printWordList()" title="In danh sách chữ">
                    <i class="fas fa-print"></i> In danh sách
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="JS_Product.wordForm('{{ $product->id }}')">
                    <i class="fas fa-plus"></i> Thêm
                </button>
            </div>
            <form class="word-filter-form" data-product-id="{{ $product->id }}" onsubmit="JS_Product.searchWords(); return false;">
                <select id="word_search_by" class="form-control" aria-label="Chọn cột để lọc">
                    <option value="name_han_viet" @selected($searchBy === 'name_han_viet')>Hán Việt</option>
                    <option value="name_chu_han" @selected($searchBy === 'name_chu_han')>Chữ Hán</option>
                    <option value="name_quan_hoa" @selected($searchBy === 'name_quan_hoa')>Quan hỏa</option>
                </select>
                <input id="word_search_text" class="form-control" type="search" value="{{ $search }}" placeholder="Nhập nội dung cần tìm..." aria-label="Nội dung cần tìm">
                <button type="submit" class="btn btn-primary word-search-button" title="Tìm kiếm" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle word-list-table">
                    <thead>
                        <tr>
                            <th class="text-center word-order-column"><span class="word-desktop-heading">STT</span><span class="word-mobile-heading">STT</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Hán Việt</span><span class="word-mobile-heading">Hán Việt</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Chữ Hán</span><span class="word-mobile-heading">Chữ Hán</span></th>
                            <th class="text-center"><span class="word-desktop-heading">Quan hỏa</span><span class="word-mobile-heading">Quan hỏa</span></th>
                            <th class="text-center word-actions-column"><span class="word-desktop-heading">Thao tác</span><span class="word-mobile-heading">#</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $index => $detail)
                            <tr class="word-data-row">
                                <td class="text-center word-order-cell" data-label="STT">{{ $detail->sort_order }}</td>
                                <td class="text-center word-hanzi-cell word-copyable" data-label="Chữ Hán" style="font-size:35px; line-height:1.25;">{{ $detail->name_chu_han }}</td>
                                <td class="text-center word-language-cell word-copyable" data-label="Quan hỏa">{{ $detail->name_quan_hoa }}</td>
                                <td class="text-center word-language-cell word-copyable" data-label="Hán Việt">{{ $detail->name_han_viet }}</td>
                                <td class="text-center word-actions-cell" data-label="Thao tác">
                                    <button type="button" class="word-icon-action" onclick="JS_Product.wordForm('{{ $product->id }}', '{{ $detail->id }}')" title="Sửa" aria-label="Sửa"><i class="far fa-edit"></i></button>
                                    <button type="button" class="word-icon-action" onclick="JS_Product.deleteWord('{{ $product->id }}', '{{ $detail->id }}')" title="Xóa" aria-label="Xóa"><i class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center word-empty-cell">{{ $search !== '' ? 'Không tìm thấy chữ phù hợp.' : 'Sản phẩm này chưa có chữ.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer word-list-footer">
            <span>{{ $details->count() }} mục{{ $search !== '' ? ' phù hợp' : '' }}</span>
            <button type="button" class="btn btn-sm btn-light" onclick="JS_Product.closeWords()">Đóng</button>
        </div>
    </div>
</div>

<style>
    .word-list-dialog { max-width: 1050px; }
    .word-list-modal { overflow: hidden; border: 0; border-radius: 16px; box-shadow: 0 20px 55px rgba(15, 23, 42, .18); }
.word-list-modal { max-height: 92vh; display: flex; flex-direction: column; }
.word-list-modal .modal-header { flex: 0 0 auto; padding: 10px 16px; border-bottom: 1px solid #edf0f4; }
.word-list-modal .modal-title { color: #263754; font-size: 17px; font-weight: 650; }
.word-list-modal .modal-header .btn { min-width: 34px; min-height: 32px; padding: 4px 9px; }
.word-list-modal .modal-body { min-height: 0; overflow-y: auto; padding: 10px 14px 12px; }
.word-list-footer { flex: 0 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 8px 14px; border-top: 1px solid #edf0f4; color: #64748b; font-size: 13px; }
.word-list-footer .btn { min-width: 64px; border-radius: 7px; }
.word-list-modal .btn-success { border-radius: 8px; padding: 6px 11px; }
    .word-list-toolbar { gap: 7px; }
.word-list-modal .modal-body > .d-flex { margin-bottom: 7px !important; }
.word-filter-form { display: grid; grid-template-columns: 135px minmax(180px, 1fr) 38px; gap: 6px; max-width: 500px; margin: 0 0 8px auto; }
.word-filter-form .form-control { min-width: 0; height: 36px; padding: 5px 9px; border-color: #e2e8f0; border-radius: 8px; font-size: 14px; }
.word-search-button { height: 36px; margin: 0; padding: 0; border-radius: 8px; font-size: 14px; }
.word-list-modal .table-responsive { overflow: visible; }
    .word-list-table { display: block; width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0; border: 1px solid #e6ebf1; border-radius: 12px; overflow: hidden; }
    .word-list-table thead { display: table; width: 100%; table-layout: fixed; }
.word-list-table tbody { display: block; width: 100%; max-height: none; overflow: visible; }
    .word-list-table tbody tr { display: table; width: 100%; table-layout: fixed; }
    .word-list-table th, .word-list-table td { vertical-align: middle; overflow-wrap: anywhere; }
    .word-list-table thead th { padding: 12px 8px; background: #f3f6fa; color: #53627a; border: 0; border-bottom: 1px solid #e6ebf1; font-size: 14px; font-weight: 650; }
    .word-list-table tbody td { padding: 12px 8px; background: #fff !important; color: #263449; border: 0; border-bottom: 1px solid #edf1f5; font-size: 14px; }
    .word-list-table tbody tr:last-child td { border-bottom: 0; }
    .word-order-column, .word-order-cell { width: 90px; }
    .word-order-column, .word-actions-column { white-space: nowrap; }
    .word-actions-column, .word-actions-cell { width: 84px; }
    .word-hanzi-cell { font-size: 35px; line-height: 1.25; }
    .word-language-cell { font-size: 14px; }
    .word-copyable { -webkit-user-select: text !important; user-select: text !important; -webkit-touch-callout: default; cursor: text; }
    .word-actions-cell { white-space: nowrap; }
    .word-icon-action { display: inline-flex; width: 28px; height: 30px; align-items: center; justify-content: center; padding: 0; margin: 0 2px; border: 0; border-radius: 0; background: transparent; color: #111827; font-size: 17px; cursor: pointer; box-shadow: none; }
    .word-icon-action:hover, .word-icon-action:focus { background: transparent; color: #000; outline: none; opacity: .7; }
    .word-mobile-heading { display: none; }

    @media (max-width: 575.98px) {
        .word-list-dialog { width: calc(100% - 12px); max-width: none; margin: 6px auto; }
        .word-list-modal { border-radius: 14px; }
        .word-list-modal { max-height: 94dvh; border-radius: 12px; }
        .word-list-modal .modal-header { padding: 7px 10px; }
        .word-list-modal .modal-title { font-size: 14px; }
        .word-list-modal .modal-header .btn { min-width: 30px; min-height: 28px; padding: 2px 7px; }
        .word-list-footer { padding: 7px 10px calc(7px + env(safe-area-inset-bottom)); }
        .word-list-modal .modal-body { padding: 7px 8px calc(18px + env(safe-area-inset-bottom)); background: #f8fafc; }
        .word-list-modal .modal-body > .d-flex { margin-bottom: 5px !important; }
        .word-list-modal .btn-success { padding: 5px 9px; font-size: 11px; }
        .word-filter-form { grid-template-columns: 92px minmax(0, 1fr) 32px; gap: 4px; width: 100%; max-width: none; margin-bottom: 6px; }
        .word-filter-form .form-control { height: 36px; padding: 5px 7px; font-size: 14px; }
        .word-search-button { width: 36px; height: 36px; font-size: 14px; }
        .word-list-table tbody { max-height: none; padding-bottom: 0; scroll-padding-bottom: 0; }
        .word-list-table { display: block; border: 0; border-radius: 0; margin-bottom: 0; }
        .word-list-table thead { display: block; width: 100%; margin-bottom: 7px; }
        .word-list-table thead tr {
            display: grid;
            grid-template-columns: 38px repeat(3, minmax(0, 1fr)) 42px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #cbd6e3;
            border-radius: 9px;
            background: #dce5f0;
        }
        .word-list-table thead th {
            display: block;
            width: auto !important;
            min-width: 0;
            padding: 8px 2px;
            background: transparent;
            color: #34445c;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
            border: 0;
            border-right: 1px solid #cbd6e3;
        }
        .word-list-table thead th:last-child { border-right: 0; }
        .word-desktop-heading { display: none; }
        .word-mobile-heading { display: inline; }
        .word-list-table tbody { display: block; width: 100%; max-height: none; padding-bottom: 0; scroll-padding-bottom: 0; overflow: visible; }
        .word-list-table tbody .word-data-row {
            display: grid;
            grid-template-columns: 38px repeat(3, minmax(0, 1fr)) 42px;
            width: 100%;
            margin-bottom: 9px;
            border: 1px solid #cbd6e3;
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
            font-size: 14px;
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
        .word-list-table .word-order-cell { width: auto; color: #43536a; font-size: 14px; }
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
        .word-list-table .word-icon-action {
            width: 24px;
            height: 24px;
            margin: 0;
            font-size: 15px;
            line-height: 1;
        }
        .word-list-table .word-empty-cell { display: block; padding: 14px 8px; border: 1px solid #e3e9f0; border-radius: 10px; }
    }

    @media print {
        @page { margin: 12mm; }
        body * { visibility: hidden !important; }
        #wordsmodal, #wordsmodal * { visibility: visible !important; }
        body { background: #fff !important; }
        #wordsmodal { position: absolute !important; inset: 0 !important; display: block !important; width: 100% !important; height: auto !important; margin: 0 !important; overflow: visible !important; opacity: 1 !important; background: #fff !important; }
        #wordsmodal .word-list-dialog { width: 100% !important; max-width: none !important; margin: 0 !important; transform: none !important; }
        #wordsmodal .word-list-modal { max-height: none !important; overflow: visible !important; border: 0 !important; border-radius: 0 !important; box-shadow: none !important; }
        #wordsmodal .word-list-modal .modal-header { padding: 0 0 10px !important; }
        #wordsmodal .word-list-modal .modal-header button,
        #wordsmodal .word-list-toolbar,
        #wordsmodal .word-filter-form,
        #wordsmodal .word-actions-column,
        #wordsmodal .word-actions-cell,
        .modal-backdrop { display: none !important; }
        #wordsmodal .word-list-modal .modal-body { padding: 0 !important; overflow: visible !important; }
        #wordsmodal .word-list-table,
        #wordsmodal .word-list-table thead,
        #wordsmodal .word-list-table tbody { display: table !important; width: 100% !important; max-height: none !important; overflow: visible !important; table-layout: auto !important; }
        #wordsmodal .word-list-table tbody tr { display: table-row !important; width: 100% !important; margin: 0 !important; border: 0 !important; box-shadow: none !important; }
        #wordsmodal .word-list-table tbody td { display: table-cell !important; padding: 8px !important; border-bottom: 1px solid #999 !important; background: #fff !important; color: #111 !important; }
        #wordsmodal .word-list-table thead th.word-actions-column,
        #wordsmodal .word-list-table tbody td.word-actions-cell { display: none !important; }
        #wordsmodal .word-list-table thead th { background: #eee !important; color: #111 !important; }
        #wordsmodal .word-list-table .word-mobile-heading { display: none !important; }
        #wordsmodal .word-list-table .word-desktop-heading { display: inline !important; }
    }
</style>
