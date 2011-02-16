<?php

  //Extras
  //@todo Clean extras up
  $extras = array();
  if(isset($item['Responsible']) && !empty($item['Responsible']))
  {
    $extras[] = $item['Responsible']['name'];
  }
  if(!empty($item['TodoItem']['deadline']))
  {
    $extras[] = date('j M Y',strtotime($item['TodoItem']['deadline']));
  }
  $extra = implode(' ',$extras);
  

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

//
var originalObj = $('#<?php echo $this->data['TodoItem']['ident']; ?>');

//Append the item
$(originalObj).after('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Delete original object
$(originalObj).remove();

//Make listable
$('.listable').data('listable').reset();

