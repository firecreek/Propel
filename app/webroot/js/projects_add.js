
var ProjectsAdd = {
  
  companyCount:0,
  

  load: function()
  {
    var self = this;
    $('#PermissionProject input:radio').bind('change',function(){ self.checkPermissionAction(); });
    
    //If no companies to select from then default the create new company
    if(this.companyCount == 0)
    {
      $('#PermissionCreateCompany p span').hide();
      $('#PermissionSelectCompany').hide();
      $('#PermissionCreateCompany').show();
    }
    
    //Binds to toggle
    $('#PermissionSelectCompany a').bind('click',function(e){
      $('#PermissionCreateCompany').show();
      $('#PermissionSelectCompany').hide();
      $('#PermissionOption').val('create');
      return false;
    });
    
    $('#PermissionCreateCompany a').bind('click',function(e){
      $('#PermissionSelectCompany').show();
      $('#PermissionCreateCompany').hide();
      $('#PermissionOption').val('select');
      return false;
    });
    
    this.checkPermissionAction();
  },
  
  
  
  checkPermissionAction: function()
  {
    var val = $('#PermissionProject input:radio:checked').val();
    var opt = $('#PermissionOption').val();
    
    if(val == 'add')
    {
      $('#PermissionAddInputs').show();
      
      if(opt == 'select')
      {
        $('#PermissionSelectCompany').show();
        $('#PermissionCreateCompany').hide();
      }
      else
      {
        $('#PermissionCreateCompany').show();
        $('#PermissionSelectCompany').hide();
      }
      
    }
    else
    {
      $('#PermissionAddInputs').hide();
    }
  }
  

}


$(document).ready(function() {
  ProjectsAdd.load();
});

