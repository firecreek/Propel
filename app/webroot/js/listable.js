

(function($) {
  $.widget("oc.listable",{
    
    options: { 
      sortable:false,
      disabled:false,
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
          if(this === ui.item.parent()[0])
          {
            self._positions(self.options.positionUrl);
          }
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
      this.editElems = this.listElements.find('.edit-link')
        .live('click.listable', function() { return self._edit(self,this); });
      
      //Private
      this.privateElems = this.listElements.find('.extra.private')
        .bind('mouseenter.listable mouseleave.listable', this._privatePop);
        
      //Fix label widths
      this.element.find('.item').each(function(){
        var width = ($(this).width() - $(this).find('.label').offset().left);
        $(this).find('.label').width(width);
      });
      
      if(!this.options.disabled)
      {
        this.enable();
      }
    },
    
    
    enable: function()
    {
      this.options.disabled = false;
      
      //Replace edit and view links
      this.listElements.each(function(){
        if($(this).attr('rel-view-url') && $(this).attr('rel-edit-url'))
        {
          $(this).find('.name a').attr('href',$(this).attr('rel-edit-url')).addClass('edit-link');
        }
      });
    },
    
    
    disable: function()
    {
      this.options.disabled = true;
      
      //Replace edit and view links
      this.listElements.each(function(){
        if($(this).attr('rel-view-url') && $(this).attr('rel-edit-url'))
        {
          $(this).find('.name a').attr('href',$(this).attr('rel-view-url')).removeClass('edit-link');
        }
      });
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
      if(confirm('Are you sure you want to delete this record?'))
      {
        var item = $(this).closest('.item');
        __scriptCall(item.attr('rel-delete-url'),{ element:item,loading:true });
      }
      
      return false;
    },
    
    
    /**
     * Checked list item
     */
    _checked: function()
    {
      var item = $(this).closest('.item');
      var checked = $(this).is(':checked');
      var url = item.attr('rel-update-url');
      var data = {};
      
      //@todo Centralise this code
      if(url.indexOf('?') !== -1)
      {
        var split = url.substr(url.indexOf('?')).split('&');
        
        for(var ii = 0; ii < split.length; ii++)
        {
          var keyVal = split[ii].split('=');
          data[keyVal[0].replace('?','')] = keyVal[1];
        }
        
        url = url.substr(0,url.indexOf('?'));
      }
      
      __scriptCall(url+'/'+checked,{ element:item, loading:true, data:data });
      
      return false;
    },
    
    
    /**
     * Inline edit
     */
    _edit: function(ui,element)
    {    
      var self = this;
    
      var item      = $(element).closest('.item');
      var group     = $(element).closest('.group');
      var inline    = $(item).find('.inline');
      var loading   = $(item).find('.loading');
      var after     = $(item).find('.after');
      var overview  = $(item).find('.overview');
      
      var header    = ($(element).closest('.header').length > 0) ? true : false;
      
      //@todo Centralise this code
      var url = $(item).attr('rel-edit-url');
      var data = { objId:$(item).attr('id') };
      if(url.indexOf('?') !== -1)
      {
        var split = url.substr(url.indexOf('?')).split('&');
        
        for(var ii = 0; ii < split.length; ii++)
        {
          var keyVal = split[ii].split('=');
          data[keyVal[0].replace('?','')] = keyVal[1];
        }
        
        url = url.substr(0,url.indexOf('?'));
      }

      $(item).addClass('ui-state-loading');
      
      //@todo Move this to __scriptCall
      $(inline).load(url,data,function(response,status,xhr){
        //Element visibilities
        $(element).removeClass('ui-state-active');
        $(item).removeClass('ui-state-loading');
        $(item).addClass('ui-state-edit');
        
        //Trigger
        self._trigger("edit", element, {
          element: element,
          item: item
        });
        
        //Submit
        $(inline).find('form:first').ajaxSubmit();
        
        //Cancel link
        $(inline).find('.submit:first a').bind('click',function(e){
          $(item).removeClass('ui-state-edit');
          
          $(item).removeClass('ui-state-active');
          
          e.preventDefault();
          $(inline).html('');
          //$(overview).show();
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




