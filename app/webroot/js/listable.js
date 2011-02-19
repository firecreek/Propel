

(function($) {
  $.widget("oc.listable",{
    
    options: { 
      sortable:false, 
      edit: {
        ajaxSubmit: {
          stickyLoad:false
        }
      }
    },
    
    _create: function()
    {
      //Self
      var self = this;
    
      //Make this element sortable
      $(this.element).find('.sortable').sortable({
        connectWith: '.sortable',
        axis: 'y',
        handle: '.position',
        start: function(event,ui){
        },
        update: function(event,ui){
          self._positions(self.options.positionUrl);
        }
      });
      
      
      //Items
      this.listElements = this.element.find('.item');
    
      //Hover
      this.hoverElems = this.listElements
        .bind('mouseenter.listable mouseleave.listable', this._hover);
      
      //Checkbox
      this.checkElems = this.listElements.find('.check input')
        .bind('change.listable', this._checked);
      
      //Delete
      this.deleteElems = this.listElements.find('.delete a')
        .bind('click.listable', this._delete);
        
      //Edit
      this.editElems = this.listElements.find('.edit')
        .bind('click.listable', this._edit);
      
      //Private
      this.privateElems = this.listElements.find('.extra.private')
        .bind('mouseenter.listable mouseleave.listable', this._privatePop);
    },
    
    
    reset: function()
    {
      this._unbinds();
      this._create();
    },
    
    
    destroy: function()
    {
      this._unbinds();
      $.Widget.prototype.destroy.call(this);
    },
    
    
    _unbinds: function()
    {
      this.hoverElems.unbind(".listable");
      this.checkElems.unbind(".listable");
      this.deleteElems.unbind(".listable");
      this.editElems.unbind(".listable");
      this.privateElems.unbind(".listable");
      
      $(this.element).find('.sortable').sortable('destroy');
    },
    
    
    /**
     * Delete list item
     */
    _delete: function(e)
    {      
      var item = $(this).closest('.item');
      __scriptCall(item.attr('rel-delete-url'),{ element:item,loading:true });
      
      return false;
    },
    
    
    /**
     * Checked list item
     */
    _checked: function()
    {
      var item = $(this).closest('.item');
      var checked = $(this).is(':checked');
      
      __scriptCall(item.attr('rel-update-url')+'/'+checked,{ element:item,loading:true });
      
      return false;
    },
    
    
    /**
     * Inline edit
     */
    _edit: function()
    {
      var self = this;
      
      var item      = $(this).closest('.item');
      var inline    = $(item).find('.inline');
      var loading   = $(item).find('.loading');
      var overview  = $(item).find('.overview');
      
      var url = $(item).attr('rel-edit-url')+'?container=true&objId='+$(item).attr('id');
      
      $(loading).show();
      $(item).addClass('ui-state-loading');
      
      $(inline).load(url,function(response,status,xhr){
        //Element visibilities
        $(loading).hide();
        $(overview).hide();
        $(inline).show();
        $(self).removeClass('ui-state-active');
        $(item).removeClass('ui-state-loading');
        
        //Submit
        $(inline).find('form').ajaxSubmit();
        
        //Cancel link
        $(inline).find('.submit a').bind('click',function(e){
          e.preventDefault();
          $(inline).html('').hide();
          $(overview).show();
        });
        
      });
      
      return false;
    },
    
    
    /**
     * Positions update
     */
    _positions: function(url)
    {
      var params = {};
    
      $('.listable .group').each(function(){
        
        var todoId = $(this).attr('rel-todo-id');
        var count = 0;
        
        $(this).find('.items .item').each(function(){
          count++;
          params['TodoItem'+$(this).attr('rel-record-id')] = todoId+'-'+count;
        });
        
      });
      
      __scriptCall(url, { data:params });
    },
    
    
    /**
     * Hover over the list element
     */
    _hover: function(e)
    {
      if(e.type == 'mouseenter')
      {
        $(this).addClass('ui-state-active');
      }
      else
      {
        $(this).removeClass('ui-state-active');
      }
    },
    
    
    _privatePop: function(e)
    {
      var popElem = $(this).closest('.item').find('.private-pop');
    
      if(e.type == 'mouseenter')
      {
        $(popElem).show();
      }
      else
      {
        $(popElem).hide();
      }
    },
    
    
  
  });
})(jQuery);



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
  
  //
  if(options.element)
  {
    url = url + '.js?objId='+$(options.element).attr('id');
  }
  else
  {
    url = url + '.js';
  }

  //
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'script',
    cache: false,
    data: options.data,
    success: function(response)
    {
    }
  });
}


