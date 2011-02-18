<?php
  $this->set('activeMenu','todos');
  $html->css('projects/todos', null, array('inline'=>false));
?>
<div class="banner todositems-comment-head">
  <h2>
    <?php __('Comments on this To-Do Item'); ?>
    <?php
      echo $html->link(__('(Back to all To-Dos)',true),array('controller'=>'todos','action'=>'index'));
    ?>
  </h2>

  <div class="record">
    <div class="wrapper listable"><?php
      echo $this->element('todos_items/comments_record');
    ?></div>
  </div>
</div>

<?php
  echo $javascript->codeBlock("
    $('.listable').listable();
  ");
?>
