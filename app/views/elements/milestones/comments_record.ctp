<?php
  $dateFormat = 'l, j F';
  $class = null;
?>

<div id="milestoneStatus">
  <?php if($record['Milestone']['completed']): ?>
    <?php $class = 'completed'; ?>
    <p class="completed"><strong><?php echo __('Completed:',true) ?></strong> <?php echo date($dateFormat,strtotime($record['Milestone']['completed_date'])) ?></strong>
  <?php elseif(strtotime($record['Milestone']['deadline']) < time()): ?>
    <p class="overdue"><strong><?php echo __('Overdue:',true) ?></strong> <?php echo date($dateFormat,strtotime($record['Milestone']['deadline'])) ?></strong>
  <?php else: ?>
    <p class="upcoming"><strong><?php echo __('Upcoming:',true) ?></strong> <?php echo date($dateFormat,strtotime($record['Milestone']['deadline'])) ?></strong>
  <?php endif; ?>
</div>

<?php

  echo $listable->item('Milestone',$id,$record['Milestone']['title'],array(
    'delete'    => false,
    'edit'      => false,
    'comments'  => false,
    'class'     => $class,
    'checked'   => $record['Milestone']['completed'] ? true : false,
    'prefix'    => isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null,
    'updateUrl' => $html->url(array('controller'=>'milestones','action'=>'update',$id))
  ));

?>

