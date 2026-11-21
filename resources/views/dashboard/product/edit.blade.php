<div class="modal-dialog modal-xl">
    <div class="modal-content card">
        <div class="modal-header">
            <h5 class="modal-title">Cập nhật bài viết</h5>
            <span type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">
                X
            </span>
        </div>
        <div class="card-body">
            <form id="frmAdd" role="form" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="id" value="{{!empty($data['id'])?$data['id']:''}}">
                <div class="row product-edit-layout">
                    <div class="col-md-4 product-image-column">
                        <div class="product-image-panel">
                            <span class="control-label required">Chọn ảnh đại diện</span>
                            <label for="upload_image" class="label-upload">Chọn ảnh</label>
                            <input type="file" hidden name="upload_image" id="upload_image" onchange="readURL(this)">
                            <div class="product-image-zoom-controls">
                                <label for="productImageZoomRange">Zoom ảnh</label>
                                <input id="productImageZoomRange" type="range" min="50" max="300" step="5" value="100" oninput="setProductImageZoom(this.value)" aria-label="Phóng to hoặc thu nhỏ ảnh">
                                <span id="productImageZoomLabel">100%</span>
                                <button type="button" onclick="setProductImageZoom(100)">Đặt lại</button>
                            </div>
                            <div class="product-image-zoom" id="productImageViewerStage">
                                @if(!empty($data['image']))
                                <img id="show_img" src="{{url('/file-image-client/product/')}}/{{$data['image']}}" alt="Ảnh đại diện sản phẩm">
                                @else
                                <img id="show_img" hidden alt="Ảnh đại diện sản phẩm">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 product-info-column">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p for="example-text-input" class="form-control-label required">Thể loại</p>
                            <select class="form-control input-sm chzn-select" name="code_category" id="code_category">
                                <option value=''>-- Chọn thể loại --</option>
                                @foreach($data['category'] as $item)

                                <option @if((isset($data['code']) && $data['code'] == $item['code_category']) || 
                                        (isset($data['code_category']) && $data['code_category'] == $item['code_category'])) selected @endif 
                                        value="{{$item['code_category']}}">{{$item['name_category']}}</option>
                                
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <p for="example-text-input" class="form-control-label required">Tên </p>
                            <input class="form-control" type="text" value="{{!empty($data['name'])?$data['name']:''}}" name="name" id="name" placeholder="Nhập Tên..." />
                        </div>
                    </div>
                    {{-- trạng thái --}}
                    <!-- <div class="col-md-6">
                        <div class="row form-group" id="div_hinhthucgiai">
                            <span class="control-label">Trạng thái</span><br>
                            <div>
                                <input type="checkbox" name="status" id="status" {{(isset($data['status']) && $data['status'] == '1') ? 'checked' : ''}} />
                                <label for="status">Hoạt động</label> <br>
                            </div>
                        </div>
                    </div> -->
                    <!-- <br> -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <p for="example-text-input" class="form-control-label">Thông tin sản phẩm</p> -->
                            <textarea class="form-control" type="text" rows="10" cols="30" name="decision" id="decision" placeholder="Thông tin sản phẩm...">{{!empty($data['decision'])?$data['decision']:''}}</textarea>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="modal-footer product-edit-footer">
                        <button id='btn_create' class="btn btn-primary btn-sm" type="button">Cập nhật</button>
                        <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style="background: #f1f2f2;">Đóng</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<style>
    .product-edit-layout { align-items: stretch; }
    .product-image-column { display: flex; }
    .product-image-panel { position: sticky; top: 12px; width: 100%; align-self: flex-start; }
    .product-image-panel .control-label { display: block; margin-bottom: 8px; }
    .product-image-panel .label-upload { display: inline-block; margin-bottom: 12px; }
    .product-image-zoom-controls { display: flex; align-items: center; gap: 8px; margin: 0 0 8px; font-size: 12px; }
    .product-image-zoom-controls label { margin: 0; white-space: nowrap; }
    .product-image-zoom-controls input { flex: 1; min-width: 50px; accent-color: #4f6ee8; }
    .product-image-zoom-controls button { padding: 4px 8px; border: 1px solid #d7deea; border-radius: 6px; background: #fff; font-size: 12px; }
    .product-image-zoom { display: block; width: 100%; height: min(62vh, 620px); min-height: 280px; padding: 8px; overflow: auto; overscroll-behavior: contain; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; -webkit-overflow-scrolling: touch; }
    .product-image-zoom { cursor: grab; }
    .product-image-zoom.is-dragging { cursor: grabbing; user-select: none; }
    .product-image-zoom img { display: block; width: 100%; max-width: none; height: auto; object-fit: contain; }
    .product-image-zoom img[hidden] { display: none; }
    .product-edit-footer { grid-column: 1 / -1; width: 100%; justify-content: flex-end; gap: 8px; padding-bottom: 0; }
    .product-edit-layout .cke_contents { min-height: 420px; }
    @media (max-width: 767.98px) {
        .product-image-panel { position: static; margin-bottom: 18px; }
        .product-image-zoom { height: 55vh; min-height: 220px; }
        .product-image-zoom-controls { gap: 6px; }
    }
</style>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#show_img').attr('src', e.target.result);
                setProductImageZoom(document.getElementById('productImageZoomRange').value);
            };
            $("#show_img").removeAttr('hidden');

            reader.readAsDataURL(input.files[0]);
        }
    }
    function setProductImageZoom(value) {
        var image = document.getElementById('show_img');
        var stage = document.getElementById('productImageViewerStage');
        var range = document.getElementById('productImageZoomRange');
        var zoom = Math.max(50, Math.min(300, Number(value) || 100));
        if (!image || !stage) return;
        range.value = zoom;
        document.getElementById('productImageZoomLabel').textContent = zoom + '%';
        image.style.width = (Math.max(1, stage.clientWidth - 18) * zoom / 100) + 'px';
    }
    window.addEventListener('resize', function() {
        var range = document.getElementById('productImageZoomRange');
        if (range) setProductImageZoom(range.value);
    });
    (function enableImageGestures() {
        var stage = document.getElementById('productImageViewerStage');
        var range = document.getElementById('productImageZoomRange');
        if (!stage || !range) return;
        stage.addEventListener('wheel', function(event) {
            event.preventDefault();
            var step = event.deltaY < 0 ? 10 : -10;
            setProductImageZoom(Number(range.value) + step);
        }, { passive: false });

        var dragging = false;
        var dragStartX = 0;
        var dragStartY = 0;
        var scrollStartLeft = 0;
        var scrollStartTop = 0;
        stage.addEventListener('mousedown', function(event) {
            if (event.button !== 0) return;
            dragging = true;
            dragStartX = event.clientX;
            dragStartY = event.clientY;
            scrollStartLeft = stage.scrollLeft;
            scrollStartTop = stage.scrollTop;
            stage.classList.add('is-dragging');
            event.preventDefault();
        });
        window.addEventListener('mousemove', function(event) {
            if (!dragging) return;
            stage.scrollLeft = scrollStartLeft - (event.clientX - dragStartX);
            stage.scrollTop = scrollStartTop - (event.clientY - dragStartY);
        });
        window.addEventListener('mouseup', function() {
            dragging = false;
            stage.classList.remove('is-dragging');
        });

        var pinchStartDistance = 0;
        var pinchStartZoom = 100;
        function touchDistance(touches) {
            var dx = touches[0].clientX - touches[1].clientX;
            var dy = touches[0].clientY - touches[1].clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }
        stage.addEventListener('touchstart', function(event) {
            if (event.touches.length === 2) {
                pinchStartDistance = touchDistance(event.touches);
                pinchStartZoom = Number(range.value) || 100;
            }
        }, { passive: true });
        stage.addEventListener('touchmove', function(event) {
            if (event.touches.length !== 2 || !pinchStartDistance) return;
            event.preventDefault();
            var scale = touchDistance(event.touches) / pinchStartDistance;
            setProductImageZoom(pinchStartZoom * scale);
        }, { passive: false });
        stage.addEventListener('touchend', function(event) {
            if (event.touches.length < 2) pinchStartDistance = 0;
        }, { passive: true });
        stage.addEventListener('touchcancel', function() { pinchStartDistance = 0; }, { passive: true });
    })();
    CKEDITOR.on('dialogDefinition', function(event) {
        if (event.data.name !== 'table') return;
        var infoTab = event.data.definition.getContents('info');
        if (!infoTab) return;
        var rows = infoTab.get('txtRows');
        var columns = infoTab.get('txtCols');
        if (rows) rows['default'] = 21;
        if (columns) columns['default'] = 12;
    });

    CKEDITOR.replace('decision', {
        filebrowserBrowseUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserUploadUrl: 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserImageBrowseUrl: 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
        on: {
            instanceReady: function(event) {
                var editor = event.editor;
                var body = editor.document.getBody();
                editor.document.appendStyleText('table { border-collapse: collapse; } table td, table th { border: 1px solid #555 !important; }');
                if (!body.getText().trim() && !body.findOne('table')) {
                    var table = '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;width:100%;table-layout:fixed;border:1px solid #555">';
                    table += '<thead><tr>';
                    for (var headingColumn = 0; headingColumn < 12; headingColumn++) {
                        var heading = headingColumn % 2 === 0 ? 'Chữ Hán' : 'Quan hỏa';
                        table += '<th scope="col" style="width:8.33%;height:40px;border:1px solid #555;text-align:center;font-weight:bold">' + heading + '</th>';
                    }
                    table += '</tr></thead><tbody>';
                    for (var row = 0; row < 20; row++) {
                        table += '<tr>';
                        for (var column = 0; column < 12; column++) {
                            table += '<td style="width:8.33%;height:54px;border:1px solid #555"><p>&nbsp;</p></td>';
                        }
                        table += '</tr>';
                    }
                    table += '</table><p>&nbsp;</p>';
                    editor.setData(table);
                }
            }
        },
    });
</script>
