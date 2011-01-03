<?php
  $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
  
  if(!isset($submitText))
  {
    $submitText = __('Create this milestone',true);
  }
  
?>


<?php
  echo $form->create('Milestone',array('url'=>$this->here,'class'=>'block'));
  
  echo $form->input('deadline',array('label'=>__('When is it due?',true)));
  echo $form->input('title',array('label'=>__('Enter a title',true),'after'=>'<small>'.__('(e.g. Design review 3)',true).'</small>'));
  echo $form->input('responsible',array('options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
  echo $form->input('email',array('label'=>__('Email 48 hours before it\'s due',true)));
?>
  
<hr />
  
<?php
  echo $form->submit($submitText,array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
  echo $form->end();
?>
