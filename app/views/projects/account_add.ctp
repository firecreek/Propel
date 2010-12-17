
<div class="box">
  <div class="banner">
    <h2><?php __('Create new project'); ?></h2>
  </div>
  <div class="content">
    <?php
      echo $session->flash();
    
      echo $form->create('Project',array('url'=>$this->here,'class'=>'block'));
      
      echo $form->input('name',array('label'=>__('Name of project',true)));
      
      echo $form->input('description',array('label'=>__('Description',true),'after'=>'<small>'.__('(Not required)',true).'</small>'));
      
      echo $form->submit(__('Create project',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'accounts','action'=>'index') ) ));
      
      echo $form->end();
    ?>
  </div>
</div>
