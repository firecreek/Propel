
<div class="box small">
  
  <div class="content">
    
    <h2><?php __('Reset your password'); ?></h2>
    <p class="intro"><?php __('Please use the form below to set a new password.'); ?></p>

    <?php
      
      echo $form->create('User',array('url'=>$this->here,'class'=>'block strong'));
  
      echo $form->input('username',array('label'=>__('Your username',true)));
      
      echo $form->input('password',array('label'=>__('Choose a new password',true),'after'=>'<small>'.__('6 characters or longer with at least one number is safest.',true).'</small>'));
      echo $form->input('password_confirm',array('type'=>'password','label'=>__('Enter your password again for verification',true)));
      
      echo $form->button(__('Reset my password',true),array('type'=>'submit'));
      
      echo $form->end();
    ?>      
      
  </div>

</div>
