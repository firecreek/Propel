
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s milestones over the next 3 months',$name.'\'s')); ?></h2>
    <?php echo $this->element('milestones/banner-form'); ?>
  </div>
  <div class="content">
  
    <?php echo $session->flash(); ?>
    
    <p>list of late milestones</p>
    
    <?php
      $months = 3;
      
      for($ii = 0; $ii < $months; $ii++)
      {
        $display = strtotime('+'.$ii.' month');
        
        $class = ($ii > 0) ? 'no-head' : null;
        
        echo $this->element('calendar/month',array('class'=>$class,'month'=>date('n',$display),'year'=>date('Y',$display)));
      }
      
    ?>
    
  </div>
</div>
