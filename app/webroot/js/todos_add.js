
var TodosAdd = {

  load: function()
  {
    this.checkPrivate();
    
    var self = this;
    $('#TodoPrivate').bind('change',function(){
      self.checkPrivate();
    });

  },
  
  checkPrivate: function()
  {
    if($('#TodoPrivate').is(':checked'))
    {
      $('#TodoPrivate').closest('.input').addClass('private');
    }
    else
    {
      $('#TodoPrivate').closest('.input').removeClass('private');
    }
  }

}


$(document).ready(function() {
  TodosAdd.load();
});


