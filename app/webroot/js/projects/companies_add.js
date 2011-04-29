
var CompanyAdd = {

  load: function()
  {
    var self = this;

    if($('#CompanyAddExisting').length > 0)
    {
      self._addExisting();
    }
    else
    {
      self._addNew();
    }
    
    //Binds to toggle
    $('#CompanyAddNew a').bind('click',function(e){
      self._addExisting();
      return false;
    });
    
    $('#CompanyAddExisting a').bind('click',function(e){
      self._addNew();
      return false;
    });
    
  },
  
  
  _addNew: function()
  {
    $('#CompanyAddNew').show();
    $('#CompanyAddExisting').hide();
    $('#PermissionOption').val('create');
    
    $('form#CompanyForm div.submit input').val($('form#CompanyForm div.submit input').attr('rel-text-new'));
  },
  
  
  _addExisting: function()
  {
    $('#CompanyAddExisting').show();
    $('#CompanyAddNew').hide();
    $('#PermissionOption').val('select');
    
    $('form#CompanyForm div.submit input').val($('form#CompanyForm div.submit input').attr('rel-text-existing'));
  }
  
  
}


$(document).ready(function() {
  CompanyAdd.load();
});

