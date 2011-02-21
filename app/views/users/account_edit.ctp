<?php
  $this->set('activeMenu','companies');
  
  //My account?
  $personal = false;
  if($personId == $session->read('Auth.Person.id'))
  {
    $personal = true;
  }
  
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
          echo $session->flash();
          echo $form->create('Person',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
      
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
      
        <hr />

        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
          
          echo $form->end();
        ?>
      </div>
    </div>

  </div>
  
  <div class="col right">
   
      
  </div>
  
</div>
