
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Milestones'); ?> <span>(<?php echo __('Today is ',true).date('j F'); ?>)</span></h2>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        

        <div class="section small highlight indented">
          <div class="banner">
            <h3><?php __('Upcoming milestones'); ?></h3>
          </div>
          <div class="content">
          
            <h3 class="sub"><?php __('Due in the next 14 days'); ?></h3>
            <?php
              echo $this->element('calendar/month',array('type'=>'short','month'=>date('n'),'year'=>date('Y')));
            ?>
            
            <br />
          
            <h3 class="sub"><?php __('All upcoming'); ?></h3>
            <p>abc</p>
            
          </div>
        </div>
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    
    <?php
      if($auth->check('Project.Milestones','create'))
      {
        echo $layout->button(__('Add a new milestone',true),array('action'=>'add'),'large add');
      }
    ?>
    

    <?php
    
      //Small calendar
      echo $this->element('calendar/month',array('type'=>'small','month'=>date('n'),'year'=>date('Y')));
      
    ?>
    
    
  </div>
  
</div>

