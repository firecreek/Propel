
<div class="box small">
  
  <div class="content">
    
    <h2><?php __('Can\'t sign in? Forget your password?'); ?></h2>
    <p class="intro"><?php __('Enter your email address below and we\'ll send you password reset instructions.'); ?></p>

    <?php
      
      echo $form->create('User',array('url'=>$this->here,'class'=>'block strong'));
  
      echo $form->input('email',array('label'=>__('Enter your email address',true)));
      
      echo $form->button(__('Send me reset instructions',true),array('type'=>'submit'));
      
      echo $form->end();
    ?>
    
    <hr class="dashed" />
    
    <p><?php __('Nevermind'); ?>, <?php echo $html->link(__('send me back to the sign in screen',true),array('action'=>'login')); ?></p>
    
      
      
  </div>

</div>
