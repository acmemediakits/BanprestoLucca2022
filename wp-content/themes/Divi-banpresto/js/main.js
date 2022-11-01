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


});