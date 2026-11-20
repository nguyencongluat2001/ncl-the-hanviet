function JS_Product(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    NclLib.active('.link-product');
    this.urlPath = baseUrl + '/' + module + '/' + controller;
}

JS_Product.prototype.previewProductImage = function (button) {
    var imageUrl = $(button).attr('data-image');
    var title = $(button).attr('data-title') || 'Ảnh sản phẩm';
    if (!imageUrl) return;

    $('#productImageTitle').text(title);
    $('#productImagePreview').attr('src', imageUrl).attr('alt', title);
    this.resetProductImageZoom();
    this.bindProductImageZoomGestures();
    $('#productImageModal').modal('show');
};

JS_Product.prototype.closeProductImageModal = function () {
    $('#productImageModal').modal('hide');
};

JS_Product.prototype.printWordList = function () {
    window.print();
};

JS_Product.prototype.zoomProductImage = function (direction) {
    var zoom = parseFloat($('#productImagePreview').attr('data-zoom') || '1');
    this.setProductImageZoom(zoom + (direction > 0 ? 0.1 : -0.1));
};

JS_Product.prototype.setProductImageZoom = function (zoom) {
    zoom = Math.max(1, Math.min(4, parseFloat(zoom) || 1));
    var image = $('#productImagePreview');
    image.attr('data-zoom', zoom);
    image.css({
        width: zoom > 1 ? (zoom * 100) + '%' : 'auto',
        maxWidth: zoom > 1 ? 'none' : '100%',
        maxHeight: zoom > 1 ? 'none' : '82vh',
        transform: 'none'
    });
    $('#productImageZoomRange').val(Math.round(zoom * 100));
    $('#productImageZoomLabel').text(Math.round(zoom * 100) + '%');
};

JS_Product.prototype.resetProductImageZoom = function () {
    this.setProductImageZoom(1);
    $('.product-image-viewport').scrollTop(0).scrollLeft(0);
};

JS_Product.prototype.bindProductImageZoomGestures = function () {
    var viewport = document.querySelector('.product-image-viewport');
    if (!viewport || viewport.dataset.zoomGesturesBound) return;
    viewport.dataset.zoomGesturesBound = 'true';

    var pinchDistance = 0;
    viewport.addEventListener('wheel', function (event) {
        if (!event.ctrlKey && !event.metaKey) return;
        event.preventDefault();
        var currentZoom = parseFloat($('#productImagePreview').attr('data-zoom') || '1');
        window.JS_Product.setProductImageZoom(currentZoom + (event.deltaY < 0 ? 0.05 : -0.05));
    }, { passive: false });

    viewport.addEventListener('touchstart', function (event) {
        if (event.touches.length === 2) {
            var dx = event.touches[0].clientX - event.touches[1].clientX;
            var dy = event.touches[0].clientY - event.touches[1].clientY;
            pinchDistance = Math.hypot(dx, dy);
        }
    }, { passive: true });

    viewport.addEventListener('touchmove', function (event) {
        if (event.touches.length !== 2 || !pinchDistance) return;
        event.preventDefault();
        var dx = event.touches[0].clientX - event.touches[1].clientX;
        var dy = event.touches[0].clientY - event.touches[1].clientY;
        var nextDistance = Math.hypot(dx, dy);
        var currentZoom = parseFloat($('#productImagePreview').attr('data-zoom') || '1');
        window.JS_Product.setProductImageZoom(currentZoom * (nextDistance / pinchDistance));
        pinchDistance = nextDistance;
    }, { passive: false });

    viewport.addEventListener('touchend', function (event) {
        if (event.touches.length < 2) pinchDistance = 0;
    }, { passive: true });
};

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
JS_Product.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmProduct_index';
    var oFormCreate = 'form#frmAdd';
    $('.chzn-select').chosen({ height: '100%', width: '100%' });
    myClass.loadList(oForm);

    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
     // form load
     $(oForm).find('#category').change(function () {
        var page = $(oForm).find('#limit').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);
    });
    $(oForm).find('#txt_search').click(function () {
            var page = $(oForm).find('#limit').val();
            var perPage = $(oForm).find('#cbo_nuber_record_page').val();
            myClass.loadList(oForm, page, perPage);
            // return false;
        
    });
}
JS_Product.prototype.loadevent = function (oForm) {
    var myClass = this;
    $('form#frmAdd').find('#btn_create').click(function () {
        myClass.store('form#frmAdd');
    })
    $('form#frmAdd').find('#btn_changePass').click(function () {
        myClass.changePass('form#frmAdd');
    })
    $('form#frmChangePass').find('#btn_updatePass').click(function () {
        myClass.updatePass('form#frmChangePass');
    })
   
}
/**
 * Hàm hiển thị modal
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Product.prototype.add = function (oForm) {
    var url = this.urlPath + '/createForm';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            $('#status').attr('checked', true);
            $('.chzn-select').chosen({ height: '100%', width: '100%' });
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Hàm hiển thêm mới
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Product.prototype.store = function (oFormCreate) {
    var url = this.urlPath + '/create';
    var myClass = this;
    var formdata = new FormData();
    var check = myClass.checkValidate();
    if(check == false){
        return false;
    }
    var status = ''
    $('input[name="status"]:checked').each(function() {
        status =  $(this).val();
    });
    formdata.append('_token', $("#_token").val());
    formdata.append('id', $("#id").val());
    formdata.append('name', $("#name").val());
    formdata.append('code_category', $("#code_category").val());
    formdata.append('trademark', $("#trademark").val());
    formdata.append('sale', $("#sale").val());
    formdata.append('price', $("#price").val());
    formdata.append('image', $("#image").val());
    formdata.append('decision', CKEDITOR.instances.decision.getData());
    formdata.append('status', status);
    $('form#frmAdd input[type=file]').each(function () {
        var count = $(this)[0].files.length;
        for (var i = 0; i < count; i++) {
            formdata.append('file-attack-' + i, $(this)[0].files[i]);
        }
    });
    NclLib.loadding();

    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  NclLib.alertMessageBackend('success', 'Thông báo', 'Cập nhật thành công');
                  $('#editmodal').modal('hide');
                  myClass.loadList(oFormCreate);

            } else {
                NclLib.loadding();
                NclLib.alertMessageBackend('danger', 'Lỗi', 'Cập nhật thất bại!');
            }
        }
    });
}
/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Product.prototype.loadList = function (oForm, numberPage = 1, perPage = 15) {
    var myClass = this;
    var url = this.urlPath + '/loadList';
    var data = 'search=' + $("#search").val();
    data += '&category=' +$("#category").val();
    data += '&offset=' + numberPage;
    data += '&limit=' + perPage;
    $.ajax({
        url: url,
        type: "GET",
        // cache: true,
        data: data,
        success: function (arrResult) {
            $("#table-container").html(arrResult);
            // phan trang
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPages = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPages);
            });
            $(oForm).find('#cbo_nuber_record_page').val(perPage);
            var loadding = NclLib.successLoadding();
            myClass.loadevent(oForm);
        }
    });
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Product.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var listitem = '';
    var i = 0;
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
          var nameMessage = 'Bạn chưa chọn đối tượng!';
          var icon = 'warning';
          var color = '#344767';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if (i > 1) {
          var nameMessage = 'Bạn chỉ được chọn một đối tượng!';
          var icon = 'warning';
          var color = '#344767';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            $('#editmodal').html(arrResult);
            $('#editmodal').modal('show');
            myClass.loadevent(oForm);

        }
    });
}

// Open edit/delete actions directly from a product row.
JS_Product.prototype.selectProductRow = function (productId) {
    var selected = false;
    $('#table-data').find('input[name="chk_item_id"]').each(function () {
        var isTarget = String($(this).val()) === String(productId);
        $(this).prop('checked', isTarget);
        selected = selected || isTarget;
    });
    return selected;
};

JS_Product.prototype.editOne = function (oForm, productId) {
    if (this.selectProductRow(productId)) {
        this.edit(oForm);
    }
};

JS_Product.prototype.deleteOne = function (oForm, productId) {
    if (this.selectProductRow(productId)) {
        this.delete(oForm);
    }
};

// Xoa bài viết
JS_Product.prototype.delete = function (oForm) {
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
          var nameMessage = 'Bạn chưa chọn bài viết để xóa!';
          var icon = 'warning';
          var color = '#344767';
        //   var color = '#344767';
          NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    var data = $(oForm).serialize();
    var url = this.urlPath + '/delete';
    Swal.fire({
        title: 'Bạn có chắc chắn xóa vĩnh viễn bài viết này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34bd57',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận'
      }).then((result) => {
        if(result.isConfirmed == true){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    _token: $('#_token').val(),
                    listitem: listitem,
                },
                success: function (arrResult) {
                    if (arrResult['success'] == true) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                position: 'top-start',
                                icon: 'success',
                                title: 'Xóa thành công',
                                showConfirmButton: false,
                                timer: 3000
                              })
                              myClass.loadList(oForm);
                          }
                    } else {
                        if (result.isConfirmed) {
                            Swal.fire({
                                position: 'top-start',
                                icon: 'error',
                                title: 'Quá trình xóa đã xảy ra lỗi',
                                showConfirmButton: false,
                                timer: 3000
                              })
                          }
                    }
                }
            });
        }
      })
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Product.prototype.infoBlog = function (id) {
    var url = this.urlPath + '/infor';
    var myClass = this;
    var data = 'id=' + id;
    var loadding = NclLib.successLoadding();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#editPassmodal').html(arrResult);
            $('#editPassmodal').modal('show');
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Hàm hiển thị modal edit
 *
 * @param oForm (tên form)
 *
 * @return void
 */
JS_Product.prototype.changePass = function (oForm) {
    var url = this.urlPath + '/changePass';
    var myClass = this;
    var data = 'id=' + $("#id").val();
    data += '&email=' +$("#email").val();
    var loadding = NclLib.successLoadding();
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        success: function (arrResult) {
            $('#editPassmodal').html(arrResult);
            $('#editPassmodal').modal('show');
            myClass.loadevent(oForm);

        }
    });
}
/**
 * Cập nhật mật khẩu
 *
 * @param oFormCreate (tên form)
 *
 * @return void
 */
JS_Product.prototype.updatePass = function (oFormCreate) {
    var url = this.urlPath + '/updatePass';
    var myClass = this;
    var data = $(oFormCreate).serialize();
    if ($("#password_old").val() == '') {
        var nameMessage = 'Mật khẩu cũ không được để trống!';
        var icon = 'warning';
        var color = '#344767';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password_new").val() == '') {
        var nameMessage = 'Mật khẩu mới không được để trống!';
        var icon = 'warning';
        var color = '#344767';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    if ($("#password_retype_change").val() == '') {
        var nameMessage = 'Chưa nhập lại mật khẩu mới!';
        var icon = 'warning';
        var color = '#344767';
        NclLib.alerMesage(nameMessage,icon,color);
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success'] == true) {
                  var nameMessage = arrResult['message'];
                  var icon = 'success';
                  var color = '#344767';
                  NclLib.alerMesage(nameMessage,icon,color);
                  $('#editPassmodal').modal('hide');
                  myClass.loadList(oFormCreate);
            } else {
                  var nameMessage = arrResult['message'];
                  var icon = 'warning';
                  var color = '#344767';
                  NclLib.alerMesage(nameMessage,icon,color);
            }
        }
    });
    
}
/**
 * Check
 */
JS_Product.prototype.checkValidate = function(){
    if($("#code_category").val() == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Thể loại không được để trống!');
        $("#code_category").focus();
        return false;
    }
    if($.trim($("#name").val() || '') == ''){
        NclLib.alertMessageBackend('warning', 'Cảnh báo', 'Tên không được để trống!');
        $("#name").focus();
        return false;
    }
    return true;
}

JS_Product.prototype.wordsList = function (productId, searchBy, searchText) {
    var modal = $('#wordsmodal');
    $.ajax({
        url: this.urlPath + '/words',
        type: 'GET',
        data: {
            product_id: productId,
            search_by: searchBy || 'name_han_viet',
            search: searchText || ''
        },
        success: function (html) {
            modal.html(html).modal('show');
        },
        error: function () {
            Swal.fire('Lỗi', 'Không tải được danh sách chữ của sản phẩm.', 'error');
        }
    });
};

JS_Product.prototype.searchWords = function () {
    var form = $('#wordsmodal .word-filter-form');
    this.wordsList(
        form.data('product-id'),
        $('#word_search_by').val(),
        $('#word_search_text').val()
    );
};

JS_Product.prototype.wordForm = function (productId, id) {
    var modal = $('#wordsmodal');
    $.ajax({
        url: this.urlPath + '/words/form',
        type: 'POST',
        data: {
            _token: $('#_token').val(),
            product_id: productId,
            id: id || '',
            return_to_all: $('#all-product-words').length > 0 ? 1 : 0
        },
        success: function (html) {
            modal.html(html);
            modal.modal('show');
        },
        error: function () {
            Swal.fire('Lỗi', 'Không tải được biểu mẫu chữ.', 'error');
        }
    });
};

JS_Product.prototype.saveWord = function () {
    var form = document.getElementById('frmProductWord');
    if (!form.reportValidity()) return;

    if (CKEDITOR.instances.word_decision) {
        CKEDITOR.instances.word_decision.updateElement();
    }
    var formData = new FormData(form);
    var productId = formData.get('product_id');
    var button = $('#frmProductWord button[onclick="JS_Product.saveWord()"]');
    button.prop('disabled', true);

    $.ajax({
        url: this.urlPath + '/words/save',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function () {
            if ($('#all-product-words').length) {
                window.location.reload();
            } else {
                JS_Product.wordsList(productId);
            }
        },
        error: function (xhr) {
            button.prop('disabled', false);
            var message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Không lưu được chữ. Vui lòng kiểm tra dữ liệu và ảnh đã chọn.';
            Swal.fire('Lỗi', message, 'error');
        }
    });
};

JS_Product.prototype.deleteWord = function (productId, id) {
    var myClass = this;
    Swal.fire({
        title: 'Xóa chữ này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#d33'
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: myClass.urlPath + '/words/delete',
            type: 'POST',
            dataType: 'json',
            data: { _token: $('#_token').val(), product_id: productId, id: id },
            success: function () {
                if ($('#all-product-words').length) {
                    window.location.reload();
                } else {
                    myClass.wordsList(productId);
                }
            },
            error: function () {
                Swal.fire('Lỗi', 'Không xóa được chữ.', 'error');
            }
        });
    });
};

JS_Product.prototype.closeWords = function () {
    $('#wordsmodal').modal('hide');
};
