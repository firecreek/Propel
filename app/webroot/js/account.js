
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
  },
  
  
  initMoveProject: function(id)
  {
    var obj = $('#'+id);
    
    $(obj).find('.move-project-link').bind('click',function(){
    
      var self = this;
    
      $(obj).find('.move-project-form').css({
        left:$(self).offset().left-300,
        top:$(self).offset().top-100,
      }).show();
      
      return false;
    });
    
    $(obj).find('.move-project-form form').ajaxSubmit();

    $(obj).find('.move-project-form div.submit a').bind('click',function(){
      $(obj).find('.move-project-form').hide();
      return false;
    });
    
    $(obj).find('.move-project-form select').bind('change',function(){
      if($(this).val())
      { 
        $(obj).find('.move-project-form div.submit input').removeAttr('disabled');
      }
      else
      {
        $(obj).find('.move-project-form div.submit input').attr('disabled',true);
      }
    });
  
    if($(obj).find('.move-project-form select').val())
    { 
      $(obj).find('.move-project-form div.submit input').removeAttr('disabled');
    }
    else
    {
      $(obj).find('.move-project-form div.submit input').attr('disabled',true);
    }
  }

}


$(document).ready(function() {
  Account.load();
});




/**
 * Call script
 */
function __scriptCall(url, options)
{
  if(options.loading)
  {
    $(options.element).find('.loading').show();
  }
  
  if(!options.data)
  {
    options.data = {};
  }
  
  if(options.element)
  {
    options.data['objId'] = $(options.element).attr('id');
  }

  //
  $.ajax({
    type: 'POST',
    url: url+'.js',
    dataType: 'script',
    cache: false,
    data: options.data,
    success: function(response)
    {
    }
  });
}

