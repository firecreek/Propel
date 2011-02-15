<?php

  $listHtml = $listable->item('Todo',$item['Todo']['id'],$item['Todo']['name'],array(
    'checkbox'      => false,
    'comments'      => false,
    'position'      => true,
    'positionHide'  => true,
    'url'           => $html->url(array('controller'=>'todos','action'=>'view',$item['Todo']['id'])),
    'editUrl'       => $html->url(array('controller'=>'todos','action'=>'edit',$item['Todo']['id'])),
    'deleteUrl'     => $html->url(array('controller'=>'todos','action'=>'delete',$item['Todo']['id'])),
  ));
  
  $listHtml = $javascript->escapeString($listHtml);

?>

//
var originalObj = $('#<?php echo $this->data['Todo']['ident']; ?>');

//Append the item
$(originalObj).after('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Delete original object
$(originalObj).remove();

//Make listable
$('.listable').data('listable').reset();

