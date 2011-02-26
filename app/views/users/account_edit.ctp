<?php
  
  $javascript->link('accounts/user_edit.js', false);
  
  $html->css('accounts/user_edit', null, array('inline'=>false));

?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Update your Opencamp ID details'); ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $form->create('Person',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
      
        <?php
        
          echo $form->input('Person.first_name',array('label'=>__('First name',true)));
          echo $form->input('Person.last_name',array('label'=>__('Last name',true)));
          echo $form->input('User.email',array('label'=>__('Email',true)));
          echo $form->input('User.username');
          echo $form->input('User.password');
          echo $form->input('User.password_confirm',array('type'=>'password','label'=>__('Confirm password',true)));
        ?>
        
        <?php
          $formats = array(
            'html' => 'HTML',
            'text' => 'Plain text'
          );
        
          echo $form->input('User.email_format',array('label'=>__('Email format',true),'options'=>$formats));
          echo $form->input('User.email_send',array('label'=>__('Receive emails from Opencamp',true)));
        ?>
      
        <hr />

        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
          
          echo $form->end();
        ?>
      </div>
    </div>

  </div>
  
  <div class="col right">
  
    <?php
      $accountRecords = $this->Auth->read('Accounts');
      if(count($accountRecords) > 1):
    ?>
    
      <p><?php __('Changes to your personal information will be made in the following accounts as well:'); ?></p>
      
      <ul>
        <?php foreach($accountRecords as $accountRecord): ?>
          <li><?php echo $accountRecord['Account']['name']; ?></li>
        <?php endforeach; ?>
      </ul>
  
    <?php endif; ?>
      
  </div>
  
</div>
