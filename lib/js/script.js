$(document).ready(function(){
/*
	Códigos de invocação dos plugins.
*/
	  $('.sidenav').sidenav();

	  $('.modal').modal();

	  $('.modal-laranja').modal();

      $('.patroci-carosel').owlCarousel({
            loop:true,
            margin:10,
            nav:false,
            autoplay:true,
    autoplayTimeout:3000,
            responsive:{
                0:{
                    items:2
                },
                600:{
                    items:2
                },
                1000:{
                    items:3
                }
            }
        });

});





var nav = $('.nav-total');
    
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            nav.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
        }
    });

















