<?php

  $extra = array();

  if($completed == 'true')
  {
    //Into recently completed
    $listHtml = $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
      'edit' => false,
      'checked' => true,
      'prefix' => date('M j',strtotime($item['TodoItem']['completed_date'])),
      'updateUrl'   => $html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id'])),
      'deleteUrl' => $html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id'])),
    ));
  }
  else
  {
    //Back into main list
    $listHtml = $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
      'position'  => true,
      'extra'     => $extra,
      'editUrl'   => $html->url(array('controller'=>'todos_items','action'=>'edit',$item['TodoItem']['id'])),
      'updateUrl' => $html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id'])),
      'deleteUrl' => $html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id'])),
      'highlight' => true
    ));
  }
  
  $listHtml = $javascript->escapeString($listHtml);

?>

//
var originalObj = $('#<?php echo $this->params['url']['objId']; ?>');
var group = $(originalObj).closest('.group');
var recent = $(group).find('.recent');

//Append the item
<?php if($completed == 'true'): ?>
  $(group).find('.recent').prepend('<?php echo $listHtml; ?>');
<?php else: ?>
  $(group).find('.items').append('<?php echo $listHtml; ?>');
<?php endif; ?>

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Delete original object
$(originalObj).remove();

//Update completed count
<?php echo $this->element('todos/js_update_count',array('name'=>'group','count'=>$item['Todo']['todo_items_completed_count'])); ?>


//Make listable
$('.listable').data('listable').reset();

