jQuery('document').ready(function ($) {
    
    setInterval(function (open) {
        

        const base = window.location.origin;

            $.ajax({
                type: "POST",
                url: base + '/dashboard/jobs/notifications.php',
                data: { name: 'read' },
                success: function (data) {
                    $(' .notifications-stats').html(data);
                },
                error: function (xhr, status, error) {
                    console.error("Status" + status + error);
                }
            });
        
    }, 1000);
});

