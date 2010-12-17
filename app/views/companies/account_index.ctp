
<div class="box">
  <div class="banner">
    <h2><?php __('Every company and person in your system'); ?></h2>
    
    <?php echo $html->link(__('Add a new company',true),array('action'=>'add'),array('class'=>'button action add large')); ?>
    
  </div>
  <div class="content">
    <?php
      echo $session->flash();
    ?>
    
  </div>
</div>
