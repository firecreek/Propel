<?php
  $this->set('activeMenu','todos');
?>
<div class="banner">
  <h2>
    <?php __('Comments on this To-Do Item'); ?>
    <?php
      echo $html->link(__('(Back to all To-Dos)',true),array('controller'=>'todos','action'=>'index'));
    ?>
  </h2>

  <div class="record">
    <div class="wrapper listable"><?php
      echo $listable->item($modelAlias,$id,$record[$modelAlias]['description'],array(
        'delete'    => false,
        'edit'      => false,
        'comments'  => false,
        'prefix'    => isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null,
        'updateUrl' => $html->url(array('controller'=>'todos_items','action'=>'update',$id))
      ));
    ?></div>
  </div>
  <?php
    echo $javascript->codeBlock("
      $('.listable .item').listable();
    ");
  ?>
</div>
