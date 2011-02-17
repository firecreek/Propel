<?php

  if(!isset($edit)) { $edit = false; }
  
  $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));

?>

<div class="item-add">
  <?php
  
    $url = array('controller'=>'todos_items','action'=>'add',$todoId);
  
    if($edit)
    {
      $url = array('controller'=>'todos_items','action'=>'edit',$id);
    }
  
    echo $form->create('TodoItem',array('id'=>false,'url'=>$url));
    echo $form->input('description',array('div'=>'input textarea description','id'=>false,'label'=>__('Enter a to-do item',true)));
  ?>
  <div class="options">
    <?php
      echo $form->input('responsible',array('id'=>false,'div'=>'input first','options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
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
    
    if(isset($groupIdent) && !empty($groupIdent))
    {
      echo $form->hidden('Group.ident',array('value'=>$groupIdent));
    }
    
    if(isset($todoItemIdent) && !empty($todoItemIdent))
    {
      echo $form->hidden('TodoItem.ident',array('value'=>$todoItemIdent));
    }
  
    echo $form->submit($submitText,array('after'=>__('or',true).' '.$html->link($cancelText,array('action'=>'index') ) ));
    echo $form->end();
  ?>
</div>
