
<?php if($this->data['Action'] == 'grant'): ?>

  <!-- do nothing -->

<?php elseif($this->data['Action'] == 'add'): ?>

  $('tr[rel-person-id=<?php echo $this->params['form']['personId']; ?>] .permission-options').show();
  $('tr[rel-person-id=<?php echo $this->params['form']['personId']; ?>] input[value=3]:radio').attr('checked',true);

<?php elseif($this->data['Action'] == 'remove'): ?>

  $('tr[rel-person-id=<?php echo $this->params['form']['personId']; ?>] .permission-options').hide();

<?php endif; ?>

$('tr[rel-person-id=<?php echo $this->params['form']['personId']; ?>]').effect('highlight',null,3000);
