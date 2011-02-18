
var Todos = {

  reorderLists: false,

  load: function()
  {
    var self = this;

    $('.listable').sortable({
      axis: 'y',
      handle: '.header .position',
      disabled: true,
      start: function(event,ui){
      },
      update: function(event,ui){
      }
    }).disableSelection();
    
  
    $('#reorderLists').bind('click',function(e){
    
      if(!self.reorderLists)
      {
        //Show
        self.reorderLists = true;
        
        $('#todoFilter').hide();
        
        $(this).addClass('active');
        $(this).html($(this).attr('rel-active'));
      
        $('.listable').addClass('reorder-todos');
        
        $('.listable .header .maintain').show();
        $('.listable .header .item').attr('rel-maintain-lock','true');
        
        //Sortable
        $('.listable').sortable({ disabled: false });
      }
      else
      {
        //Save        
        var params = {};
        var count = 0;

        $('.listable .header .item').each(function(){
          count++;
          params['Todo'+$(this).attr('rel-record-id')] = count;
        });
        
        $.ajax({
          type: 'POST',
          url: $(this).attr('rel-update-url')+'.js',
          dataType: 'script',
          data: params,
          cache: false,
          success: function(response)
          {
          }
        });
      
        //Hide
        self.reorderLists = false;
        
        $('#todoFilter').show();
        
        $(this).removeClass('active');
        $(this).html($(this).attr('rel-not-active'));
      
        $('.listable').removeClass('reorder-todos');
        
        $('.listable .header .maintain').hide();
        $('.listable .header .item').removeAttr('rel-maintain-lock');
        
        //Sortable
        $('.listable').sortable({ disabled: true });
      }
      
      return false;
    });
    
    
    $('.item-add .submit a').live('click',function(e){
      var group = $('.item-add .submit a').closest('.group');
      
      $(group).find('.item-add').hide();
      $(group).find('.add-item-link').show();
    
      return false;
    });
  
    $('.add-item-link a').live('click',function(e){
      $('#'+$(this).attr('rel')).find('.add-item-link').hide();
      $('#'+$(this).attr('rel')).find('.item-add').show();
      $('#'+$(this).attr('rel')).find('textarea').focus();
      
      return false;
    });
  }

}


$(document).ready(function() {
  Todos.load();
});

