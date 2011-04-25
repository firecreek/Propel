
<h2><?php __('User permissions (ARO)'); ?></h2>

<?php
  echo $this->Form->create('User',array('url'=>$this->here));
  echo $this->Form->input('id',array('type'=>'text','label'=>__('Enter User ID to update',true)));
  echo $this->Form->submit('Update');
  echo $this->Form->end();
?>
