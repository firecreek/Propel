<?php

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

?>
