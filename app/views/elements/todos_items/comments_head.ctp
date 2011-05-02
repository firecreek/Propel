<?php
  $this->set('activeMenu','todos');
  $html->css('projects/todos', null, array('inline'=>false));
  
  $subscribe = true;
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
      $prefix = '';
      $class = null;
      
      if($record['TodoItem']['completed'])
      {
        $prefix = date('M j',strtotime($record['TodoItem']['completed_date']));
        $class = 'completed';
      }
      
      echo $listable->item('TodoItem',$id,$record['TodoItem']['description'],array(
        'delete'    => false,
        'edit'      => false,
        'comments'  => false,
        'class'     => $class,
        'checked'   => $record['TodoItem']['completed'] ? true : false,
        'prefix'    => $prefix,
        'updateUrl' => $html->url(array('controller'=>'todos_items','action'=>'update',$id))
      ));
    ?></div>
  </div>
</div>

<?php
  echo $javascript->codeBlock("
    $('.listable').listable();
  ");
?>
