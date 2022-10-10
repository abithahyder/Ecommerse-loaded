var editor1 = CKEDITOR.replace('content', {
    readOnly: false,
});

editor1.on('change', function () {
    $('#fromate_hidden_id').val(editor1.getData());
    $('#previewContent').html(editor1.getData());
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#mailing_type').on('change', function () {
    var value = $(this).val();
    
    $('.previewMailingType').text($("#mailing_type option:selected").text());

    $('#template').empty();
    $.ajax({
        url: base_url + '/mail/template-list',
        type: 'POST',
        data: { type: value },
        dataType: 'json',
        beforeSend: function () {},
        success: function (data) {
            if (data.status == true) {
                $.each(data.data, function (indexInArray, valueOfElement) {
                    $('#template').append('<option value="' + this.mt_id + '">' + this.mt_name + '</option>');
                });
                $('#template').trigger('change');
            }else{
                $('#template').empty();
            }
        },
        complete: function () {},
        error: function (jqXHR, textStatus, errorThrown) {
            swal.fire("!Opps ", "Something went wrong, try again later", "error");
        }
    });

});

$('#template').on('change', function () {
    var value = $(this).val();

    $('.previewTemplate').text($("#template option:selected").text());

    $.ajax({
        url: base_url + '/mail/get-template',
        type: 'POST',
        data: { id: value },
        dataType: 'json',
        beforeSend: function () {},
        success: function (data) {
            if (data.status == true) {
                editor1.setData(data.data);
                $('#previewContent').html(data.data);
                $('#fromate_hidden_id').val(data.data);
            }else{
                editor1.setData('');
            }
        },
        complete: function () {},
        error: function (jqXHR, textStatus, errorThrown) {
            swal.fire("!Opps ", "Something went wrong, try again later", "error");
        }
    });

});


// Class definition
var KTWizard3 = function () {
    // Base elements
    var wizardEl;
    var formEl;
    var validator;
    var wizard;

    // Private functions
    var initWizard = function () {
        // Initialize form wizard
        wizard = new KTWizard('kt_wizard_v3', {
            startStep: 1,
            
        });

        // Validation before going to next page
        wizard.on('beforeNext', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop();  // don't go to the next step
            }else{
                
            }
            
        })

        // Change event
        wizard.on('change', function (wizard) {
            KTUtil.scrollTop();
        });
    }

    var initValidation = function () {
        validator = formEl.validate({
            // Validate only visible fields
            ignore: ":hidden",

            // Validation rules
            rules: {
                //= Step 1
                mailing_type: {
                    required: true
                },
                template: {
                    required: true
                },

                //= Step 2
                subject: {
                    required: true
                },
                reply_mail: {
                    required: false
                },
                reply_name: {
                    required: false
                },
                mt_formate: {
                    required: false
                },
                //= Step 3
                delivery: {
                    required: false
                },
                packaging: {
                    required: false
                },
                preferreddelivery: {
                    required: false
                },

                //= Step 4
                locaddress1: {
                    required: false
                },
                locpostcode: {
                    required: false
                },
                loccity: {
                    required: false
                },
                locstate: {
                    required: false
                },
                loccountry: {
                    required: false
                },
            },

            // Display error  
            invalidHandler: function (event, validator) {
                KTUtil.scrollTop();

                swal.fire({
                    "title": "",
                    "text": "There are some errors in your submission. Please correct them.",
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary"
                });
            },

            // Submit valid form
            submitHandler: function (form) {
                
            }
        });
    }

    var initSubmit = function () {
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');
        btn.on('click', function (e) {
            e.preventDefault();

            if (validator.form()) {
                // See: src\js\framework\base\app.js
                KTApp.progress(btn);
                //KTApp.block(formEl);

                // See: http://malsup.com/jquery/form/#ajaxSubmit
                formEl.ajaxSubmit({
                    success: function (data) {
                        KTApp.unprogress(btn);
                        //KTApp.unblock(formEl);
                        if (data.status == true) {
                            swal.fire({
                                "title": "",
                                "text": "The application has been successfully submitted!",
                                "type": "success",
                                "confirmButtonClass": "btn btn-secondary"
                            });
                            setTimeout(() => {
                                window.location = base_url + '/mail/schedule';
                            }, 2000);
                        }else{
                            swal.fire({
                                "title": "",
                                "text": "Something went wrong, Please try again later",
                                "type": "error",
                                "confirmButtonClass": "btn btn-secondary"
                            });
                        }
                    }
                });
            }
        });
    }

    return {
        // public functions
        init: function () {
            wizardEl = KTUtil.get('kt_wizard_v3');
            formEl = $('#kt_form');
            initWizard();
            initValidation();
            initSubmit();
        }
    };
}();

jQuery(document).ready(function () {
    KTWizard3.init();
});

$('#park-list').select2({
    placeholder: "Select a park",
    width: '100%',
});

$('#park-member-list').select2({
    placeholder: "Select a member",
    width: '100%',
    closeOnSelect: false
});

$('#park-list').on('change', function () {
    var value = $(this).val();
    $('#park-member-list').empty();
    $.ajax({
        url: base_url + '/mail/member-list',
        type: 'POST',
        data: { park: value },
        dataType: 'json',
        beforeSend: function () {
            $('#park-member-list').attr('disabled', 'disabled');
        },
        success: function (data) {
            // swal.hideLoading();
            if (data.status == true) {
                $.each(data.data, function (indexInArray, valueOfElement) {
                    $('#park-member-list').append('<option value="' + this.me_id + '">' + this.me_first_name + ' ' + this.me_last_name + '</option>');
                });
            } else {
                $('#park-member-list').empty();
            }
            $('#park-member-list').focus();
            $('#park-member-list').removeAttr('disabled');
        },
        complete: function () {
            // swal.hideLoading();
            $('#park-member-list').removeAttr('disabled');
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // swal.hideLoading();
            $('#park-member-list').removeAttr('disabled');
            swal.fire("!Opps ", "Something went wrong, try again later", "error");
        }
    });
});

$('input[name="subject"]').change(function () {
    $('.previewSubject').text( $(this).val() );
})
$('input[name="reply_mail"]').change(function () {
    $('.previewReplyEmail').text( $(this).val() );
})
$('input[name="reply_name"]').change(function () {
    $('.previewReplyName').text( $(this).val() );
})

document.getElementById('files').addEventListener('change', function (e) {
    var list = document.getElementById('filesList');
    var preview = document.getElementById('previewFilesList');
    list.innerHTML = '';
    preview.innerHTML = '';
    for (var i = 0; i < this.files.length; i++) {
        list.innerHTML += (i + 1) + '. ' + this.files[i].name + '<br>';
        preview.innerHTML += (i + 1) + '. ' + this.files[i].name + '<br>';
    }
    if (list.innerHTML == '') list.style.display = 'none';
    else list.style.display = 'block';
    if (preview.innerHTML == '') preview.style.display = 'none';
    else preview.style.display = 'block';
})

$('#single-mail').select2({
    placeholder: "Add mail",
    width: '100%',
    tags: true
});