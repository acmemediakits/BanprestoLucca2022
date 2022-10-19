if ('ontouchstart' in document.documentElement) {
    document.addEventListener('touchstart', ontouchstart, {passive: true});
}

window.addEventListener('DOMContentLoaded', ()  => {
    setTimeout(function(){
        const menu = document.getElementById('mobile_menu1');
        if (!!menu){
            const container = menu.querySelector('.logo-submenu a');
            const close  = window.getComputedStyle(container, '::before');
            if (!!container) {
                container.onclick = (e) =>{
                    e.preventDefault();
                }
            }
            if (!!close) {
                close.onclick = (e) =>{
                    e.preventDefault();
                }
            }
        }
    }, 600)

});

jQuery(function ($) {
    if (!!$('.loghi')) {
        $('.loghi').slick({
            slidesToShow: 9,
            slidesToScroll: 1,
            slidesPerRow: 1,
            autoplaySpeed: 3000,
            autoplay: true,
            dots: false,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1440,
                    settings: {
                        slidesToShow: 8,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                        infinite: true
                    }
                },
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 7,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                        infinite: true
                    }
                },
                {
                    breakpoint: 980,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                        infinite: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                        infinite: true
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                        infinite: true
                    }
                },

            ]
        });
    }

});