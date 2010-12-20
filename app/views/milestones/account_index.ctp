
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s milestones over the next 3 months',$name.'\'s')); ?></h2>
    <?php echo $this->element('milestones/banner-form'); ?>
  </div>
  <div class="content">
  
    <?php echo $session->flash(); ?>
    
    <p>list of late milestones</p>
    <p>list of milestones in calendar element</p>
    
  </div>
</div>
