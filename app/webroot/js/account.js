
var Account = {
  
  load: function()
  {
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

