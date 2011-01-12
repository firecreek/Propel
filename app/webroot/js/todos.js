
var Todos = {

  load: function()
  {
  
    $('.sortable').sortable({
      connectWith: '.sortable',
      axis: 'y',
      start: function(event,ui){
        $('.listable .item').attr('rel-maintain-lock','true');
      },
      update: function(event,ui){
        $('.listable .item').removeAttr('rel-maintain-lock');
      }
    }).disableSelection();
    
    
    $('.item-add .submit a').bind('click',function(e){
      var group = $('.item-add .submit a').closest('.group');
      
      $(group).find('.item-add').hide();
      $(group).find('.add-item-link').show();
    
      return false;
    });
  
    $('.add-item-link a').bind('click',function(e){
      $('#'+$(this).attr('rel')).find('.add-item-link').hide();
      $('#'+$(this).attr('rel')).find('.item-add').show();
      
      return false;
    });
  }

}


$(document).ready(function() {
  Todos.load();
});

