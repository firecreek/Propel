
<h2>
  <?php __('Comments on this Milestone'); ?>
  <?php
    echo $html->link('('.__('Back to all Milestones',true).')',array('controller'=>'milestones','action'=>'index'));
  ?>
</h2>

<div class="record">
  <div class="wrapper listable"><?php
    echo $listable->item($modelAlias,$id,$record[$modelAlias]['title'],array(
      'delete'    => false,
      'edit'      => false,
      'comments'  => false,
      'prefix'    => isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null,
      'updateUrl' => $html->url(array('controller'=>'milestones','action'=>'update',$id))
    ));
  ?></div>
</div>
<?php
  echo $javascript->codeBlock("
    $('.listable .item').listable();
  ");
?>
