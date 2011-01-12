<?php

  if(!isset($edit)) { $edit = false; }
  
  $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));

?>

<div class="item-add">
  <?php
  
    $url = array('controller'=>'todos','action'=>'add_item',$todoId);
  
    if($edit)
    {
      $url = array('controller'=>'todos','action'=>'edit_item',$todoId,$id);
    }
  
    echo $form->create('TodoItem',array('id'=>null,'url'=>$url));
    echo $form->input('description',array('div'=>'input textarea description','id'=>null,'label'=>__('Enter a to-do item',true)));
  ?>
  <div class="options">
    <?php
      echo $form->input('responsible',array('div'=>'input first','options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
      echo $form->input('deadline',array('empty'=>true,'div'=>'input second','label'=>__('When is it due?',true)));
    ?>
  </div>
  <hr />
  <?php
  
    $submitText = __('Add this item',true);
    $cancelText = __('I\'m done adding items',true);
      
    if($edit)
    {
      $submitText = __('Save this item',true);
      $cancelText = __('Cancel',true);
    }
  
    echo $form->submit($submitText,array('after'=>__('or',true).' '.$html->link($cancelText,array('action'=>'index') ) ));
    echo $form->end();
  ?>
</div>
