
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s to-do items across all projects',$responsible['name'].'\'s')); ?></h2>
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Todo',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
      echo $form->input('responsible',array('label'=>__('Show items for',true).':','options'=>$responsibleOptions));
      echo $form->input('due',array('label'=>__('due',true).':','options'=>$dueOptions));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>

  </div>
  <div class="content">
  
    <?php echo $session->flash(); ?>
  
    <h3><?php
      echo __(sprintf('There are no to-dos assigned to %s.',$responsible['name']), true);
    ?></h3>
    <p><?php __('Choose another person with the pulldown to the right.'); ?></p>
    
  </div>
</div>
