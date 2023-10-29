function pauseSite(id, action) {
    const base = window.location.origin;
    const div = $(".ajax-response");
    const paragraph = $(".ajax-response p");
    const icon = $(".ajax-response i");

    function maskPageOn(color) {
        var div = $('#maskPageDiv');
        if (div.length === 0) {
            $(document.body).append('<div id="maskPageDiv" style="position:fixed;width:100%;height:100%;left:0;top:0;display:none;z-index: 3;"></div>'); // create it
            div = $('#maskPageDiv');
        }
        if (div.length !== 0) {
            div[0].style.zIndex = 3;
            div[0].style.backgroundColor = color;
            div[0].style.display = 'inline';
        }
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
    }

    function maskPageOff() {
        var div = $('#maskPageDiv');
        if (div.length !== 0) {
            div[0].style.display = 'none';
            div[0].style.zIndex = 'auto';
        }
    }

    function hourglassOn() {
        if ($('style:contains("html.hourGlass")').length < 1) $('<style>').text('html.hourGlass, html.hourGlass * { cursor: wait !important; }').appendTo('head');
        $('html').addClass('hourGlass');
    }
    function hourglassOff() {
        $('html').removeClass('hourGlass');
    }

    maskPageOn('rgba(255,255,255, 0.2)');
    hourglassOn();



    $.ajax({
        type: "POST",
        url: base + '/dashboard/jobs/pauseSite.php',
        data: { site: id, action: action },
        success: function (data) {
            console.log('success');
            var data = data;
        },
        error: function (xhr, status, error) {
            console.log('fail');
            maskPageOff();
            hourglassOff();
        }
    }).done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
        console.log(data);
        if (data.success == true && !data.errors) {
            maskPageOff();
            hourglassOff();
            console.log('da');
            div.toggleClass('success');
            paragraph.html(data.message);
            icon.addClass('fa-regular fa-circle-check fa-shake');
            setTimeout(() => {
                div.toggleClass('success');
                icon.removeClass('fa-regular fa-circle-check fa-shake');
            }, 5000);
        }

        else if (data.errors && !data.success) {
            maskPageOff();
            hourglassOff();
            div.toggleClass('error');
            paragraph.html(data.message);
            icon.addClass('fa-sharp fa-solid fa-circle-xmark fa-beat');
            setTimeout(() => {
                div.toggleClass('error');
                icon.removeClass('fa-sharp fa-solid fa-circle-xmark fa-beat');
            }, 5000);
        }

        $('.sites-overview').load(location.href + " .container");

    });
}