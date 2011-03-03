
var Milestones = {

  refreshUrl:'',
  refreshTimer:'',

  load: function()
  {
    //Add new
    $('#MilestoneList .col.right a.add').live('click',function(e){
      $('#MilestoneList').hide();
      $('#MilestoneAdd').show();
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
  },
  
  
  initMoveProject: function(id)
  {
    var obj = $('div[rel=edit-milestone-'+id+']');
    
    $(obj).find('.move-project-link').bind('click',function(){
    
      var self = this;
    
      $(obj).find('.move-project-form').css({
        left:$(self).offset().left-300,
      }).show();
      
      return false;
    });
    
    $(obj).find('.move-project-form form').ajaxSubmit();

    $(obj).find('.move-project-form div.submit a').bind('click',function(){
      $(obj).find('.move-project-form').hide();
      return false;
    });
    
    $(obj).find('.move-project-form select').bind('change',function(){
      if($(this).val())
      { 
        $(obj).find('.move-project-form div.submit input').removeAttr('disabled');
      }
      else
      {
        $(obj).find('.move-project-form div.submit input').attr('disabled',true);
      }
    });
  
    if($(obj).find('.move-project-form select').val())
    { 
      $(obj).find('.move-project-form div.submit input').removeAttr('disabled');
    }
    else
    {
      $(obj).find('.move-project-form div.submit input').attr('disabled',true);
    }
  }
  

}


$(document).ready(function() {
  Milestones.load();
});

