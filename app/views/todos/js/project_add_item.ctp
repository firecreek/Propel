<?php

  $extra = array();

  $listHtml = $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
    'position'  => true,
    'extra'     => $extra,
    'editUrl'   => $html->url(array('controller'=>'todos','action'=>'edit_item',$item['Todo']['id'],$item['TodoItem']['id'])),
    'updateUrl' => $html->url(array('controller'=>'todos','action'=>'update_item',$item['Todo']['id'],$item['TodoItem']['id'])),
    'deleteUrl' => $html->url(array('controller'=>'todos','action'=>'delete_item',$item['Todo']['id'],$item['TodoItem']['id'])),
    'highlight' => true
  ));
  
  $listHtml = $javascript->escapeString($listHtml);

?>


//Append the item
$('#<?php echo $this->data['Group']['ident']; ?> .items').append('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Item add reset
$('#<?php echo $this->data['Group']['ident']; ?> .item-add textarea').val('').focus();

//Reset listable
$('.listable').data('listable').reset();
