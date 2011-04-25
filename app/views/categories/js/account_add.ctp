<?php
  $listHtml = $listable->item('Category',$record['Category']['id'],$record['Category']['name'],array(
    'position'  => false,
    'checkbox'  => false,
    'comments'  => false,
    'editUrl'   => $html->url(array('controller'=>'categories','action'=>'edit',$record['Category']['id'])),
    'deleteUrl' => $html->url(array('controller'=>'categories','action'=>'delete',$record['Category']['id'])),
  ));
  $listHtml = $javascript->escapeString($listHtml);

  $typeHuman = Inflector::humanize($type);
?>


//Append the item
$('#Categories<?php echo $typeHuman; ?> .items').append('<?php echo $listHtml; ?>');

//Highlight this item
$('#<?php echo $listable->lastIdent; ?> .name').effect('highlight',null,3000);

//Empty text area
$('#Categories<?php echo $typeHuman; ?> .category-add input[type=text]').val('');

//Remove loading
$('#Categories<?php echo $typeHuman; ?> .category-add .saving').remove();

//Item add reset
if($("*:focus").attr('type') != 'input')
{
  $('#Categories<?php echo $typeHuman; ?> .category-add input[type=text]').focus();
}

//Reset listable
$('.listable').data('listable').reset();
