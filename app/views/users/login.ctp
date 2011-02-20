<?php
  $html->css('login', null, array('inline'=>false));
?>

<div class="box">

  <div class="banner">
    <?php if(isset($account) && !empty($account)): ?>
      <h2><?php echo $account['Account']['name']; ?></h2>
    <?php else: ?>
      <h2><?php echo __('Sign in',true); ?></h2>
    <?php endif; ?>
  </div>
  
  <div class="content">
    <?php
      echo $session->flash();
      echo $session->flash('auth');
    
      echo $form->create('User',array('url'=>$this->here,'class'=>'block strong'));
      
      echo $form->input('username',array('label'=>__('Username',true)));
      echo $form->input('password',array('label'=>__('Password',true)));
      
      echo $form->button(__('Sign in',true),array('class'=>'button large','type'=>'submit'));
      
      echo $form->end();
    ?>
    <hr class="dashed" />
    
    <p><strong><?php __('Help'); ?>:</strong> <?php echo $html->link(__('I forgot my username or password',true),array('accountSlug'=>false,'action'=>'forgotten')); ?></p>
    
  </div>

</div>
