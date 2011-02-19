
/**
 * Opencamp Dialog
 * 
 * @category jQuery Module
 * @package  OpenCamp
 * @version  1.0
 * @author   Darren Moore <darren.m@firecreek.co.uk>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://opencamp.firecreek.co.uk
 * 
 */
(function ($) {
  var OcDialog = function () {
    var defaults = {
      blank: ''
    },
    
    testFunction = function() {
    }
      
    return {
      init: function (opt) {
        opt = $.extend({}, defaults, opt||{});
        
        return this.each(function () {
          
          
        });
      }
    };
  }();
  $.fn.extend({
    OcDialog: OcDialog.init
  });
})(jQuery)
