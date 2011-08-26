<?php if(isset($this->data['Select']['id'])): ?>

  //Get add option then remove it
  var addOption = $('#PostCategoryId option[value=_add]');
  addOption.remove();

  //Add new
  $('#<?php echo $this->data['Select']['id']; ?>')
    .append(
      $("<option></option>")
        .attr("value",<?php echo $id; ?>)
        .text("<?php echo $record['Category']['name']; ?>"));
        
  //Add in new option
  $('#<?php echo $this->data['Select']['id']; ?>').append(addOption);
  
  //Select added record
  $('#PostCategoryId option[value=<?php echo $id; ?>]').attr('selected', 'selected');

<?php else: ?>

  <?php

    $listOptions = array();

    if(isset($this->data['Filter']['url']))
    {
      $listOptions['url'] = $this->data['Filter']['url'].'/category:'.$record['Category']['id'];
    }
  
    $listHtml = $listable->item('Category',$record['Category']['id'],$record['Category']['name'],array_merge(array(
      'controls' => array(
        'edit' => array(
          'url' => $html->url(array('controller'=>'categories','action'=>'edit',$record['Category']['id']))
        ),
        'delete' => array(
          'url' => $html->url(array('controller'=>'categories','action'=>'delete',$record['Category']['id']))
        )
      )
    ),$listOptions));
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
  
<?php endif; ?>
