
<div class="box">

  <div class="banner">
    <h2><?php echo __('Sign in',true); ?></h2>
  </div>
  
  <div class="content">
    <?php
      echo $session->flash('auth');
    
      echo $form->create('User',array('url'=>$this->here));
      
      echo $form->input('username',array('label'=>__('Username',true)));
      echo $form->input('password',array('label'=>__('Password',true)));
      
      echo $form->button(__('Sign in',true),array('class'=>'button large','type'=>'submit'));
      
      echo $form->end();
    ?>
  </div>

</div>
