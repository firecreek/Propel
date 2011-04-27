
var Subscribe = {

  company:'',

  load: function()
  {
    var self = this;
    
    $('.subscribe-list').each(function(){
    
      var subList = this;
      
      //All
      $(this).find('tr.all input[type=checkbox]').bind('change',function(){
        var company = $(this).attr('rel-company');
        var inputs = $(subList).find('tr.person input[rel-company="'+company+'"]');
        
        if($(this).is(':checked'))
        {
          //Select
          $(inputs).attr('checked',true);
        }
        else
        {
          //Unselect
          $(inputs).removeAttr('checked');
        }
      });
      
      //Single
      $(this).find('tr.person input[type=checkbox]').bind('change',function(){
        $(subList).find('tr.all input[rel-company="'+$(this).attr('rel-company')+'"]').removeAttr('checked');
      });
      
      //Private checkbox
      $('input.private').bind('change',function(){
      
        var tableRows = $(subList).find('tr[rel-company!="'+self.company+'"]');
      
        if($(this).is(':checked'))
        {
          $(tableRows).hide();
        }
        else
        {
          $(tableRows).show();
        }
      
      });
      
    });
  }

}


$(document).ready(function() {
  Subscribe.load();
});

