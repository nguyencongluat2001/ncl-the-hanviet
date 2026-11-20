@extends('dashboard.layouts.index')

@section('body')
    <script type="text/javascript" src="{{ URL::asset('dist/js/backend/pages/JS_Product.js') }}"></script>
    <div class="container-fluid all-product-words" id="all-product-words">
        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-12">
                <section class="content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form id="word-search-page-filter" class="word-search-page-filter" method="GET" action="{{ url('/system/urlsearch/index') }}">
                                <select name="search_by" class="form-control" aria-label="Chọn cột để lọc">
                                    <option value="name_chu_han" @selected($searchBy === 'name_chu_han')>Chữ Hán</option>
                                    <option value="name_quan_hoa" @selected($searchBy === 'name_quan_hoa')>Quan thoại</option>
                                    <option value="name_han_viet" @selected($searchBy === 'name_han_viet')>Hán Việt</option>
                                </select>
                                <input name="search" class="form-control" type="search" value="{{ $search }}" placeholder="Nhập nội dung cần tìm..." aria-label="Nội dung cần tìm">
                                <button type="submit" class="btn btn-primary word-search-page-button" title="Tìm kiếm" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                            </form>

                            <div class="table-responsive">
                                <table class="table word-search-page-table">
                                    <thead>
                                        <tr>
                                            <th class="word-search-order-column">Số thứ tự</th>
                                            <th>Chữ Hán</th>
                                            <th>Quan Thoại</th>
                                            <th>Hán Việt</th>
                                            <th class="word-search-actions-column">Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($details as $detail)
                                            <tr class="word-search-data-row">
                                                <td class="text-center" data-label="STT">{{ $detail->sort_order }}</td>
                                                <td class="word-search-hanzi" data-label="Chữ Hán">{{ $detail->name_chu_han }}</td>
                                                <td data-label="Quan Thoại">{{ $detail->name_quan_hoa }}</td>
                                                <td data-label="Hán Việt">{{ $detail->name_han_viet }}</td>
                                                <td class="word-search-actions" data-label="Thao Tác">
                                                    <button type="button" class="btn btn-warning btn-sm" onclick="JS_Product.wordForm('{{ $detail->product_id }}', '{{ $detail->id }}')" title="Sửa"><i class="far fa-edit"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="JS_Product.deleteWord('{{ $detail->product_id }}', '{{ $detail->id }}')" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-4">{{ $search !== '' ? 'Không tìm thấy chữ phù hợp.' : 'Chưa có chữ nào trong product_details.' }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">{{ $details->links('pagination.phantrang') }}</div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade" id="wordsmodal" role="dialog" aria-hidden="true"></div>

    <script>
        var baseUrl = '{{ url('') }}';
        var JS_Product = new JS_Product(baseUrl, 'system', 'product');
        $(function () {
            $('.link-product').removeClass('active');
            NclLib.active('.link-urlsearch');
        });
    </script>
@endsection

<style>
    .word-search-page-filter { display: grid; grid-template-columns: 145px minmax(200px, 320px) 42px; justify-content: end; gap: 8px; margin: 12px 0 18px; }
    .word-search-page-filter .form-control { height: 38px; min-width: 0; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 13px; }
    .word-search-page-button { height: 38px; margin: 0; padding: 0; border-radius: 10px; }
    .word-search-page-table { width: 100%; border: 1px solid #cbd5e1; border-radius: 12px; border-collapse: separate; border-spacing: 0; overflow: hidden; table-layout: fixed; }
    .word-search-page-table th { padding: 12px 10px; border: 0; border-bottom: 1px solid #cbd5e1; background: #f3f6fa; color: #53627a; font-size: 12px; font-weight: 650; text-align: center !important; }
    .word-search-page-table td { padding: 12px 10px; border: 0; border-bottom: 1px solid #dbe2ea; background: #fff; color: #263449; text-align: center !important; vertical-align: middle; overflow-wrap: anywhere; }
    .word-search-page-table tr:last-child td { border-bottom: 0; }
    .word-search-order-column { width: 100px; }
    .word-search-actions-column { width: 100px; }
    .word-search-page-table td.word-search-hanzi { font-size: 35px !important; line-height: 1.2; -webkit-user-select: text; user-select: text; }
    .word-search-actions { white-space: nowrap; }
    .word-search-actions .btn { min-width: 30px; padding: 5px 8px; margin: 0 2px; border-radius: 8px; }

    @media (max-width: 575.98px) {
        .word-search-page-filter { grid-template-columns: 104px minmax(0, 1fr) 36px; gap: 5px; margin: 8px 0 12px; }
        .word-search-page-filter .form-control { height: 35px; padding: 6px 7px; font-size: 11px; }
        .word-search-page-button { width: 36px; height: 35px; font-size: 12px; }
        .word-search-page-table { display: block; border: 0; border-radius: 0; }
        .word-search-page-table thead { display: block; margin-bottom: 7px; }
        .word-search-page-table thead tr,
        .word-search-page-table tbody .word-search-data-row {
            display: grid;
            grid-template-columns: 38px repeat(3, minmax(0, 1fr)) 42px;
            width: 100%;
        }
        .word-search-page-table thead tr { overflow: hidden; border: 1px solid #e3e9f0; border-radius: 9px; background: #edf2f7; }
        .word-search-page-table th { display: block; width: auto !important; min-width: 0; padding: 8px 2px; border: 0; border-right: 1px solid #e0e7ef; background: transparent; font-size: 9px; white-space: nowrap; overflow: hidden; }
        .word-search-page-table th:last-child { border-right: 0; }
        .word-search-page-table tbody { display: block; }
        .word-search-page-table tbody .word-search-data-row { margin-bottom: 9px; overflow: hidden; border: 1px solid #e3e9f0; border-radius: 11px; background: #fff; box-shadow: 0 2px 7px rgba(30, 50, 80, .06); }
        .word-search-page-table td { display: flex; min-width: 0; justify-content: center; align-items: center; padding: 9px 4px; border: 0; border-right: 1px solid #edf1f5; background: transparent; font-size: 13px; line-height: 1.25; }
        .word-search-page-table td::before { content: attr(data-label); display: none; }
        .word-search-page-table td.word-search-hanzi { font-size: 35px !important; }
        .word-search-page-table .word-search-actions { flex-wrap: wrap; gap: 4px; border-right: 0; }
        .word-search-page-table .word-search-actions .btn { width: 24px; height: 24px; min-width: 24px; padding: 2px; margin: 0; font-size: 10px; border-radius: 7px; }
    }
</style>
