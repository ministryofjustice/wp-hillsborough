/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
//(function() {
//    var container, button, menu, secondary;
//
//    container = document.getElementById('site-navigation');
//    if (!container)
//        return;
//
//    button = container.getElementsByTagName('h1')[0];
//    if ('undefined' === typeof button)
//        return;
//
//    secondary = document.getElementById('secondary');
//    menu = secondary.getElementsByTagName('ul')[0];
//
//    // Hide menu toggle button if menu is empty and return early.
//    if ('undefined' === typeof menu) {
//        button.style.display = 'none';
//        return;
//    }
//
//    if (-1 === menu.className.indexOf('nav-menu'))
//        menu.className += ' nav-menu';
//
//    button.onclick = function() {
//        if (-1 !== secondary.className.indexOf('toggled')) {
//            secondary.className = secondary.className.replace(' toggled', '');
//            button.className = button.className.replace(' toggled', '');
//        }
//        else {
//            secondary.className += ' toggled';
//            button.className += ' toggled';
//        }
//    };
//})();


// Slide out menu for mobile view
$(document).on("click", '#site-navigation h1', function(e) {
    if ($(this).hasClass('toggled')) {
        $('#secondary').removeClass('toggled');
        $('#site-navigation h1').first().removeClass('toggled');
    } else {
        $('#secondary').addClass('toggled');
        $('#site-navigation h1').first().addClass('toggled');
    }
});

jQuery(document).ready(function($) {
    var isTouchDevice = 'ontouchstart' in document.documentElement;
    if (isTouchDevice) {
        $("body").swipe({
            swipe: function(event, direction, distance, duration, fingerCount) {
                if (direction == "right") {
                    $('#secondary').addClass('toggled');
                    $('#site-navigation h1').first().addClass('toggled');
                } else if (direction == "left") {
                    $('#secondary').removeClass('toggled');
                    $('#site-navigation h1').first().removeClass('toggled');
                }
            },
            allowPageScroll: "vertical"
        });
    }
});

// Popup for video - enabled site wide so can be used anywhere
$("a.popup-video").on("click", function(e) {
    e.preventDefault();
    if (!$(this).attr("data-video-id"))
        return false;
    $("body").append("<div id='blackout'></div>");
    $("#blackout").animate({opacity: 1}, 500).append('<div id="popup"><div class="close"><a href="#">Close</a></div><iframe width="512" height="288" src="//www.youtube.com/embed/' + $(this).attr("data-video-id") + '?wmode=transparent" frameborder="0" allowfullscreen></iframe></div>');
});
$(document).on("click", "#popup .close a, #blackout", function(e) {
    e.preventDefault();
    $("#blackout").fadeOut(function() {
        $(this).remove();
    });
});

/*  Sub-menu behaviour */

$( document ).ready(function() {
$('.sub-menu').hide();
$("li").has("ul.sub-menu").click(function(){
  $("ul",this).slideDown();
});

$('#menu-main-nav ul li ul.sub-menu li a').click(function(e){
  if ($(this).attr('class') != 'active'){
    $('#menu-main-nav ul li a').removeClass('active');
    $(this).addClass('active');
  }
});

$('a').filter(function(){
  return this.href === document.location.href;
}).addClass('active');

$("ul.sub-menu > li > a").each(function () {
  var currentURL = document.location.href;
  var thisURL = $(this).attr("href");
  if (currentURL.indexOf(thisURL) != -1) {
    $(this).parents("ul.sub-menu").css('display', 'block');
  }
});

$('#menu-main-nav > ul > li > a').each(function(){
  var currURL = document.location.href;
  var myHref= $(this).attr('href');
  if (currURL.match(myHref)) {
    $(this).parent().find("ul.sub-menu").css('display', 'block');
  }
});
}); 