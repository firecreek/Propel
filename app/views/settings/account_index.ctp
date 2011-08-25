<?php
  $html->css('settings', null, array('inline'=>false));
?>

<div class="cols">
  <div class="col left">

    <div class="box">
      <div class="banner">
        <?php echo $this->element('settings/menu'); ?>
      </div>
      <div class="content">
      
        <?php
          echo $form->create('Account',array('url'=>$this->here,'class'=>'basic'));
        ?>        
      
        
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
  
    <!-- empty -->
  
  </div>
</div>
