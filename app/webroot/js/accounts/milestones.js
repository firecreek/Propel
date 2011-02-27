
var Milestones = {
  

  load: function()
  {
  
    $('#MilestoneFilterForm div.submit').hide();
    
    $('#MilestoneFilterForm select').bind('change',function(e){
      $('#MilestoneFilterForm').submit();
    });
    
  }

}


$(document).ready(function() {
  Milestones.load();
});

