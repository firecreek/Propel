
var Account = {
  
  load: function()
  {
    $('.listable .item').bind('mouseenter mouseleave',function(e){
      if(e.type == 'mouseenter')
      {
        Account.listableDisplay($(this).attr('id'),'show');
      }
      else
      {
        Account.listableDisplay($(this).attr('id'),'hide');
      }
    });
  },
  
  
  listableDisplay: function(ident,type)
  {
    var doms = $('#'+ident).find('.maintain,.comment');
    if(type == 'show')
    {
      $(doms).show();
    }
    else
    {
      $(doms).hide();
    }
  }

}


$(document).ready(function() {
  Account.load();
});

