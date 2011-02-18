<?php
  $referer = Controller::referer();
  if(strpos($referer,'/comments/') !== false):
?>

  <?php
    $listHtml = $javascript->escapeString($this->element('milestones/comments_record'));
  ?>
  var originalObj = $('#<?php echo $this->params['url']['objId']; ?>');
  
  //Delete previous status
  $('#milestoneStatus').remove();

  //Append the item
  $(originalObj).after('<?php echo $listHtml; ?>');

  //Delete original object
  $(originalObj).remove();

  //Make listable
  $('.listable').data('listable').reset();

<?php else: ?>

  Milestones.refresh();

<?php endif; ?>
