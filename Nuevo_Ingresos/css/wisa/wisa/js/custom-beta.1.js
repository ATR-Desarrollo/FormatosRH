$(document).ready(function() {
 
    $(function() {
        $(".preloader").fadeOut();
    });
  
    $(function() {
        var url = window.location;
        var element = $('ul.side-menu a').filter(function() {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }).addClass('active').parent().parent().addClass('in').parent();
        if (element.is('li')) {
            element.addClass('active');
        }
    });
});

