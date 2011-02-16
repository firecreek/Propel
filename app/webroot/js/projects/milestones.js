
var Milestones = {

  refreshUrl:'',
  refreshTimer:'',

  load: function()
  {
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

