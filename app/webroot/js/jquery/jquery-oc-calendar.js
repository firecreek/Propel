
/**
 * Opencamp Calendar
 *
 * Wrapper for jQuery UI Date picker
 * 
 * @category jQuery Module
 * @package  OpenCamp
 * @version  1.0
 * @author   Darren Moore <darren.m@firecreek.co.uk>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://opencamp.firecreek.co.uk
 * 
 */

(function($) {
  $.widget("oc.calendar",{
    
    options: {
    },
    
    _create: function()
    {
      var self = this;
      
      $(this.element).find('select').hide();
      
      $(this.element).datepicker({
        changeMonth: true,
        changeYear: true,
        firstDay: 1,
        dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        dateFormat: 'MM d, yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText) {
          self._updateDate.apply(self,arguments)
        }
      });
      
      $(this.element).append('<div class="ui-current-date">Loading</div>');
      this._updateDate.apply(this);
    },
    
    
    _updateDate: function(dateText)
    {
      var rawDate = $(this.element).datepicker('getDate');
      
      $('.calendar div').find('select[name*=month]').val($.datepicker.formatDate('mm',rawDate));
      $('.calendar div').find('select[name*=day]').val($.datepicker.formatDate('dd',rawDate));
      $('.calendar div').find('select[name*=year]').val($.datepicker.formatDate('yy',rawDate));
      
      $(this.element).find('.ui-current-date').html($.datepicker.formatDate('MM d, yy',rawDate));
    },
    
    destroy: function()
    {
      $.Widget.prototype.destroy.call(this);
    }    
    
  
  });
})(jQuery);
