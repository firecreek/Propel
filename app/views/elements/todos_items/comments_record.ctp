<?php
  $prefix = '';
  $class = null;
  
  if($record['TodoItem']['completed'])
  {
    $prefix = date('M j',strtotime($record['TodoItem']['completed_date']));
    $class = 'completed';
  }
  
  echo $listable->item('TodosItem',$id,$record['TodoItem']['description'],array(
    'checkbox' => array(
      'enabled' => true,
      'url'     => $html->url(array('controller'=>'todos_items','action'=>'update',$id)),
      'checked' => $record['TodoItem']['completed'] ? true : false
    ),
    'class'     => $class,
    'prefix'    => $prefix
  ));
?>