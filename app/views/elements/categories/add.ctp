<?php

  if(!isset($edit)) { $edit = false; }

?>
<div class="category-add">
  <?php
  
    if($edit)
    {
      $url = array('controller'=>'categories','action'=>'edit',$id);
    }
    else
    {
      $url = array('controller'=>'categories','action'=>'add',$type);
    }
  
    echo $this->Form->create('Category',array('url'=>$url));
    echo $this->Form->input('name',array('label'=>false));
    
    if(isset($filter) && !empty($filter))
    {
      echo $this->Form->hidden('Filter.url',array('value'=>$this->Html->url($filter)));
    }
  ?>
  
  <hr />
  
  <?php
  
    $submitText = __('Add this category',true);
    $cancelText = __('I\'m done adding categories',true);
    
    if($edit)
    {
      $submitText = __('Save changes',true);
      $cancelText = __('Cancel',true);
    }
  
    echo $form->submit($submitText,array('after'=>__('or',true).' '.$html->link($cancelText,array('controller'=>'settings','action'=>'categories') ) ));
    echo $form->end();
  ?>
</div>
