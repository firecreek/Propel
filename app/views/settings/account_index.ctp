<?php

  $html->css('pages/settings', null, array('inline'=>false));

?>

<div class="cols">
  <div class="col left">

    <div class="box">
      <div class="banner">
        <?php echo $this->element('settings/menu'); ?>
      </div>
      <div class="content">
      
        <?php
          echo $session->flash();
          echo $form->create('Account',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
        
        
        <h3><?php __('Your logo'); ?></h3>
        
        <div class="logos">
        
          <div class="col account">
            <div class="image"></div>
            <p class="type"><strong><?php __('Your logo'); ?></strong> (<?php echo $html->link(__('Upload logo',true),array('action'=>'logo','account')); ?>)</p>
            <p class="info"><?php __('Your logo appears on the sign in screen, the Dashboard, and Overview pages.'); ?></p>
          </div>
          
          <div class="col apple">
            <div class="image"></div>
            <p class="type"><strong><?php __('iPhone/iPad icon'); ?></strong> (<?php echo $html->link(__('Upload logo',true),array('action'=>'logo','apple')); ?>)</p>
            <p class="info"><?php __('Appears when you add a home screen icon on your iPhone, iPad, or iPod Touch (apple-touch-icon.png).'); ?></p>
          </div>
          
          <div class="col shortcut">
            <div class="image"></div>
            <p class="type"><strong><?php __('Shortcut icon'); ?></strong> (<?php echo $html->link(__('Upload logo',true),array('action'=>'logo','shortcut')); ?>)</p>
            <p class="info"><?php __('Your shortcut icon, or favicon, appears in some web browsers on the address bar, tabs or bookmarks menu.'); ?></p>
          </div>
          
        </div>
        
        
        <hr />
        
      
        
        <h3><?php __('Your site name'); ?></h3>
        <p class="intro"><?php __('The site name appears at the top of every page.'); ?></p>
        <fieldset class="tight">
          <?php
            echo $form->input('name',array('label'=>false));
          ?>
        </fieldset>
        
        <?php
          echo $form->submit(__('Save changes',true));
          
          echo $form->end();
        ?>
        
      </div>
    </div>


  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner"><h3><?php __('Tip'); ?></h3></div>
      <div class="content">
        <p><?php
          $companyLink = $html->link(__('All People',true),array('controller'=>'companies','action'=>'index'));
          __(sprintf('To add or edit the people in your company, go to the \'%s\' page.',$companyLink));
        ?></p>
      </div>
    </div>
  
  </div>
</div>
