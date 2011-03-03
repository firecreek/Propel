
var Account = {
  
  load: function()
  {
    this._menus();
    this._flash();
    this._wysiwyg();
  },
  
  errorShow: function(message,options)
  {
    var div = $('<div class="dialog error">');
    
    var html = '';
    html += '<div class="wrapper">';
    html += '<h1>There was an error</h1>'+message;
    html += '</div>';
    html += '<div class="close-button"><span></span></div>';
  
    $('body').prepend('<div class="lights-out"></div>');
    $('body').prepend(div);
  
    $(div).html(html).css({
      top:  ($(document).height()/2) - ($(div).height() / 2)-100,
      left: ($(document).width()/2) - ($(div).width() / 2)
    });
    
    $(div).find('.close-button').bind('click',function(){
      $('.lights-out').hide();
      $(div).hide();
    });
    
    $('.lights-out').height($(document).height()).show();
  },
  
  _menus: function()
  {
    $("ul.sf-menu").superfish({
      autoArrows: false,
      speed: 400,
      delay: 500,
      delayShow: 300
    });
  },
  
  _flash: function()
  {
    if($('#flashMessage.success'))
    {
      setTimeout(function() { $('#flashMessage.success').effect('highlight'); },500);
      setTimeout(function() { $('#flashMessage.success').fadeOut(); },3000);
    }
  },
  
  _wysiwyg: function()
  {
    if($('.wysiwyg').length > 0)
    {
      $('.wysiwyg').rte({
        media_url:'/img/rte/',
        content_css_url:'/css/rte.css'
      });
    }
  }

}


$(document).ready(function() {
  Account.load();
});

