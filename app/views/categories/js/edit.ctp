<?php

  $url = $this->Html->url(array('action'=>'index','category'=>$record['Category']['id']));
  
  $listHtml = $listable->item('Category',$record['Category']['id'],$record['Category']['name'],array(
    'controls' => array(
      'edit' => array(
        'url' => $html->url(array('controller'=>'categories','action'=>'edit',$record['Category']['id']))
      ),
      'delete' => array(
        'url' => $html->url(array('controller'=>'categories','action'=>'delete',$record['Category']['id']))
      )
    ),
    'url' => $url
  ));
  $listHtml = $javascript->escapeString($listHtml);

?>

//
var originalObj = $('#Category<?php echo $id; ?>');

//Append the item
$(originalObj).after('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Delete original object
$(originalObj).remove();

//Make listable
$('.listable').data('listable').reset();
