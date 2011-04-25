
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
      datepicker: {}
    },
    
    _create: function()
    {
      var self = this;
      
      $(this.element).find('select').hide();
      
      $(this.element).datepicker(jQuery.extend({
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
      },this.options.datepicker));
      
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



/**
 * Dialog Calendars
 */
var Calendar = {

  active:'',
  inside:false,
  
  load: function()
  {
    if($('#Calendar').length == 0) { return; }
  
    //Detect clicks outside of element
    var self = this;
    $('#Calendar').hover(function(){ 
      self.inside = true;
    }, function(){ 
      self.inside = false;
    });
    
    $('body').mouseup(function(){ 
      if(!self.inside) { $('#Calendar').hide(); }
    });
  },

  show: function(id)
  {
    this.active = id;
    
    $('#Calendar').css({
      left:($('#'+this.active).offset().left+$('#'+this.active).width())+35,
      top:$('#'+this.active).offset().top-60
    }).show();
  },
  
  updateDate: function(date)
  {
    $('#'+this.active).removeClass('unimportant').val(date);
    $('#Calendar').hide();
    this.active = '';
  }

}

$(document).ready(function() {
  Calendar.load();
});

