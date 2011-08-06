/**
 * Admin
 */
var Admin = {};

/**
 * functions to execute when document is ready
 *
 * @return void
 */
Admin.documentReady = function() {
    Admin.linkToggles();
}

/**
 * Toggle permissions (enable/disable)
 *
 * @return void
 */
Admin.linkToggles = function() {
    $('a.toggle').unbind();
  
    $('a.toggle').click(function() {      
        // show loader
        $(this).find('img').attr('src', '/img/icons/circle_ball.gif');

        var href = $(this).attr('href');
      
        $(this).parent().load(href, function() {
            Admin.linkToggles();
        });

        return false;
    });
}

/**
 * document ready
 *
 * @return void
 */
$(document).ready(function() {
    Admin.documentReady();
});