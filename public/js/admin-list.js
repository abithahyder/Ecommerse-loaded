
$( document ).on( 'click', '.add_admin',  function(){
    $( '.add_new_admin_form' ).stop().slideToggle();
} );

$('select[name="district[]"]').select2({
    placeholder: "Select district",
    width:'100%',
});

var table;
$(document).ready(function() {
    
    table = $('#admin_list_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: base_url+"/admin/getdata",
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
           { data: 'name' },
           { data: 'email' },
           { data: 'ug_name',name: 'user_groups.ug_name' },
           { data: 'status' ,orderable: false, searchable: false},
           { data: 'action', name: 'action', orderable: false, searchable: false}
       ]
    });
});


$('#admin_add_form').validate({
    focusInvalid: true,
    rules: {
        name: 'required',
        email: 'required',
        group: 'required',
        password: {
            minlength: 8
        },
        'password_confirmation': {
            minlength: 8,
            equalTo: "#password"
        },
        status: 'required',
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
        text: "Are you sure you want to proceed ?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
        
    }).then(function(result) { 
        if (result.value) {
            $.ajax({
                url : base_url+'/admin/delete',
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
                        title: 'User  deleted successfully',
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

$(document).on('change', '.chkbox_active', function () {
    if(!$(this).val()){
        swal.fire({
            title: 'Something went wrong, try again later',
            type: 'error',
            animation: false,
            customClass: 'animated tada'
        })
        return false;
    }
    var status = 'inactive'
    if ($(this).is(":checked")) {
        status = 'active'
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
    $.ajax({
        url : base_url+'/admin/status-change',
        type : 'POST',
        data : {id:$(this).val(), status : status},
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
                title: 'User status changed successfully',
                showConfirmButton: false,
                timer: 2000
            });
            table.ajax.reload();
        },
        complete: function() {
            swal.hideLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal.hideLoading();
            swal.fire("!Opps ", "Something went wrong, try again later", "error");
            table.ajax.reload();
        }
    });

});