$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
selectRefresh();
function selectRefresh() {
    
    $('.kt_select2_option ').select2({
        placeholder: "Select a option",
        tags: true
    });
    $('.kt_select2_value ').select2({
        placeholder: "Enter value",
        tags: true
    });
}

$(document).ready(function () {
    $('#category').trigger('change');
});

$(document).on('click','#add-more-option', function () {
    var UnID = uniqId();
    var html = '<div class="form-group row remove-option" >'+
                    '<div class="col-lg-4">'+
                        '<select name="option['+UnID+'][]" class="form-control kt-select2 kt_select2_option" >'+
                            '<option value=""></option>'+
                        '</select>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                        '<select name="values['+UnID+'][]" class="form-control kt-select2 kt_select2_value" multiple  >'+
                        '</select>'+
                    '</div>'+
                    '<div class="col-lg-4">'+
                        '<button type="button" class="delete-option btn-sm btn btn-label-danger btn-bold">'+
                            '<i class="la la-trash-o"></i>'+
                        '</button>'+
                    '</div>'+
                '</div>';
    $('.option-list').append(html);
    selectRefresh();
    addValidation();
});

function addValidation() {
    // $('select').each(function () {
    //     $(this).rules("add", {
    //         required: true
    //     });
    // });
    // $('input').each(function () {
    //     $(this).rules("add", {
    //         required: true
    //     });
    // });
}

function uniqId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}

$(document).on('click','.delete-option', function () {
    $(this).parents().closest('.remove-option').remove();
    $('.kt_select2_value').trigger('change');
    addValidation();
});

$(document).on('change','.kt_select2_value', function () {
    types = [];
    options = [];

    $('select.kt_select2_value').each(function() {
        types.push($(this).val());
    });

    $('select.kt_select2_option').each(function() {
        options.push($(this).val());
    });

    if( types ){
        $.ajax({
            url : base_url+'/product/combinations-varient',
            type : 'POST',
            data : { array:types },
            dataType:'json',
            beforeSend: function() {
            },
            success : function(data) { 
                if ( data.status == true ) {
                    var html = '';
                    $.each(data.data, function (indexInArray, valueOfElement) { 
                        // $.each(data.data, function (indexInArray, valueOfElement) {
                        //     console.log(valueOfElement);
                        // });
                        html += '<div class="form-group row">'+
                                    '<div class="col-lg-3 col-form-label-lg">'+
                                        '<label class="ml-5"> '+ valueOfElement.toString()  +' </label>'+
                                    '</div>'+
                                    '<div class="col-lg-3">'+
                                        '<label > Price </label>'+
                                        '<input name="preview[price][]" type="number" class="form-control" placeholder="Enter price" >'+
                                    '</div>'+
                                    '<div class="col-lg-3">'+
                                        '<label > Qty </label>'+
                                        '<input name="preview[qty][]" type="number" class="form-control" placeholder="Enter qty" >'+
                                    '</div>'+
                                    '<div class="col-lg-3">'+
                                        '<label > SKU </label>'+
                                        '<input name="preview[sku][]" type="text" class="form-control" placeholder="Enter sku" >'+
                                    '</div>'+
                                '</div>';
                    });
                    $('.preview-list').html(html);
                    addValidation();
                }else{
                    $('.preview-list').html('');
                }
            },
            complete: function() {
                // swal.hideLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('.preview-list').html('');
                // swal.hideLoading();
                // swal.fire("!Opps ", "Something went wrong, try again later", "error");
            }
        });
    }else{
        $('.preview-list').html('');
    }
});

$(document).on('change','#multi-option', function () {
    if( $(this).prop("checked") == true ){
        $('.variants-part').removeClass('d-none');
    }else{
        $('.variants-part').addClass('d-none');
    }
});

$(document).on('click','.more-option', function () {
    $('.variants-part').toggleClass('d-none');
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
                    if( cat == value.id ){
                        var selected = true;
                    }
                    $('#subCategory').append($("<option></option>").attr({value:value.id,selected:selected}).text(value.Product_category_name)); 
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

$(document).on('click','.delete-sku', function () {
    event.preventDefault();
    var tag_id = $(this).attr('data-id');
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
                url: base_url +'/product/delete-variant',
                type : 'POST',
                data : { id:tag_id},
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
                        title: 'product variant deleted successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    thiss.parents().closest('.remove-option').remove();
                    
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

$(document).on('click','.update-sku', function () {
    event.preventDefault();
    var tag_id = $(this).attr('data-id');
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
    $.ajax({
        url : base_url+'/product/update-sku',
        type : 'POST',
        data: { 
            price: $('input[name="price' + tag_id + '"]').val(), 
            qty: $('input[name="qty' + tag_id + '"]').val(), 
            sku: $('input[name="sku' + tag_id + '"]').val(),
            edit_id: tag_id  
        },
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
                title: 'Update successfully',
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

//select-box
var show = true;
  
function showCheckboxes() {
    var checkboxes = 
        document.getElementById("checkBoxes");

    if (show) {
        checkboxes.style.display = "block";
        show = false;
    } else {
        checkboxes.style.display = "none";
        show = true;
    }
}


$("#checkpinAll").click(function() {
    $("input[name='pinselect[]']").prop("checked", $(this).prop("checked"));

  });

  $('#selpinval').click(function(){
    items = new Array();
    $('input[name="pinselect[]"]:checked').each(function(){
        items.push($(this).val());
          });
   $('#pinvalues').val(items);
    
  });

  
