<?php

  $listHtml = $listable->item('Category',$record['Category']['id'],$record['Category']['name'],array(
    'position'  => false,
    'checkbox'  => false,
    'comments'  => false,
    'editUrl'   => $html->url(array('controller'=>'categories','action'=>'edit',$record['Category']['id'])),
    'deleteUrl' => $html->url(array('controller'=>'categories','action'=>'delete',$record['Category']['id'])),
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
