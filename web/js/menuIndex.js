(function($) {
    "use strict"; // Start of use strict

    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: (target.offset().top - 54)
                }, 1000, "easeInOutExpo");
                return false;
            }
        }
    });

    // Closes responsive menu when a scroll trigger link is clicked
    $('.js-scroll-trigger').click(function() {
        $('.navbar-collapse').collapse('hide');
    });

    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
        target: '#mainNav',
        offset: 54
    });

    $("#mainNav").removeClass("navbar-shrink");



    // $("#mainNav").click(function() {
    //     $("#menu.container-fluid").on("click").attr("style", "background-color: white;");
    // });
    //
    // $("#mainNav").click(function() {
    //     $("#menu.container-fluid").off("click").attr("style", "background-color: yellow;");
    // });


    // Collapse the navbar when page is scrolled
    $(window).scroll(function() {
        if ($("#mainNav").offset().top > 100) {
            $("#mainNav").addClass("navbar-shrink");
        } else {
            $("#mainNav").removeClass("navbar-shrink");
        }
    });

    if (window.matchMedia("(min-width: 420px)").matches) {
        $("#menu.container-fluid").attr("style", "height: auto !important");
    }
    else {
        $("#menu.container-fluid").attr("style", "height: 200px !important");
    }

})(jQuery); // End of use strict

$(document).ready(function(){
    $('.navbar-toggler').click(function() {
        if($("#menu").css('background-color') == 'rgb(255, 255, 255)') {
            $("#menu").css('background-color', 'transparent');
        }
        else{
            $("#menu").css('background-color', 'white');
        }
    });
});