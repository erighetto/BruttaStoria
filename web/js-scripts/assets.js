$(function () {
    $("#dizionario .label").hover(
        function () {
            $(this).removeClass("label-default");
            $(this).addClass("label-primary");
        }, function () {
            $(this).removeClass("label-primary");
            $(this).addClass("label-default");
        }
    );
});