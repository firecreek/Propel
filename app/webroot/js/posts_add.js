
var PostsAdd = {

  load: function()
  {
    this.checkPrivate();
    
    var self = this;
    $('#PostPrivate').bind('change',function(){
      self.checkPrivate();
    });

  },
  
  checkPrivate: function()
  {
    if($('#PostPrivate').is(':checked'))
    {
      $('#PostPrivate').closest('form').find('fieldset.details').addClass('private');
    }
    else
    {
      $('#PostPrivate').closest('form').find('fieldset.details').removeClass('private');
    }
  }

}


$(document).ready(function() {
  PostsAdd.load();
});


