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

  $listHtml = $listable->item('TodosItem',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
    'controls' => array(
      'edit'      => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'edit',$item['TodoItem']['id']))),
      'position'  => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id']))),
      'delete'    => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id']))),
    ),
    'comments' => array(
      'enabled'     => true,
      'controller'  => 'todos_items'
    ),
    'checkbox' => array(
      'enabled' => true,
      'checked' => false,
      'url'     => $this->Html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id']))
    ),
    'extra'     => $extra
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

