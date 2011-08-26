
var Categories = {


  load: function()
  {
    var self = this; 
    
    if($('.category-select').length > 0)
    {
      this._initSelectMode();
    }
    
    if($('.category-filter').length > 0)
    {
      this._initFilterMode();
    }
    
    if($('.add-record-link').length > 0)
    {
      this._initEditMode();
    }
  },
  
  
  
  _initSelectMode: function()
  {
    $('.category-select select').bind('change',function(e){
      if($(this).val() == '_add')
      {
        var name = prompt('Enter the new category name:')
        
        if(name)
        {
          //Add new category
          var select = this;
          
          //
          var savingObj = $(this).closest('.category-select').find('.saving');
          $(savingObj).show();
          $(select).attr('disabled',true);
          
          //@todo Use __scriptCall
          $.ajax({
            type: 'POST',
            url: $(this).attr('rel-add-url')+'.js',
            dataType: 'script',
            cache: false,
            data: {
              data: {
                Category: { name: name },
                Select: { id:$(this).attr('id') }
              }
            },
            success: function(response)
            {
              $(savingObj).hide();
              $(select).removeAttr('disabled');
            }
          });
          
        }
      }
    });
  },
  
  
  _initFilterMode: function()
  {
    $('.category-filter').each(function(){
    
      var self = this;
      var editLink = $(this).find('a[rel=edit-mode]');
      var editDefaultText = $(editLink).text();
      
      $(editLink).bind('click',function(){
        
        if(!$(self).hasClass('ui-state-edit'))
        {
          //On
          $(self).addClass('ui-state-edit');
          $(editLink).text($(editLink).attr('rel-edit-text'));
          
          $('.category-filter .listable').data('listable').enable();
        }
        else
        {
          //Off
          $(self).removeClass('ui-state-edit');
          $(editLink).text(editDefaultText);
          
          $('.category-filter .listable').data('listable').disable();
        }
        
        return false;        
      });
    
    });
  },
  
  
  
  _initEditMode: function()
  {
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

