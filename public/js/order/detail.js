$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change','#change-status', function () {
    var status = $(this).val();
    var orderId = $('#orderId').val();

    if( status ){
        $.ajax({
            url : base_url+'/order/status-update',
            type : 'POST',
            data : { status : status , order_id : orderId},
            dataType:'json',
            beforeSend: function() {
                swal.fire({
                    title: 'Please Wait..!',
                    text: 'Is working..',
                    onOpen: function() {
                        swal.showLoading()
                    }
                })
            },
            success : function(data) { 
                swal.fire({
                    position: 'top-right',
                    type: 'success',
                    title: 'Status update successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            complete: function() {
                swal.hideLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.hideLoading();
                swal.fire("!Opps ", "Something went wrong, try again later", "error");
            }
        });
    }else{
        swal.fire("!Opps ", "Something went wrong, try again later", "error");
    }
});

$(document).on('change','#multi-option', function () {
    if( $(this).prop("checked") == true ){
        $('.variants-part').removeClass('d-none');
    }else{
        $('.variants-part').addClass('d-none');
    }
});

$(document).on('change','#category', function () {
    var id = $(this).val();
    var cat = $('#subCategory').data('category');
    $('#subCategory option:not(:first)').remove();
    $.ajax({
        url : base_url+'/product/sub-category',
        type : 'POST',
        data : {id:id },
        dataType:'json',
        beforeSend: function() {
            
        },
        success : function(res) { 
            if( res.status == true ){
                $.each(res.data, function(key, value) {
                    var selected = false;
                    if( cat == value.cat_id ){
                        var selected = true;
                    }
                    $('#subCategory').append($("<option></option>").attr({value:value.cat_id,selected:selected}).text(value.cat_title)); 
               });
            }else{
                // swal.fire("!Opps ", , "error"); 
            }
            swal.hideLoading();
        },
        complete: function() {
            swal.hideLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal.hideLoading();
            swal.fire("!Opps ", "Something went wrong, try again later", "error");
        }
    });
});

$(document).on('click','.image-delete', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.preventDefault();
    var tag_id = $(this).attr('data-id');
    var product_id = $(this).data('product');
    var thiss = $(this);
    
    if(tag_id == ''){
        swal.fire({
            title: 'Something went wrong, try again later',
            type: 'error',
            animation: false,
            customClass: 'animated tada'
        })
        return false;
    }
    swal.fire({
        title: 'Are you sure?',
        text: "Are you sure you want to proceed ? ",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
        
    }).then(function(result) { 
        if (result.value) {
            $.ajax({
                url : base_url+'/product/image-delete',
                type : 'POST',
                data : { id:tag_id, product_id:product_id },
                dataType:'json',
                beforeSend: function() {
                    swal.fire({
                        title: 'Please Wait..!',
                        text: 'Is working..',
                        onOpen: function() {
                            swal.showLoading()
                        }
                    })
                },
                success : function(data) { 
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Images  deleted successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    thiss.hide();
                    
                },
                complete: function() {
                    swal.hideLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.hideLoading();
                    swal.fire("!Opps ", "Something went wrong, try again later", "error");
                }
            });
        }
    });
});

$('#admin_add_form').validate({
    focusInvalid: true,
    rules: {
        p_name: 'required',
        p_cat_parent_id: 'required',
        p_cat_id: 'required',
        p_price: {
            required: true,
            digits: true
        },
        p_sale_price: {
            required: true,
            digits: true
        },
        p_status: 'required',
        p_desc: 'required',
        p_short_desc: 'required',
    },
    messages: {
      
    },
    highlight: function(element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function(element) {
        $(element).removeClass("is-invalid");
    },
    submitHandler: function(form) {
      form.submit();
    }
});