
$('#<?php echo $this->params['url']['objId']; ?>').fadeOut(400, function(){
  $(this).remove();
  Milestones.checkSections();
});
