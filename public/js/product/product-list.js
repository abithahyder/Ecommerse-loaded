
$('.category-filter').change(function (e) { 
    e.preventDefault();
    table.ajax.reload();
});

$(document).on('click','.filter-show', function () {
    $( '.filter-body' ).stop().slideToggle();
});

var table;
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    table = $('#admin_list_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: { 
            url : base_url+"/product/get-data",
            type : 'POST',
            data : function(d){
                d.category = $('select[name="category"]').val();
                d.sub_category = $('select[name="sub_category"]').val();
            }
         },
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        buttons: [
                {
                    extend: 'excelHtml5',
                    action: function (e, dt, node, config) {
                        window.location = base_url + "/admin/export?export=xlsx";
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'csvHtml5',
                    action: function (e, dt, node, config) {
                        window.location = base_url + "/admin/export?export=csv";
                    },
                },
                {   
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1]
                    }
                }
        ],
       "columns":[
           { data: 'p_id' },
           { data: 'p_name' },
           { data: 'ptitle', name:'parent.cat_title' },
           { data: 'ctitle', name:'child.cat_title'},
           { data: 'p_price' },
           { data: 'p_sale_price' },
           { data: 'image' ,orderable: false, searchable: false},
           { data: 'p_status' },
           { data: 'action', name: 'action', orderable: false, searchable: false},
       ],
       "order": [[ 0, "desc" ]]
    });
});



$(document).on('click','.delete-single', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.preventDefault();
    var tag_id = $(this).attr('data-id');
    
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
                url : base_url+'/product/delete',
                type : 'POST',
                data : {id:tag_id },
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
                        title: 'Product  deleted successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('.check-all').prop("checked", false).change();    
                    table.ajax.reload();
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
