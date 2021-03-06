<?php

  if(!isset($edit)) { $edit = false; }
  if(!isset($class)) { $class = null; }
  
  $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
  
  $ident = md5(microtime());

?>

<div class="item-add" id="TodoAdd<?php echo $ident; ?>">
  <?php
  
    $url = array('controller'=>'todos_items','action'=>'add',$todoId);
  
    if($edit)
    {
      $url = array('controller'=>'todos_items','action'=>'edit',$id);
    }
  
    echo $form->create('TodoItem',array('id'=>false,'url'=>$url,'class'=>$class));
    echo $form->input('description',array('div'=>'input textarea description','type'=>'textarea','id'=>false,'label'=>__('Enter a to-do item',true)));
  ?>
  <div class="options">
    <div class="fields">
      <?php
        echo $form->input('responsible',array('id'=>false,'div'=>'input first','options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
        echo $form->input('deadline',array(
          'empty'=>true,
          'type'=>'date',
          'div'=>'input second',
          'label'=>__('When is it due?',true),
          'minYear' => date('Y')-2,
          'maxYear' => date('Y')+10,
        ));
      ?>
    </div>
    <div class="notify" style="display:none;">
      <?php
        echo $this->Form->input('TodoItem.notify',array(
          'type'              => 'checkbox',
          'label'             => '',
          'checked'           => true,
          'rel-label-text'    => __('Notify %s via email?',true)
        ));
      ?>
    </div>
    
    <?php
      echo $this->Javascript->codeBlock("
        Todos._notifyCheck($('#TodoAdd".$ident." .fields select[name*=responsible]'));
      ");
    ?>
  </div>
  <hr />
  <?php
  
    $submitText = __('Add this item',true);

    if(!isset($cancelText))
    {
      $cancelText = __('I\'m done adding items',true);
    }
    
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
  
    echo $form->submit($submitText,array('after'=>__('or',true).' '.$html->link($cancelText,array('controller'=>'todos','action'=>'index') ) ));
    echo $form->end();
  ?>
</div>
