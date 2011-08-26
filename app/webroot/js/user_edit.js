
var UserEdit = {
  
  load: function()
  {
    $('input[type=password]').val('password').addClass('dummy');
    
    $('input[type=password]').bind('focus',function(e){
      if($('#UserPassword').val() == 'password' && $('#UserPasswordConfirm').val() == 'password')
      {
        $('input[type=password]').val('').removeClass('dummy');
      }
    });
    
    $('input[type=password]').bind('blur',function(e){
      if(!$('#UserPassword').val() && !$('#UserPasswordConfirm').val())
      {
        $('input[type=password]').val('password').addClass('dummy');
      }
    });
    
  }

}


$(document).ready(function() {
  UserEdit.load();
});

