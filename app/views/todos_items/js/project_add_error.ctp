
//Remove loading
$('#<?php echo $this->data['Group']['ident']; ?> .item-add .saving').remove();

//Item add reset
if($("*:focus").attr('type') != 'textarea')
{
  $('#<?php echo $this->data['Group']['ident']; ?> .item-add textarea').focus();
}
