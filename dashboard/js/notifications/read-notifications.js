
var count = 0;

jQuery('document').ready(function ($) {

    $("#notification").addClass('fa-solid fa-envelope');
});


$("#notification").click(function () {

    const base = window.location.origin;

    count++;

    console.log(count);

    if(count % 2 == 0) {
        $.ajax({
            type: "POST",
            url: base + '/dashboard/jobs/readNotifications.php',
            data: { name: 'read' },
            success: function (data) {
                console.log('success');
            },
            error: function (xhr, status, error) {
                console.error('fail');
            }
        });
        $("#notification").removeClass("fa-solid fa-envelope-open");
        $("#notification").addClass('fa-solid fa-envelope');
    } else {
        $("#notification").removeClass('fa-solid fa-envelope');
        $("#notification").addClass("fa-solid fa-envelope-open");
    }
    $('.charts canvas').toggleClass('hidden');
});