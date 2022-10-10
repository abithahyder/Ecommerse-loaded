
$('.order-filter').change(function (e) { 
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
            url : base_url+"/order/get-data",
            type : 'POST',
            data : function(d){
                d.status = $('select[name="status"]').val();
                d.date = $('input[name="date"]').val();
            }
         },
       "columns":[
           { data: 'orm_id' },
           { data: 'number', name:'om_or_id_no' },
           { data: 'cname', name:'clients.c_name' },
           { data: 'om_date'},
           { data: 'om_grand_total' },
           { data: 'payment' },
           { data: 'om_status', orderable: false, searchable: false },
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
                url : base_url+'/order/delete',
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
