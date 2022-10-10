$('#add_form').validate({
    focusInvalid: true,
    rules: {
        mt_name: 'required',
        mt_type: 'required',
        mt_formate: 'required',
    },
    messages: {
      
    },
    invalidHandler: function(event, validator){

        $('html, body').animate({
             scrollTop: $(validator.errorList[0].element).offset().top - 150
        }, 1000);
    
        return false;
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

var editor1 = CKEDITOR.replace('template', {});

$('select[name="pm_city"]').change(function (e) {
    $('input[name="pm_city_string"]').val($(this).find('option:selected').text());
    e.preventDefault();
});
