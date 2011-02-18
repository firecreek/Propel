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
    'editUrl'   => $html->url(array('controller'=>'todos_items','action'=>'edit',$item['TodoItem']['id'])),
    'updateUrl' => $html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id'])),
    'deleteUrl' => $html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id'])),
    'highlight' => true
  ));
  
  $listHtml = $javascript->escapeString($listHtml);

?>


//Append the item
$('#<?php echo $this->data['Group']['ident']; ?> .items').append('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Empty text area
$('#<?php echo $this->data['Group']['ident']; ?> .item-add textarea').val('')

//Item add reset
if($("*:focus").attr('type') != 'textarea')
{
  $('#<?php echo $this->data['Group']['ident']; ?> .item-add textarea').focus();
}

//Reset listable
$('.listable').data('listable').reset();
