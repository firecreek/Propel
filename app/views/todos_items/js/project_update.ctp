<?php
  $referer = Controller::referer();
  if(strpos($referer,'/comments/') !== false):
?>

  <?php
    $listHtml = $javascript->escapeString($this->element('todos_items/comments_record'));
  ?>
  var originalObj = $('#<?php echo $this->params['url']['objId']; ?>');

  //Append the item
  $(originalObj).after('<?php echo $listHtml; ?>');

  //Delete original object
  $(originalObj).remove();

  //Make listable
  $('.listable').data('listable').reset();


<?php else: ?>


  <?php
    //From normal list
    $extra = array();

    if($completed == 'true')
    {
      //Into recently completed
      $listHtml = $listable->item('Todo',$record['TodoItem']['id'],$record['TodoItem']['description'],array(
        'edit' => false,
        'checked' => true,
        'prefix' => date('M j',strtotime($record['TodoItem']['completed_date'])),
        'updateUrl'   => $html->url(array('controller'=>'todos_items','action'=>'update',$record['TodoItem']['id'])),
        'deleteUrl' => $html->url(array('controller'=>'todos_items','action'=>'delete',$record['TodoItem']['id'])),
      ));
    }
    else
    {
      //Back into main list
      $listHtml = $listable->item('Todo',$record['TodoItem']['id'],$record['TodoItem']['description'],array(
        'position'  => true,
        'extra'     => $extra,
        'editUrl'   => $html->url(array('controller'=>'todos_items','action'=>'edit',$record['TodoItem']['id'])),
        'updateUrl' => $html->url(array('controller'=>'todos_items','action'=>'update',$record['TodoItem']['id'])),
        'deleteUrl' => $html->url(array('controller'=>'todos_items','action'=>'delete',$record['TodoItem']['id'])),
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
  <?php echo $this->element('todos/js_update_count',array('name'=>'group','count'=>$record['Todo']['todo_items_completed_count'])); ?>

  //Make listable
  $('.listable').data('listable').reset();

<?php endif; ?>
