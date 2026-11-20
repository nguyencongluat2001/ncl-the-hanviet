<style>
    .unit-edit span {
        font-size: 19px;
    }
</style>
@php
use Modules\System\Dashboard\Blog\Models\BlogImagesModel;

@endphp
<div class="table-responsive pmd-card pmd-z-depth ">
    <table  id="table-data" class="table  table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
            <tr>
                <td align="center"><input type="checkbox" name="chk_all_item_id"
                        onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></td>
                <td align="center"><b>STT</b></td>
                <!-- <td align="center"><b>Ngày thêm</b></td> -->
                <td align="center"><b>Tên</b></td>
                <td align="center"><b>Ảnh đại diện</b></td>
                <!-- <td align="center"><b>Trạng thái</b></td> -->
                <td align="center"><b>#</b></td>
            </tr>
        </thead>
        <tbody>
        @foreach ($datas as $key => $data)
                <tr>
                    <td style="width:5% ;vertical-align: middle;" align="center"><input type="checkbox" name="chk_item_id"
                            value="{{ $data['id'] }}"></td>
                    <td style="width:5% ;vertical-align: middle;" align="center">{{ $key + 1 }}</td>
                    <!-- <td style="width:5% ;vertical-align: middle;" align="center">{{ $data['created_at']}}</td> -->
                    <td style="width:20% ;white-space: inherit;vertical-align: middle;">{{ $data['name'] }}</td>
                    <td style="width:20%;vertical-align: middle;" align="center"><img  src="{{url('/file-image-client/product/')}}/{{ !empty($data['image'])?$data['image']:'' }}" alt="Image" style="height: 150px;width: 150px;object-fit: cover;"></td>
                    <!-- <td style="width:5% ;vertical-align: middle;" align="center">{{($data['status'] == '1') ? 'Hoạt động' : 'Không hoạt động'}}</td> -->
                    <td style="width:5% ;vertical-align: middle;" align="center">
                        <button onclick="JS_Product.wordsList('{{ $data['id'] }}')" class="btn btn-light product-row-action" type="button" title="Quản lý chữ của sản phẩm">
                            <i style="color:#344767" class="far fa-list-alt"></i>
                        </button>
                         <button onclick="JS_Product.previewProductImage(this)" data-image="{{ asset('file-image-client/product/' . ($data['image'] ?? '')) }}" data-title="{{ $data['name'] }}" class="btn btn-light product-row-action" type="button" title="Xem ảnh sản phẩm" aria-label="Xem ảnh sản phẩm">
                              <i style="" class="far fa-eye"></i>
                        </button>
                        <button onclick="JS_Product.editOne('form#frmProduct_index', '{{ $data['id'] }}')" class="btn btn-light product-row-action" type="button" title="Sửa sản phẩm">
                            <i class="far fa-edit"></i>
                        </button>
                        <button onclick="JS_Product.deleteOne('form#frmProduct_index', '{{ $data['id'] }}')" class="btn btn-light product-row-action" type="button" title="Xóa sản phẩm">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="width:100%" class="row">
    <tfoot>
        <tr>
            <td colspan="10">
                {{$datas->links('pagination.phantrang')}}
            </td>
        </tr>
    </tfoot>
    </div>
</div>
<style>
    #table-data .product-row-action { width: 30px; height: 30px; margin: 2px; padding: 0; border: 0; border-radius: 7px; color: #202b3d; background: #f1f4f8; box-shadow: none; }
    #table-data .product-row-action:hover { background: #e2e8f0; }
    @media (max-width: 575.98px) {
        #table-data .product-row-action { width: 36px; height: 36px; min-width: 36px; min-height: 36px; margin: 2px; font-size: 15px; border-radius: 8px; }
    }
</style>
