<?php
  $responsibleOptions = $layout->permissionList($auth->read('People'));
  
  echo $form->create('Todo',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
  echo $form->input('responsible',array('label'=>__('Show items for',true).':','options'=>$responsibleOptions));
  echo $form->input('due',array('label'=>__('due',true).':','options'=>$dueOptions));
  echo $form->submit(__('Search',true));
  echo $form->end();
?>
