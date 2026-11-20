<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content card">
        <div class="modal-header">
            <h5 class="modal-title">{{ $detail ? 'Sửa' : 'Thêm' }} — {{ $product->name }}</h5>
            <button type="button" class="btn btn-sm btn-light" @if($returnToAll) onclick="JS_Product.closeWords()" @else onclick="JS_Product.wordsList('{{ $product->id }}')" @endif aria-label="Đóng">×</button>
        </div>
        <div class="modal-body">
            <form id="frmProductWord" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="word_sort_order">Số thứ tự</label>
                        <input class="form-control" type="number" min="1" id="word_sort_order" name="sort_order" value="{{ $detail->sort_order ?? (($product->wordDetails()->max('sort_order') ?? 0) + 1) }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="word_name_chu_han">Chữ Hán</label>
                        <input class="form-control" style="font-size:28px;" id="word_name_chu_han" name="name_chu_han" value="{{ $detail->name_chu_han ?? '' }}">
                    </div>
                     <div class="col-md-6 form-group mb-3">
                        <label for="word_name_quan_hoa">Quan hỏa</label>
                        <input class="form-control" id="word_name_quan_hoa" name="name_quan_hoa" value="{{ $detail->name_quan_hoa ?? '' }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="word_name_han_viet">Hán Việt</label>
                        <input class="form-control" id="word_name_han_viet" name="name_han_viet" required value="{{ $detail->name_han_viet ?? '' }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="word_image">Ảnh (chọn một ảnh)</label>
                        <input class="form-control" type="file" id="word_image" name="image" accept="image/*">
                        <div class="form-text">Tối đa 5 MB. Để trống nếu muốn giữ ảnh hiện tại.</div>
                        <img id="word_image_preview" @if(empty($detail->image)) hidden @endif src="{{ !empty($detail->image) ? asset('file-image-client/product-details/' . $detail->image) : '' }}" alt="Ảnh chữ" style="margin-top:10px;max-width:180px;max-height:180px;object-fit:contain">
                    </div>
                    <div class="col-12 form-group mb-3">
                        <label for="word_decision">Thông tin</label>
                        <textarea class="form-control" rows="10" name="decision" id="word_decision">{{ $detail->decision ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0">
                    <button type="button" class="btn btn-primary btn-sm" onclick="JS_Product.saveWord()">Lưu</button>
                    <button type="button" class="btn btn-default btn-sm" @if($returnToAll) onclick="JS_Product.closeWords()" @else onclick="JS_Product.wordsList('{{ $product->id }}')" @endif>Quay lại</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    if (CKEDITOR.instances.word_decision) {
        CKEDITOR.instances.word_decision.destroy(true);
    }
    CKEDITOR.replace('word_decision', {
        filebrowserBrowseUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserUploadUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserImageBrowseUrl: 'filemanager/dialog.php?type=1&editor=ckeditor&fldr='
    });
    document.getElementById('word_image').addEventListener('change', function (event) {
        var file = event.target.files && event.target.files[0];
        if (!file) return;
        var preview = document.getElementById('word_image_preview');
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
    });
</script>
