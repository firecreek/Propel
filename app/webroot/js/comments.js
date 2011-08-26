
var Comments = {
  

  load: function()
  {
    var self = this;
  
    $('.comments .section li.delete a').bind('click',function(e){
    
      if(confirm('Are you sure you want to delete this message?\nNote: There is no undo.'))
      {
        $(this).closest('li.delete').addClass('loading');
        
        var recordId = $(this).closest('.section').attr('rel-id');
          
        $.ajax({
          type: 'POST',
          url: $(this).attr('href')+'.js',
          dataType: 'script',
          cache: false,
          success: function(response)
          {
          }
        });
      }

      return false;
    });
  }

}


$(document).ready(function() {
  Comments.load();
});

