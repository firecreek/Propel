<?php
  $html->css('pages/invitation', null, array('inline'=>false));
?>

<div class="box">
  
  <div class="content">
  
    <?php if($type == 'new'): ?>
    
      <h2><?php __('Hi, '); ?> <?php echo $record['Person']['first_name']; ?>.</h2>
      <p class="intro"><?php echo $record['PersonInvitee']['full_name']; ?> <?php __('just set up an account for you on Propel, our project management and collaboration system. All you need to do is choose a username and password.'); ?></p>
    
      <div class="cols clearfix">
        <div class="col left">
          <?php
            
            echo $form->create('User',array('url'=>$this->here,'class'=>'block strong'));
            
            echo $form->input('username',array(
              'label' => __('Choose a username',true),
              'after' => '<small>'.__('This is what you’ll use to sign in',true).'</small>',true)
            );
            
            echo $form->input('password',array('label'=>__('Pick a password',true),'after'=>'<small>'.__('6 characters or longer with at least one number is safest.',true).'</small>'));
            echo $form->input('password_confirm',array('type'=>'password','label'=>__('Enter the password again',true)));
            
            echo $form->button(__('Create your account',true),array('type'=>'submit'));
            
            echo $form->end();
          ?>
        </div>
        <div class="col right">
          <h3><?php __('Already have an account?'); ?></h3>
          <p><?php echo $html->link(__('Sign in',true),array('action'=>'invitation',$code,'existing')); ?> <?php __('with the username you already have.'); ?></p>
        </div>
      </div>
      
      
    <?php else: ?>
    
      <h2><?php __('Sign in with the username you already have'); ?></h2>
      <p class="intro"><?php __('Sign in with your existing Propel username and password instead of creating a new one for this Propel account.'); ?></p>
      
      <?php
        echo $form->create('User',array('url'=>$this->here,'class'=>'strong'));
        
        echo $form->hidden('Person.code',array('value'=>$code));
        
        echo $form->input('username',array('label'=>__('Username',true)));
        echo $form->input('password',array('label'=>__('Password',true)));
        
        echo $form->submit(__('Sign in',true),array('after'=>__('or',true).' '.$html->link(__('Go back',true),array('action'=>'invitation',$code),array('class'=>'unimportant') ) ));
        
        echo $form->end();
      ?>
    
    <?php endif; ?>
        
  </div>

</div>
