
var Account = {
  
  load: function()
  {
    this._menus();
    this._flash();
    this._wysiwyg();
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

