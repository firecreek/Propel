
(function($) {

  $.fn.listable = function(options)
  {
    var opts = $.extend({}, $.fn.listable.defaults, options);

    //Sortable
    if(opts.sortable)
    {
      $('.sortable').sortable({
        connectWith: '.sortable',
        axis: 'y',
        handle: '.position',
        start: function(event,ui){
          $('.listable .item').attr('rel-maintain-lock','true');
        },
        update: function(event,ui){
          if(this === ui.item.parent()[0])
          {
            $('.listable .item').removeAttr('rel-maintain-lock');
            $.fn.listable.updatePositions(this,opts.positionUrl);
          }
        }
      }).disableSelection();
    }

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
      
      //Delete
      $(this).find('.delete a').bind('click',function(e){
        $.fn.listable.delete(self);
        return false;
      });
      
      //Checkbox
      if($(this).attr('rel-update-url'))
      {
        $(this).find('.check input').bind('change',function(e){
          $.fn.listable.checked(self);
        });
      }
      
      //Inline editing
      if($(this).attr('rel-edit-url'))
      {
        $(this).find('.edit').bind('click',function(e){
          e.preventDefault();
          $.fn.listable.edit(self);
        });
      }

    });
  };


  /**
   * Update positions
   */
  $.fn.listable.updatePositions = function(obj,url)
  {
    var params = {};
  
    $('.listable .group').each(function(){
      
      var todoId = $(this).attr('rel-todo-id');
      var count = 0;
      
      $(this).find('.items .item').each(function(){
        count++;
        params['TodoItem'+$(this).attr('rel-record-id')] = todoId+'-'+count;
      });
      
    });
    
    $.ajax({
      type: 'POST',
      url: url+'.json',
      dataType: 'json',
      data: params,
      cache: false,
      success: function(response)
      {
        if(response.success)
        {
        }
      }
    });
  }


  /**
   * Check
   */
  $.fn.listable.checked = function(obj)
  {
    var loading = $(obj).find('.loading');
    $(loading).show();
    
    $.fn.listable.checkActive++;
    
    $.ajax({
      type: 'POST',
      url: $(obj).attr('rel-update-url')+'/'+$(obj).find('.check input').is(':checked')+'.json',
      dataType: 'json',
      cache: false,
      success: function(response)
      {
        $.fn.listable.checkActive--;
        
        if(response.success)
        {
          if(response.reload && $.fn.listable.checkActive == 0)
          {
            $('#main').load(response.reload);
          }
        }
      }
    });
  }
  
  $.fn.listable.checkActive = 0;


  /**
   * Show / hide
   */
  $.fn.listable.display = function(obj,type)
  {
    //Locked in edit mode
    if($(obj).attr('rel-maintain-lock'))
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
    $(obj).attr('rel-maintain-lock','true');
    
    var inline = $(obj).find('.inline');
    var loading = $(obj).find('.loading');
    var name = $(obj).find('.name');
    var maintain = $(obj).find('.maintain');
    var checkbox = $(obj).find('.check');
    
    var url = $(obj).attr('rel-edit-url')+'?container=true';
    
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
        
        $(obj).removeAttr('rel-maintain-lock');
      });
      
    });
  }


  /**
   * Inline delete
   */
  $.fn.listable.delete = function(obj)
  {
    var loading = $(obj).find('.loading');
    $(loading).show();
    
    var url = $(obj).attr('rel-delete-url');
    
    $.ajax({
      type: 'POST',
      url: url+'.json',
      dataType: 'json',
      success: function(response)
      {
        if(response.success)
        {
          if(response.reload)
          {
            $('#main').load(response.reload);
          }
          else
          {
            $(obj).fadeOut();
          }
        }
      }
    });
  }


  /**
   * Defaults
   */
  $.fn.listable.defaults = {
    sortable: false
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
