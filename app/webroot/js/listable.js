
(function($) {

  $.fn.listable = function(options)
  {
    var opts = $.extend({}, $.fn.listable.defaults, options);

    return this.each(function() {

      var self = this;

      $.fn.listable.display(this,'hide');

      //Mouse over/out
      $(this).bind('mouseenter',function(e){
        $.fn.listable.display(this,'show');
      });

      $(this).bind('mouseleave',function(e){
        $.fn.listable.display(this,'hide');
      });
      
      //Inline editing
      if($(this).attr('rel-url'))
      {
        $(this).find('.edit').bind('click',function(e){
          e.preventDefault();
          $.fn.listable.edit(self);
        });
      }

    });
  };


  /**
   * Show / hide
   */
  $.fn.listable.display = function(obj,type)
  {
    //Locked in edit mode
    if($(obj).attr('rel-edit-lock') && type == 'show')
    {
      return;
    }
    
    var domArr = new Array;
    domArr[domArr.length] = '.maintain';
  
    if(!$(obj).hasClass('l-comments-with'))
    {
      domArr[domArr.length] = '.comment';
    }
  
    var doms = $(obj).find(domArr.join(','));
    
    if(type == 'show')
    {
      $(doms).show();
    }
    else
    {
      $(doms).hide();
    }
  };


  /**
   * Inline edit
   */
  $.fn.listable.edit = function(obj)
  {
    $(obj).attr('rel-edit-lock','true');
    
    var inline = $(obj).find('.inline');
    var loading = $(obj).find('.loading');
    var name = $(obj).find('.name');
    var maintain = $(obj).find('.maintain');
    var checkbox = $(obj).find('.check');
    
    var url = $(obj).attr('rel-url')+'?container=true';
    
    $(loading).show();
    
    $(inline).load(url,function(response,status,xhr){
      //Element visibilities
      $(inline).show();
      $(loading).hide();
      $(name).hide();
      $(checkbox).hide();
      $(maintain).hide();
      
      //Submit
      $(inline).find('form').ajaxSubmit();
      
      //Cancel link
      $(inline).find('.submit a').bind('click',function(e){
        e.preventDefault();
        
        $(inline).html('').hide();
        $(name).show();
        $(checkbox).show();
        
        $(obj).removeAttr('rel-edit-lock');
      });
      
    });
  }


  /**
   * Defaults
   */
  $.fn.listable.defaults = {
  };

})(jQuery);




$.fn.ajaxSubmit = function(e) {

	this.submit(function(){
  
    $(this).find('div.submit').append('<div class="saving"></div>');
  
		var params = {};
		$(this)
		.find("input[checked], input[type='text'], input[type='hidden'], input[type='password'], input[type='submit'], option[selected], textarea")
		.filter(":enabled")
		.each(function() {
			params[ this.name || this.id || this.parentNode.name || this.parentNode.id ] = this.value;
		});
    
    $.ajax({
      type: 'POST',
      url: this.getAttribute("action")+'.json',
      data: params,
      dataType: 'json',
      success: function(response)
      {
        if(response.success)
        {
          if(response.reload)
          {
            $('#main').load(response.reload);
          }
        }
      }
    });

		return false;
	});
	
	return this;
}
