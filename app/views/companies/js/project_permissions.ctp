<?php
  $personId = $this->params['form']['personId'];
?>

<?php if($this->data['Action'] == 'grant'): ?>

  <!-- do nothing -->

<?php elseif($this->data['Action'] == 'add'): ?>

  $('tr[rel-person-id=<?php echo $personId; ?>] .permission-options').show();
  $('tr[rel-person-id=<?php echo $personId; ?>] input[value=3]:radio').attr('checked',true);

<?php elseif($this->data['Action'] == 'remove'): ?>

  $('tr[rel-person-id=<?php echo $personId; ?>] .permission-options').hide();

<?php endif; ?>

$('tr[rel-person-id=<?php echo $personId; ?>]').effect('highlight',null,3000);
