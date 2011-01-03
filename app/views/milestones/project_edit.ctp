
<div class="box">
  <div class="banner">
    <h2><?php __('Edit milestone'); ?></h2>
  </div>
  <div class="content">
    
    <?php
      echo $session->flash();
    ?>
    
    <?php
      echo $this->element('milestones/form',array(
        'submitText' => __('Save changes',true)
      ));
    ?>
    
  </div>
</div>
