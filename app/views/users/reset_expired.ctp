
<div class="box small">
  
  <div class="content">
    
    <h2><?php __('This reset password page has expired'); ?></h2>
    
    <p><?php __('For security reasons, reset password pages stop working after you use them to reset a password.'); ?></p>

    <p><?php echo sprintf(__('If you\'re trying to reset your password, please %s and click the "forgot password" link to reset it again.',true),$this->Html->link(__('go back to the login screen',true),array('action'=>'login'))); ?></p>
    
      
  </div>

</div>
