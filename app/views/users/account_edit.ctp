<?php
  $javascript->link('user_edit.js', false);
  $html->css('people', null, array('inline'=>false));
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Update your Propel ID details'); ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $form->create('Person',array('url'=>$this->here,'class'=>'basic','type'=>'file','id'=>'UserEdit'));
        ?>
        
        <div class="avatar">
          <div class="image"><?php echo $layout->avatar($this->Auth->read('Person'));?></div>
          <div class="upload">
            <p><?php __('Upload your photo') ?></p>
            <?php echo $form->input('User.avatar',array('type'=>'file','label'=>false)); ?>
          </div>
        </div>
        
      
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
          echo $form->input('User.email_send',array('label'=>__('Receive emails from Propel',true)));
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
