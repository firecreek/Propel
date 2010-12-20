<?php
  echo $form->create('Milestones',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
  echo $form->input('responsible',array('label'=>__('Show milestones assigned to',true).':','options'=>$responsibleOptions,'selected'=>$responsible));
  echo $form->submit(__('Search',true));
  echo $form->end();
?>
