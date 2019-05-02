$(document).ready(function(){
/*
	Códigos de invocação dos plugins.
*/
	  $('.sidenav').sidenav();

	  $('.modal').modal();

	  $('.modal-laranja').modal();
});





var nav = $('.nav-total');
    
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            nav.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
        }
    });

















