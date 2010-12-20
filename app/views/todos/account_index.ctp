
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s to-do items across all projects',$name.'\'s')); ?></h2>
    <?php
      echo $form->create('Todos',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
      echo $form->input('responsible',array('label'=>__('Show items for',true).':','options'=>$responsibleOptions,'selected'=>$responsible));
      echo $form->input('due',array('label'=>__('due',true).':','options'=>$dueOptions,'selected'=>$due));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>
  </div>
  <div class="content">
  
    <?php echo $session->flash(); ?>
  
    <h3><?php __(sprintf('There are no to-dos assigned to %s.', ($responsibleSelf ? 'You' : $name) )); ?></h3>
    <p><?php __('Choose another person with the pulldown to the right.'); ?></p>
    
  </div>
</div>
