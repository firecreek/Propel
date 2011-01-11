
(function($) {

  $.fn.listable = function(options)
  {
    var opts = $.extend({}, $.fn.listable.defaults, options);

    return this.each(function() {

      $.fn.listable.display($(this).attr('id'),'hide');

      $(this).bind('mouseenter mouseleave',function(e){
        if(e.type == 'mouseenter')
        {
          $.fn.listable.display($(this).attr('id'),'show');
        }
        else
        {
          $.fn.listable.display($(this).attr('id'),'hide');
        }
      });

    });
  };


  /**
   * Show / hide
   */
  $.fn.listable.display = function(ident,type)
  {
    var obj = '#'+ident;
    
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
   * Defaults
   */
  $.fn.listable.defaults = {
  };

})(jQuery);


