
<h2>Add Grant</h2>

<?php
  echo $this->Form->create('Grant');
  echo $this->Form->input('alias');
  echo $this->Form->input('name');
  echo $this->Form->input('model',array('options'=>array(
    'Account' => 'Account',
    'Project' => 'Project',
  )));
  echo $this->Form->submit('Add Grant');
  echo $this->Form->end();
?>