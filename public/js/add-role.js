Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

$(document).ready(function () {
    var editId = $("input[name='edit_id']");
    if (editId.val()) {
        var oldPermission = $("input[name='ug_permissions']");
        var allPermisionCheckBox = $("input[class='permissionCheckbox']");
        var editPermissions = oldPermission.val().split(",");
        $.each(allPermisionCheckBox, function () {
            if (editPermissions.includes($(this).attr("data-val"))) {
                $(this).prop("checked", true);
            }
        });

    }

    if ($('.permissionCheckbox:checked').length == $('.permissionCheckbox').length) {
        $('#checkAll').prop("checked", true);
    }

    // datatable initialize
    table = $('#kt_table_1').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "drawCallback": function( settings ) {
            // if ($('.permissionCheckbox:checked').length != $('.permissionCheckbox').length) {
            //     $('#checkAll').prop("checked", false);
            // }else{
            //     $('#checkAll').prop("checked", true );
            // }
        }
    });

    // check all permissions
    $(document).on("click", "#checkAll", function (e) {
        if( $.fn.DataTable.isDataTable( '#kt_table_1' ) )
        {
            var rows = table.rows().nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            var allpermisssions = [];
            $.each(rows, function () {
                var checkbox = $(this).find('input[type="checkbox"]', rows);
                if (checkbox.prop("checked") == true) {
                    allpermisssions.push(checkbox.attr("data-val"));
                }
            });
            var oldPermissions = $('input[name="ug_permissions"]').val(allpermisssions.join(','));
        }else{
            var rows = $(".permissionCheckbox");
            rows.prop('checked', this.checked);
            var allpermisssions = [];
            $.each(rows, function () {
                var checkbox = $(this);
                if (checkbox.prop("checked") == true) {
                    allpermisssions.push(checkbox.attr("data-val"));
                }
            });
            var oldPermissions = $('input[name="ug_permissions"]').val(allpermisssions.join(','));
        }
        
    });

    // one permission check/uncheck
    $(document).on("change", "input[class='permissionCheckbox']", function (e) {
        var this_val = $(this).attr("data-val");
        var oldPermissions = $('input[name="ug_permissions"]').val();
        var check = $(this).prop("checked");
        if (check) {
            if (oldPermissions) {
                $('input[name="ug_permissions"]').val(oldPermissions + ',' + this_val);
            } else {
                $('input[name="ug_permissions"]').val(this_val);
            }
        } else {
            if (oldPermissions) {
                var newArr = [];
                newArr = oldPermissions.split(',');
                if (newArr.includes(this_val)) {
                    newArr.remove(this_val);
                    $('input[name="ug_permissions"]').val(newArr.join(","));
                }
            }
        }
        if ($('.permissionCheckbox:checked').length != $('.permissionCheckbox').length) {
            $('#checkAll').prop("checked", false);
        }else{
            $('#checkAll').prop("checked", true );
        }
    });

    // check form validation
    $('.validation_form').validate({
        focusInvalid: true,
        rules: {
            // q_question: 'required',
            ug_name: 'required',
        },
        messages: {

        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // $('.validation_form input[type=checkbox]').each(function () {
            //     if($(this).is(":not(:checked)")){
            //         $(this).prop( "checked", true ).val(0);
            //     }
            // });
            // $("select[name='kt_table_1_length']").val("-1");
            if (!$('input[name="ug_permissions"]').val()) {
                alert("please select any permission")
            } else {
                form.submit();
            }
        }
    });


});
