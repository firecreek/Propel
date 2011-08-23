
<h2>Edit Grant</h2>

<?php
  echo $this->Form->create('Grant');
  echo $this->Form->hidden('id');
  echo $this->Form->input('name');
  echo $this->Form->input('alias');
  echo $this->Form->input('position');
  echo $this->Form->input('model',array('options'=>array(
    'Account' => 'Account',
    'Project' => 'Project',
  )));
  echo $this->Form->submit('Save Changes');
  echo $this->Form->end();
?>