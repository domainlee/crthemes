;(function () {
    'use strict';
    var loading = function () {
        $(window).on("load", function () {
            var speed = 500;
            setTimeout(function () {
                loading2();
                animation();
            }, speed);
        });
    }

    var nav = function () {
        var button_nav = $('.toggle-menu');
        button_nav.click(function (e) {
            $('body').toggleClass('nav-open-js');
        });

        var toggle_search = $('.toggle-search');
        toggle_search.click(function (e) {
            $('body').toggleClass('search-open-js');
        });

        $("html").click(function(e) {
            if ($(e.target).closest('.search-mobile').length == 0)
                $('body').removeClass('search-open-js');
        });

        $('.nav__mobile > ul > li a').each(function(){
            var t = $(this);
            var checkElement = t.next();
            if(checkElement.is('ul')) {
                t.after('<span class="more">+</span>');
                t.next().click(function(e){
                    e.preventDefault();
                    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        checkElement.slideUp('normal');
                    }
                    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        checkElement.slideDown('normal');
                    }
                    $(this).toggleClass('active');
                });
            }

        });
    }

    var lazy = function () {
        $('.lazy').Lazy({
            effect: "fadeIn",
            effectTime: 500
        });
    };

    var owlCarousel = function() {
        // SINGLE PAGE - Gallery //
        $('.hero__js').owlCarousel({
            loop: true,
            margin: 0,
            dots: true,
            nav: true,
            lazyLoad: true,
            autoplay: true,
            items: 1,
            animateOut: 'fadeOut',
            autoplayHoverPause: true,
            navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        });

        $('.gallery__js').owlCarousel({
            loop: true,
            margin: 30,
            dots: false,
            nav: false,
            lazyLoad: true,
            autoplay: true,
            items: 1,
            autoplayHoverPause: true,
            navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        });

        $('.client__js').owlCarousel({
            loop: true,
            margin: 30,
            dots: false,
            nav: true,
            lazyLoad: true,
            autoplay: true,
            animateOut: 'fadeOut',
            items: 1,
            autoplayHoverPause: true,
            navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        });

        $('.single__gallery').owlCarousel({
            loop: true,
            margin: 30,
            dots: false,
            nav: true,
            lazyLoad: true,
            autoplay: true,
            animateOut: 'fadeOut',
            items: 1,
            autoplayHoverPause: true,
            navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        });

    };

    var masonry = function () {
        $('.grid-js').masonry({
            itemSelector: '.post-type-two__item',
            columnWidth: '.grid-size',
        });
    };

    var searchDesktop = function () {
        $('.head__button-search').click(function(){
            $('body').toggleClass('search-js-open');
        });
    };

    var sidebarScroll = function() {
        $('.sidebar-fixed').theiaStickySidebar({
            additionalMarginTop: 20
        });
    }

    var skill = function () {
        var skill_item = $('.my-resume__skill--item');
        skill_item.each(function (k, v) {
            var count = $(this).find('div');
            var span = count.find('span');
            var precent = count.attr('data-precent');
            span.each(function (kk, vv) {
                if(kk < precent) {
                    $(this).addClass('active');
                }
            });
        });
    }

    var sticky = function () {
        apply_stickies()
        window.addEventListener('scroll', function() {
            apply_stickies()
        })
        function apply_stickies() {
            var _$stickies = [].slice.call(document.querySelectorAll('.sticky-top'))
            _$stickies.forEach(function(_$sticky) {
                if (CSS.supports && CSS.supports('position', 'sticky')) {
                    apply_sticky_class(_$sticky)
                }
            })
        }
        function apply_sticky_class(_$sticky) {
            var currentOffset = _$sticky.getBoundingClientRect().top
            var stickyOffset = parseInt(getComputedStyle(_$sticky).top.replace('px', ''))
            var isStuck = currentOffset <= stickyOffset

            _$sticky.classList.toggle('is-sticky', isStuck)
        }
    }

    var animation = function () {
        let animContainer = document.querySelectorAll('.to-top');
        for (let element of animContainer) {
            if (isInViewport(element)) {
                setInterval(function(){
                    element.classList.add('is-on');
                }, 200);
            }
        }
        function isInViewport(el) {
            let inSight = el.getBoundingClientRect();
            let windowHeight = window.innerHeight;

            let topVisible = inSight.top > 0 && inSight.top < windowHeight;
            let bottomVisible = inSight.bottom < windowHeight && inSight.bottom > 0;
            let bothVisible = inSight.top < 0 && inSight.bottom > windowHeight;
            return topVisible || bottomVisible || bothVisible;
        }
    }

    var form = function () {
        $('.form-contact').submit(function(e) {
            var t = $(this);
            var name  = t.find('.contact__name').val();
            var email  = t.find('.contact__email').val();
            var phone  = t.find('.contact__phone').val();
            var address  = t.find('.contact__address').val();

            var title  = t.find('.contact__title').val();
            var service  = t.find('.contact__service').val();
            var content  = t.find('.contact__content').val();

            var url = $('.contact__redirect').val();

            var modal = $('#checkoutModal');

            var data = {
                'action': 'contact',
                'name': name,
                'email': email,
                'phone': phone,
                'address': address,
                'title': title,
                'service': service,
                'content': content,
            };

            e.preventDefault();
            if ( $(this).parsley().isValid() ) {
                $.post(ajaxurl, data, function (e) {
                    var obj = JSON.parse(e);
                    if(obj.code == 1) {
                        t.find('.contact__name').val('');
                        t.find('.contact__phone').val('');
                        alert('Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất. Cảm ơn.');
                        window.location.href = url
                    }
                });
            }
        });
    }

    var loading2 = function () {
        "use strict";
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ? true : false;
        var preloader = $("#preloader");
        if (!isMobile) {
            setTimeout(function () {
                preloader.addClass("preloaded");
            }, 800);
            setTimeout(function () {
                preloader.remove();
            }, 2000);
        } else {
            preloader.remove();
        }
    }

    var scroll = function () {
        var timer;
        $(window).scroll(function(event) {
            if(timer) {
                window.clearTimeout(timer);
            }
            timer = window.setTimeout(function() {
                var scrollTop = $('html').scrollTop();
                var head = $('.head')
                if(scrollTop > 100) {
                    var top = -100 + (scrollTop - 100);
                    if(top <= 0) {
                        head.css('top', top);
                    }
                    $('.head').addClass('head__fix');
                } else if(scrollTop < 100) {
                    head.css('top', 0);
                    $('.head').removeClass('head__fix');
                }
            }, 10);
        });
    }

    $(document).ready(function() {
        loading();
        loading2();
        scroll();
        nav();
        lazy();
        owlCarousel();
        // masonry();
        searchDesktop();
        sidebarScroll();
        skill();
        sticky();
        form();
        $(document).on( 'scroll', function(){
            animation();
        });
    });
}());