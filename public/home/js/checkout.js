$(document).ready(function() {
    $("input[type='radio']").click(function() {
        if($('#cash').is(":checked")) {
            $("#carddetail").hide();
        }else{
            $("#carddetail").show();
        }
       
          });
          
});

