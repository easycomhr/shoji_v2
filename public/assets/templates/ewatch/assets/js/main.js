(function ($) {
    "use strict";
    jQuery(window).on('load', function () {
        $(".preloader").delay(1600).fadeOut("slow");
    });
    $(document).on("click", ".gs_scroll_up", function (e) {
        e.preventDefault();
        $("html, body").stop().animate({scrollTop: 0}, 1000);
    });
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 20) {
            $('header .stickyHeader').addClass("is-sticky");
        } else {
            $('header .stickyHeader').removeClass("is-sticky");
        }
    });
    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";
    $(window).on("load resize", function () {
        if (this.matchMedia("(min-width: 992px)").matches) {
            $dropdown.hover(function () {
                const $this = $(this);
                $this.addClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "true");
                $this.find($dropdownMenu).addClass(showClass);
            }, function () {
                const $this = $(this);
                $this.removeClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "false");
                $this.find($dropdownMenu).removeClass(showClass);
            });
        } else {
            $dropdown.off("mouseenter mouseleave");
        }
    });
    var submitIcon = $('.search-btn i');
    var inputBox = $('.searchbox-input');
    var searchBox = $('.searchbox');
    var isOpen = false;
    submitIcon.click(function () {
        if (isOpen == false) {
            searchBox.addClass('searchbox-open');
            submitIcon.attr('class', 'bx bx-x');
            inputBox.focus();
            isOpen = true;
        } else {
            searchBox.removeClass('searchbox-open');
            submitIcon.attr('class', 'bx bx-search-alt');
            inputBox.focusout();
            isOpen = false;
        }
    });
    submitIcon.mouseup(function () {
        return false;
    });
    searchBox.mouseup(function () {
        return false;
    });
    $(document).mouseup(function () {
        if (isOpen == true) {
            $('.searchbox-icon').css('display', 'block');
            submitIcon.click();
        }
    });
    $('.quantity').on('click', '.plus', function (e) {
        let $input = $(this).prev('input.qty');
        let val = parseInt($input.val());
        $input.val(val + 1).change();
    });
    $('.quantity').on('click', '.minus', function (e) {
        let $input = $(this).next('input.qty');
        var val = parseInt($input.val());
        if (val > 0) {
            $input.val(val - 1).change();
        }
    });

    function getVals() {
        let parent = this.parentNode;
        let slides = parent.getElementsByTagName("input");
        let slide1 = parseFloat(slides[0].value);
        let slide2 = parseFloat(slides[1].value);
        if (slide1 > slide2) {
            let tmp = slide2;
            slide2 = slide1;
            slide1 = tmp;
        }
        let displayElement = parent.getElementsByClassName("rangeValues")[0];
        displayElement.innerHTML = "$" + slide1 + " - $" + slide2;
    }

    AOS.init({duration: 1200,})
    $('body').on('mouseenter mouseleave', '.nav-item', function (e) {
        if ($(window).width() > 750) {
            var _d = $(e.target).closest('.nav-item');
            _d.addClass('show');
            setTimeout(function () {
                _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
            }, 1);
        }
    });

    function makeTimer() {
        var endTime = new Date("29 April 2021 9:56:00 GMT+01:00");
        endTime = (Date.parse(endTime) / 1000);
        var now = new Date();
        now = (Date.parse(now) / 1000);
        var timeLeft = endTime - now;
        var days = Math.floor(timeLeft / 86400);
        var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
        var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
        var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
        if (hours < "10") {
            hours = "0" + hours;
        }
        if (minutes < "10") {
            minutes = "0" + minutes;
        }
        if (seconds < "10") {
            seconds = "0" + seconds;
        }
        $("#days").html(days + "<span>Days</span>");
        $("#hours").html(hours + "<span>Hours</span>");
        $("#minutes").html(minutes + "<span>Minutes</span>");
        $("#seconds").html(seconds + "<span>Seconds</span>");
    }

    setInterval(
        function () {
            makeTimer();
        }, 1000);
    $(document).ready(function () {
        $('body.hero-anime').removeClass('hero-anime');
    });
    $(document).ready(function () {
        $('.minus').click(function () {
            var $input = $(this).parent().find('input');
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $input.val(count);
            $input.change();
            return false;
        });
        $('.plus').click(function () {
            var $input = $(this).parent().find('input');
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
        });
    });
    $('.product-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: false,
        lazyload: true,
        autoplayTimeout: 4000,
        responsiveClass: true,
        slideSpeed: 5000,
        pagination: true,
        paginationSpeed: 3000,
        singleItem: true,
        autoplaySpeed: 2000,
        navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-arrow-angle-pointing-to-right'></i>"],
        responsive: {0: {items: 1, nav: false}, 600: {items: 1, nav: false}, 1000: {items: 1, nav: true, loop: true}}
    });
    $('.testimonial-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: true,
        autoplay: false,
        lazyload: true,
        autoplayTimeout: 4000,
        responsiveClass: true,
        slideSpeed: 5000,
        pagination: true,
        paginationSpeed: 3000,
        singleItem: true,
        autoplaySpeed: 2000,
        responsive: {
            0: {items: 1, dots: true, nav: false},
            600: {items: 1, dots: true, nav: false},
            1000: {items: 1, nav: false, dots: true, loop: true}
        }
    });
    $('.client-inner-items').owlCarousel({
        loop: true,
        items: 3,
        margin: 15,
        merge: true,
        autoplayHoverPause: true,
        nav: false,
        dots: true,
        autoplay: true,
        lazyload: true,
        autoplayTimeout: 4000,
        responsiveClass: true,
        slideSpeed: 5000,
        pagination: true,
        paginationSpeed: 3000,
        autoplaySpeed: 2000,
        responsive: {
            0: {items: 1, dots: false, nav: false},
            580: {items: 1, dots: false, nav: false},
            768: {items: 2, dots: true, nav: false},
            1000: {items: 3, nav: false, dots: true, mergeFit: true}
        }
    });
    $('.releted-product-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: false,
        lazyload: true,
        autoplayTimeout: 4000,
        responsiveClass: true,
        slideSpeed: 5000,
        pagination: true,
        paginationSpeed: 3000,
        singleItem: true,
        autoplaySpeed: 2000,
        navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-arrow-angle-pointing-to-right'></i>"],
        responsive: {0: {items: 1, nav: false}, 600: {items: 2, nav: false}, 1000: {items: 4, nav: true, loop: true}}
    });
}(jQuery));
