
$('#<?php echo $this->params['form']['objId']; ?>').fadeOut();


var group = $('#<?php echo $this->params['form']['objId']; ?>').closest('.group');

//Update completed count
<?php echo $this->element('todos/js_update_count',array('name'=>'group','count'=>$item['Todo']['todo_items_completed_count'])); ?>

//Make listable
$('.listable').data('listable').reset();
