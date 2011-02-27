
var Account = {
  
  load: function()
  {
    $("ul.sf-menu").superfish({
      autoArrows: false,
      speed: 400,
      delay: 500,
      delayShow: 300
    });
    
    this._wysiwyg();
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

