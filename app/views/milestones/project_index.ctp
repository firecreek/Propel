
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Milestones'); ?> <span>(<?php echo __('Today is ',true).date('j F'); ?>)</span></h2>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        
        <?php echo $form->create('Milestones',array('url'=>$this->here)); ?>
        
        
        <?php
          //Overdue
          if(!empty($overdue))
          {
            echo $this->element('milestones/section',array(
              'class'   => 'important',
              'title'   => __('Overdue',true),
              'records' => $overdue
            ));
          }
        ?>
        
        <?php
          //Upcoming
          if(!empty($upcoming))
          {
            $calendar = false;
            if(!empty($upcoming14Days)) { $calendar = true; }
          
            echo $this->element('milestones/section',array(
              'class'         => 'highlight',
              'calendar'      => $calendar,
              'calendarData'  => $upcoming14Days,
              'title'         => __('Upcoming',true),
              'records'       => $upcoming
            ));
          }
        ?>
        
        <?php
          //Completed
          if(!empty($completed))
          {
            echo $this->element('milestones/section',array(
              'class'   => 'completed',
              'title'   => __('Completed',true),
              'records' => $completed,
              'dateKey' => 'completed_date',
              'checked' => true
            ));
          }
        ?>



        <hr />
        
        <?php
          echo $form->submit(__('Submit Changes',true)); 
          echo $form->end();
        ?>
        
        
        
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

