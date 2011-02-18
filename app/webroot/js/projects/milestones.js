
var Milestones = {

  refreshUrl:'',
  refreshTimer:'',

  load: function()
  {
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

