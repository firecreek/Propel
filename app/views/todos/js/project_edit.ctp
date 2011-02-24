<?php

  $after = false;

  if(!empty($item['Todo']['description']))
  {
    $after = nl2br($item['Todo']['description']);
  }
  
  //List item
  $listHtml = $listable->item('Todo',$item['Todo']['id'],$item['Todo']['name'],array(
    'checkbox'      => false,
    'comments'      => false,
    'position'      => true,
    'positionHide'  => true,
    'url'           => $html->url(array('controller'=>'todos','action'=>'view',$item['Todo']['id'])),
    'editUrl'       => $html->url(array('controller'=>'todos','action'=>'edit',$item['Todo']['id'])),
    'deleteUrl'     => $html->url(array('controller'=>'todos','action'=>'delete',$item['Todo']['id'])),
    'after'         => $after
  ));
  $listHtml = $javascript->escapeString($listHtml);

  //Description
  $description = $javascript->escapeString( $this->element('todos/list_description',array('record'=>$item)) );
  
?>

//
var originalObj = $('#<?php echo $this->data['Todo']['ident']; ?>');
var group = $(originalObj).closest('.group');

//Append the item
$(originalObj).after('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Update description
$('#<?php echo $listable->lastIdent; ?>').closest('.group').find('.list-description').html('<?php echo $description; ?>');

//Delete original object
$(originalObj).remove();

//
$(group).removeClass('ui-state-edit');

//Make listable
$('.listable').data('listable').reset();

