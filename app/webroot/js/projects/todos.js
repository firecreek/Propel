
var Todos = {

  reorderLists: false,

  load: function()
  {
    var self = this;

    //Add new
    $('#TodoList .col.right a.add').bind('click',function(e){
      $('#TodoList').hide();
      $('#TodoAdd').show();
      return false;
    });
    
    $('#TodoAdd div.submit a').bind('click',function(e){
      $('#TodoList').show();
      $('#TodoAdd').hide();
      
      $(':input','#TodoAdd')
       .not(':button, :submit, :reset, :hidden')
       .val('')
       .removeAttr('checked')
       .removeAttr('selected');
      
      return false;
    });
    
    
    //RHS Filters
    $('#TodoFilter div.submit').hide();
    $('#TodoFilter select').bind('change',function(e){
      
      var form = $(this).closest('form');
      
      $(form).submit();
    
      $(form).find('select').attr('disabled',true);
      
      setTimeout(function(){
        $(form).find('select').removeAttr('disabled');
      },10000);
    });


    //Reordering main lists
    $('.listable').sortable({
      axis: 'y',
      handle: '.header .position',
      disabled: true,
      start: function(event,ui){
      },
      update: function(event,ui){
      }
    });
    
    $('#reorderLists').bind('click',function(e){
    
      if(!self.reorderLists)
      {
        //Show
        self.reorderLists = true;
        
        $('#todoFilter').hide();
        
        $(this).addClass('active');
        $(this).html($(this).attr('rel-active'));
      
        $('.listable').addClass('reorder-todos');
        
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
        
        //Sortable
        $('.listable').sortable({ disabled: true });
      }
      
      return false;
    });
  
  
    //Add item link
    $('.add-item-link a').live('click',function(e){
      $('#'+$(this).attr('rel')).find('.add-item-link').hide();
      $('#'+$(this).attr('rel')).find('.item-add').show();
      $('#'+$(this).attr('rel')).find('textarea').focus();
      
      return false;
    });
    
    //Cancel button
    $('.item-add .submit a').live('click',function(e){
      var group = $('.item-add .submit a').closest('.group');
      var addContainer = $(this).closest('.add-item-container');
      
      if(addContainer)
      {
        $(addContainer).find('.item-add').hide();
        $(addContainer).find('.add-item-link').show();
      }
      else
      {
        $(this).closest('.item-add').hide();
      }
    
      return false;
    });
    
  }

}


$(document).ready(function() {
  Todos.load();
});

