$(document).ready(function(){
    $("#sendMailForm").on("submit", function(){
        $("#sendMailBtn").prop("disabled", true).attr("title","Please wait mail in process");
    });
});