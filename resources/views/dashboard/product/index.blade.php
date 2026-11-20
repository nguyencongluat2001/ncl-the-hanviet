@extends('dashboard.layouts.index')
@section('body')
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_Product.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dist\js\backend\pages\JS_System_Security.js') }}"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    {{-- <link  href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" /> --}}
    <div class="container-fluid" >
        <div class="row">
            <form action="" method="POST" id="frmProduct_index">
                <input style="display:none" type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <div class="breadcrumb-input-fix d-sm-flex align-items-center justify-content-between" style="padding-top:20px">
                        <!-- <span><H5>DANH SÁCH BÀI VIẾT</H5></span> -->
                    <!-- <div class="breadcrumb-input-right">
                        <button class="btn btn-success btn-sm shadow-sm" id="btn_add" type="button"data-toggle="tooltip"
                            data-original-title="Thêm người dùng"><i class="fas fa-user-plus"></i></button>
                        <button class="btn btn-warning btn-sm shadow-sm" id="btn_edit" type="button"data-toggle="tooltip"
                            data-original-title="SỬa người dùng"><i class="fas fa-user-edit"></i></button>
                        <button class="btn btn-danger btn-sm shadow-sm" id="btn_delete" type="button"data-toggle="tooltip"
                            data-original-title="Xóa người dùng"><i class="fas fa-user-times"></i></button>
                    </div> -->
                </div>
                <section class="content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row form-group product-toolbar">
                            <div class="col-md-3 product-toolbar-actions">
                            <button class="btn btn-success shadow-sm" id="btn_add" type="button"data-toggle="tooltip"
                            data-original-title="Thêm bài viết"><i class="fas fa-plus"></i></button>
                            </div>
                                <div class="col-md-2 product-toolbar-category">
                                    <select class="form-control input-sm chzn-select" name="category"
                                        id="category">
                                        <option value=''>-- Chọn thể loại --</option>
                                        @foreach($data['category'] as $item)
                                            <option value="{{$item['code_category']}}" @if($loop->first) selected @endif>{{$item['name_category']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group product-toolbar-search">
                                    <!-- <span class="input-group-text text-body">
                                        <i class="fas fa-search"
                                            aria-hidden="true"></i>
                                        </span> -->
                                    <input id="search" name="search" type="text" class="form-control" placeholder="Tìm kiếm...">
                                </div>
                                <button id="txt_search" name="txt_search" type="button" class="btn btn-dark product-toolbar-submit"><i class="fas fa-search"></i></button>
                            </div>
                            <!-- Màn hình danh sách -->
                            <div class="row" id="table-container" style="padding-top:10px"></div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editmodal" role="dialog"></div>
    <div class="modal fade" id="wordsmodal" role="dialog" aria-hidden="true"></div>
    <div class="modal fade" id="productImageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl product-image-dialog" role="document">
            <div class="modal-content product-image-modal">
                <div class="product-image-modal-header">
                    <div id="productImageTitle" class="product-image-title">Ảnh sản phẩm</div>
                    <div class="product-image-controls">
                        <input id="productImageZoomRange" type="range" min="100" max="400" step="5" value="100" oninput="JS_Product.setProductImageZoom(this.value / 100)" aria-label="Điều chỉnh độ phóng đại" title="Kéo để điều chỉnh độ phóng đại">
                        <span id="productImageZoomLabel">100%</span>
                        <button type="button" onclick="JS_Product.resetProductImageZoom()" aria-label="Kích thước ban đầu">100%</button>
                        <button type="button" class="product-image-close" onclick="JS_Product.closeProductImageModal()" aria-label="Đóng">×</button>
                    </div>
                </div>
                <div class="product-image-viewport">
                    <img id="productImagePreview" src="" alt="Ảnh sản phẩm">
                </div>
            </div>
        </div>
    </div>
    <div class="modal " id="editPassmodal" role="dialog" style=" width: 100%;height: 100%;background: #0000007d; background-repeat:no-repeat;background-size: cover;"></div>
    <div class="modal " id="addfile" role="dialog"></div>

    <div id="dialogconfirm"></div>
    <script src='../assets/js/jquery.js'></script>
    <script type="text/javascript">
        var baseUrl = '{{ url('') }}';
        var JS_Product = new JS_Product(baseUrl, 'system', 'product');
        $(document).ready(function($) {
            JS_Product.loadIndex(baseUrl);
        })
        // var JS_System_Security = new JS_System_Security();
        // $(document).ready(function($) {
        //     JS_System_Security.security();
        // })
    </script>
@endsection

@section('css')
<style>
    .product-toolbar { display: flex; align-items: center; gap: 8px; margin: 0; text-align: initial; }
    .product-toolbar-actions { flex: 0 0 auto; width: auto; padding: 0; display: flex; gap: 6px; }
    .product-toolbar-actions .btn { width: 38px; height: 36px; padding: 0; border-radius: 9px; }
    .product-toolbar-category { flex: 0 0 190px; width: auto; padding: 0; }
    .product-toolbar-category .form-control { height: 36px; }
    .product-toolbar-search { flex: 1 1 auto; width: auto; min-width: 0; height: 36px; }
    .product-toolbar-search .form-control { min-width: 0; height: 36px; }
    .product-toolbar-submit { flex: 0 0 40px; width: 40px; height: 36px; padding: 0; border-radius: 9px; }
    .product-image-dialog { max-width: min(1100px, 94vw); }
    .product-image-modal { overflow: hidden; border: 0; border-radius: 12px; background: #111827; }
    .product-image-modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 9px 12px; color: #fff; }
    .product-image-title { overflow: hidden; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; }
    .product-image-controls { display: flex; flex: 0 0 auto; align-items: center; gap: 6px; }
    .product-image-controls button { min-width: 34px; height: 32px; padding: 0 9px; border: 1px solid #64748b; border-radius: 7px; background: #273449; color: #fff; font-size: 15px; }
    .product-image-controls input[type="range"] { width: 120px; height: 24px; margin: 0; accent-color: #60a5fa; }
    .product-image-controls span { min-width: 38px; color: #fff; font-size: 12px; text-align: center; }
    .product-image-controls .product-image-close { font-size: 20px; }
    .product-image-viewport { display: flex; max-height: 82vh; min-height: 180px; align-items: center; justify-content: center; overflow: auto; background: #0b1120; }
    .product-image-viewport img { max-width: 100%; max-height: 82vh; object-fit: contain; transform: scale(1); transform-origin: center center; transition: transform .15s ease; }

    @media (max-width: 575.98px) {
        .product-toolbar { display: flex; flex-wrap: nowrap !important; align-items: center !important; width: 100%; gap: 5px; margin: 0 0 6px; }
        .product-toolbar > * { min-width: 0; }
        .product-toolbar-actions { display: flex; align-items: center; gap: 3px; }
        .product-toolbar-actions .btn { width: 30px; height: 30px; min-height: 30px; align-self: center; padding: 0; font-size: 11px; line-height: 28px; border-radius: 7px; }
        .product-toolbar-category { flex: 0 0 92px; width: 92px; min-width: 0; }
        .product-toolbar-category .form-control,
        .product-toolbar-category .chosen-container,
        .product-toolbar-category .chosen-container-single .chosen-single { box-sizing: border-box; height: 30px !important; min-height: 30px; font-size: 10px; }
        .product-toolbar-category .form-control { padding: 3px 5px; }
        .product-toolbar-category .chosen-container-single .chosen-single { padding: 0 5px; line-height: 28px; }
        .product-toolbar-category .chosen-container-single .chosen-single div b { background-position-y: 4px; }
        .product-toolbar-search { flex: 1 1 0; width: auto; height: 30px; min-height: 30px; }
        .product-toolbar-search .form-control { box-sizing: border-box; height: 30px !important; min-height: 30px; padding: 4px 7px; font-size: 11px; }
        .product-toolbar-submit { flex: 0 0 30px; width: 30px; height: 30px; min-height: 30px; padding: 0; font-size: 11px; line-height: 28px; border-radius: 7px; }
        #table-container { padding-top: 4px !important; }
        .product-image-dialog { width: calc(100% - 16px); max-width: none; margin: 8px auto; }
        .product-image-modal-header { padding: 7px 8px; }
        .product-image-title { max-width: 38vw; font-size: 12px; }
        .product-image-controls { gap: 4px; }
        .product-image-controls input[type="range"] { width: 76px; }
        .product-image-controls span { min-width: 32px; font-size: 10px; }
        .product-image-controls button { min-width: 30px; height: 30px; padding: 0 6px; font-size: 11px; }
        .product-image-controls .product-image-close { font-size: 18px; }
        .product-image-viewport { max-height: 82dvh; }
        .product-image-viewport img { max-height: 82dvh; }
    }
</style>
@endsection

