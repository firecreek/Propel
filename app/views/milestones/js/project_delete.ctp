
$('#<?php echo $this->params['form']['objId']; ?>').fadeOut(400, function(){
  $(this).remove();
  Milestones.checkSections();
});
