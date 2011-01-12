
<div class="box">
  <div class="banner">
    <h2><?php __('Edit to-do list'); ?></h2>
  </div>
  <div class="content">
    
    <?php
      echo $session->flash();
    ?>
    
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
    ?>
    
    <?php
      echo $form->create('Todo',array('url'=>$this->here,'class'=>'block'));
      
      echo $form->input('name',array('label'=>__('First give the list a name',true)));
    ?>
      
    <hr />
      
    <?php
      echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
      echo $form->end();
    ?>

    
  </div>
</div>
