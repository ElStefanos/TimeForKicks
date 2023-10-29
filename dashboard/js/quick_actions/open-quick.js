var count_quick = 0;
$("#quick-actions").addClass('fa-solid fa-gear');
$("#quick-actions").click(function(){
    $('.charts canvas').toggleClass('hidden');
    $(".quick-actions-container").toggleClass('hidden');
    count_quick++;
    if(count_quick % 2 == 0) {
        $("#quick-actions").removeClass("fa-solid fa-gears");
        $("#quick-actions").addClass('fa-solid fa-gear');
    } else {
        $("#quick-actions").removeClass('fa-solid fa-gear');
        $("#quick-actions").addClass("fa-solid fa-gears");
    }
});