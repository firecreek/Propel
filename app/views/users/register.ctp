
<div class="box">

  <div class="banner">
    <h2><?php echo __('Register Account',true); ?></h2>
  </div>
  
  <div class="content">
    <?php
    
      echo $session->flash();
      
      echo $form->create('User',array('url'=>$this->here));
      
      echo '<fieldset><legend>'.__('Create your OpenCamp account',true).'</legend>';
      echo $form->input('Person.first_name',array('label'=>__('First name',true)));
      echo $form->input('Person.last_name',array('label'=>__('Last name',true)));
      echo $form->input('User.email',array('label'=>__('Email',true)));
      echo $form->input('Company.name',array('label'=>__('Company',true),'after'=>'<small>'.__('(Or non-profit, organization, group, school, etc.)',true).'</small>'));
      echo '</fieldset>';
      
      echo '<fieldset class="block"><legend>'.__('Now choose a username &amp; password',true).'</legend>';
      echo $form->input('User.username',array('label'=>__('Username',true),'after'=>'<small>'.__('This is what you’ll use to sign in').'</small>',true));
      echo $form->input('User.password',array('label'=>__('Password',true),'after'=>'<small>'.__('6 characters or longer with at least one number is safest.',true).'</small>'));
      echo $form->input('User.password_confirm',array('type'=>'password','label'=>__('Enter your password again for verification',true)));
      echo '</fieldset>';
      
      echo $form->button(__('Create my account',true),array('class'=>'button large','type'=>'submit'));
      
      echo $form->end();
    ?>
  </div>

</div>
