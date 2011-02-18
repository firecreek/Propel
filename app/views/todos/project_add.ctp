<?php
  $javascript->link('projects/todos_add.js', false);
  $html->css('projects/todos', null, array('inline'=>false));
?>

<div id="TodoAdd">
  <?php
    echo $this->element('todos/add');
  ?>
</div>
