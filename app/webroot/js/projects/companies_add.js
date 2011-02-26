
var CompanyAdd = {

  load: function()
  {

    if($('#CompanyAddExisting'))
    {
      $('#CompanyAddNew').hide();
      $('#CompanyAddExisting').show();
      $('#PermissionOption').val('select');
    }
    else
    {
      $('#CompanyAddExisting').hide();
      $('#PermissionOption').val('create');
    }
    
    //Binds to toggle
    $('#CompanyAddNew a').bind('click',function(e){
      $('#CompanyAddExisting').show();
      $('#CompanyAddNew').hide();
      $('#PermissionOption').val('select');
      return false;
    });
    
    $('#CompanyAddExisting a').bind('click',function(e){
      $('#CompanyAddNew').show();
      $('#CompanyAddExisting').hide();
      $('#PermissionOption').val('create');
      return false;
    });
    
  }
  
  
}


$(document).ready(function() {
  CompanyAdd.load();
});

