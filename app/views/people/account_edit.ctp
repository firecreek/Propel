<?php

  //My account?
  $personal = false;
  if($personId == $session->read('Auth.Person.id'))
  {
    $personal = true;
  }

?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php
          if($personal)
          {
            echo __('Your contact information for this account',true);
          }
          else
          {
            echo __('Edit',true).' '.$record['Person']['full_name'];
          }
        ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $session->flash();
          echo $form->create('Person',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
        
        <fieldset>
          <?php
            
            if(!$personal)
            {
              echo $form->input('company_id',array('label'=>__('Company',true)));
            }
          
            echo $form->input('email',array('label'=>__('Email',true)));
            echo $form->input('title');
            echo $form->input('phone_number_office',array('label'=>__('Office',true).' #'));
            echo $form->input('phone_number_mobile',array('label'=>__('Mobile',true).' #'));
            echo $form->input('phone_number_fax',array('label'=>__('Fax',true).' #'));
            echo $form->input('phone_number_home',array('label'=>__('Home',true).' #'));
          ?>
        </fieldset>
        
        

        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
          
          echo $form->end();
        ?>
      </div>
    </div>

  </div>
  
  <div class="col right">
  
    <?php if(!$personal): ?>
      
      <div class="area">
        <div class="banner">
          <h3><?php __('Delete this person?'); ?></h3>
        </div>
        <div class="content">
          <p><?php __(sprintf('This will permanently remove %s from your account. Don\'t worry, their messages, comments, and history will not be erased.',$record['Person']['first_name'])); ?></p>
          <p><?php echo $html->link(__(sprintf('Delete %s now',$record['Person']['first_name']),true),array('action'=>'delete',$personId),array('class'=>'red')); ?></p>
        </div>
      </div>
      
      
      <div class="area">
        <div class="banner">
          <h3><?php echo $record['Person']['first_name'].' '.__('can access...', true); ?></h3>
        </div>
        <div class="content">
          <p>TO DO</p>
        </div>
      </div>
      
    <?php endif; ?>    
      
  </div>
  
</div>
