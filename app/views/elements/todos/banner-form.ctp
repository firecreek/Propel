<?php
  echo $form->create('Todos',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
  echo $form->input('responsible',array('label'=>__('Show items for',true).':','options'=>$responsibleOptions,'selected'=>$responsible));
  echo $form->input('due',array('label'=>__('due',true).':','options'=>$dueOptions,'selected'=>$due));
  echo $form->submit(__('Search',true));
  echo $form->end();
?>
