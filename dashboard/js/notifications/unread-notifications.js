



jQuery('document').ready(function ($) {
    
    setInterval(function (open) {
        
        var open = 0;
        const base = window.location.origin;

        $("#notification").click(function () {
            
            if (open === 0) {
                open = 1;
            } else {
                open = 0;
            }
        });

        if (open === 0) {
            $.ajax({
                type: "POST",
                url: base + '/dashboard/jobs/notifications.php',
                data: { name: 'read' },
                success: function (data) {
                    $('.notifications-list').html(data);
                },
                error: function (xhr, status, error) {
                    console.error("failed");
                }
            });

            var unread = $("div[id='unread']").length;

            unread--;

            if (unread === 0) {
                $('#notifications-indicator').css('display', 'none');
            } else {
                $('#notifications-indicator').css('display', 'block');
                $('#notifications-indicator').html(unread);
            }
            $('#total').html(unread);

        }
    }, 1000);
});

