
var Todos = {

  reorderLists: false,

  load: function()
  {
    var self = this;

    //Add new
    $('#TodoList .col.right a.add').bind('click',function(e){
      $('#TodoList').hide();
      $('#TodoAdd').show();
      
      $('#TodoAdd form')[0].reset();
      $('#TodoAdd form :input:visible:first').focus();
      
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
  
  
    //Add item link
    $('.add-item-link a').live('click',function(e){
      $('#'+$(this).attr('rel')).find('.add-item-link').hide();
      $('#'+$(this).attr('rel')).find('.item-add').show();
      $('#'+$(this).attr('rel')).find('textarea').focus();
      
      return false;
    });
    
    //Cancel button
    $('.item-add .submit a').live('click',function(e){
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
  
  
    //Notify
    $('.item-add .fields select[name*=responsible]').live('change',function(e){
      self._notifyCheck(this);
    });
    
    
    //Reorder lists
    $('#reorderLists').bind('click',function(e){
    
      if(!self.reorderLists)
      {
        //Show
        
        //Check a group isn't being edited
        if($('.group.ui-state-edit').length == 1)
        {
          alert('There are todo groups in edit mode, please save your changes first');
          return false;
        }
        
        //
        self.reorderLists = true;
        
        $(this).addClass('active');
        $(this).html($(this).attr('rel-active'));
        
        //Sortable
        $('#TodoActiveList').sortable({ disabled:false }).addClass('reorder');
      }
      else
      {
        //Save        
        var params = {};
        var count = 0;

        $('#TodoActiveList li').each(function(){
          count++;
          params['Todo'+$(this).attr('data-todo-id')] = count;
        });
        
        $.ajax({
          type: 'POST',
          url: $(this).attr('rel-update-url')+'.js',
          dataType: 'script',
          data: params,
          cache: false,
          success: function(response)
          {
            window.location.reload()
          }
        });
        return false;
      
        //Hide
        self.reorderLists = false;
        
        $(this).removeClass('active');
        $(this).html($(this).attr('rel-not-active'));
        
        //Sortable
        $('#TodoActiveList').sortable({ disabled: true }).removeClass('reorder');
      }
      
      return false;
    });
    
  },
  
  
  _notifyCheck: function(obj)
  {
    var val = $(obj).val();
    var notifyDom = $(obj).closest('.options').find('.notify');
    var personName = $(obj).find('option:selected').text();
    
    if(val == 'self' || val.substr(0,7) == 'person_')
    {
      var text = $(notifyDom).find('input[type=checkbox]').attr('rel-label-text');
      text = text.replace('%s',personName);
      $(notifyDom).find('label').text(text);
      
      $(notifyDom).show();
    }
    else
    {
      $(notifyDom).hide();
    }
  }

}


$(document).ready(function() {
  Todos.load();
});

