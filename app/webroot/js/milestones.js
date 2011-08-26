
var Milestones = {

  refreshUrl:'',
  refreshTimer:'',

  load: function()
  {
    //Filter
    $('#MilestoneFilterForm div.submit').hide();
    
    $('#MilestoneFilterForm select').bind('change',function(e){
      $('#MilestoneFilterForm').submit();
    });
    
    
    //Add new
    if($('#MilestoneList .col.right a.add'))
    {
      $('#MilestoneList .col.right a.add').live('click',function(e){
        $('#MilestoneList').hide();
        $('#MilestoneAdd').show();
        
        $('#MilestoneAdd form')[0].reset();
        $('#MilestoneAdd form :input:visible:first').focus();
        
        return false;
      });
      
      $('#MilestoneAdd div.submit a').live('click',function(e){
        $('#MilestoneList').show();
        $('#MilestoneAdd').hide();
        
        $(':input','#MilestoneAdd')
         .not(':button, :submit, :reset, :hidden')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected');
        
        return false;
      });
    }
  },
  
  checkSections: function()
  {
    $('.section').each(function(){
      console.log($(this).find('.item').length);
      if($(this).find('.item').length == 0)
      {
        $(this).fadeOut();
      }
    });
  },
  
  
  refresh: function()
  {
    var self = this;
    this.refreshTimer = setTimeout(function() { self._callRefresh(); },500);
  },
  
  
  _callRefresh: function()
  {
    //Destroy listable first
    if($('.listable') && $('.listable').data('listable'))
    {
      $('.listable').data('listable').destroy();
    }
    
    $('#main').load(this.refreshUrl);
  }
  

}


$(document).ready(function() {
  Milestones.load();
});

