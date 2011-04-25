
var Categories = {


  load: function()
  {
    var self = this; 
  
    //Add item link
    $('.add-record-link a').live('click',function(e){
      $('#'+$(this).attr('rel')).find('.add-record-link').hide();
      $('#'+$(this).attr('rel')).find('.category-add').show();
      $('#'+$(this).attr('rel')).find('input[type=text]').focus();
      
      return false;
    });
    
    //Cancel button
    $('.category-add .submit a').live('click',function(e){
      var addContainer = $(this).closest('.add-record-container');
      
      if(addContainer)
      {
        $(addContainer).find('.category-add').hide();
        $(addContainer).find('.add-record-link').show();
      }
      else
      {
        $(this).closest('.category-add').hide();
      }
    
      return false;
    });
    
  }

}


$(document).ready(function() {
  Categories.load();
});

