
var table;
$(document).ready(function () {

    table = $('#kt_table_1').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: base_url + "/mail/template/list",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        columns: [
            { data: 'mt_name'},
            { data: 'mt_type' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [0, "desc"],
        autoWidth: true,
    });
});


$(document).on('click', '.delete-single', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.preventDefault();
    var tag_id = $(this).attr('data-id');

    if (tag_id == '') {
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

    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: base_url + '/mail/template/delete',
                type: 'POST',
                data: { id: tag_id },
                dataType: 'json',
                beforeSend: function () {
                    swal.fire({
                        title: 'Please Wait..!',
                        text: 'Is working..',
                        onOpen: function () {
                            swal.showLoading()
                        }
                    })
                },
                success: function (data) {
                    swal.fire({
                        position: 'top-right',
                        type: 'success',
                        title: 'Template deleted successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('.check-all').prop("checked", false).change();
                    table.ajax.reload();
                },
                complete: function () {
                    swal.hideLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal.hideLoading();
                    swal.fire("!Opps ", "Something went wrong, try again later", "error");
                }
            });
        }
    });
});