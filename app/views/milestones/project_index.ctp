<?php

  $javascript->link('listable.js', false);
  $javascript->link('projects/milestones.js', false);
  
  echo $javascript->codeBlock("
    Milestones.refreshUrl = '".$html->url(array('action'=>'index'))."';
  ");
  
  $html->css('projects/milestones', null, array('inline'=>false));
  
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Milestones'); ?> <span>(<?php echo __('Today is ',true).date('j F'); ?>)</span></h2>
      </div>
      <div class="content">
        
        <?php echo $form->create('Milestone',array('url'=>$this->here,'id'=>'MilestoneIndex')); ?>
        
        
        <?php
          //Overdue
          if(!empty($overdue))
          {
            echo $this->element('milestones/section',array(
              'type'    => 'overdue',
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
              'type'          => 'upcoming',
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
              'type'    => 'completed',
              'class'   => 'completed',
              'title'   => __('Completed',true),
              'records' => $completed,
              'dateKey' => 'completed_date',
              'checked' => true
            ));
          }
        ?>



        
        <?php
          echo $form->submit(__('Submit Changes',true)); 
          echo $form->end();
        ?>
        
        
        <?php
          echo $javascript->codeBlock("
            $('form#MilestoneIndex .submit').hide();
            $('.listable').listable();
          ");
        ?>
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    
    <?php
      if($auth->check('Project.Milestones','create'))
      {
        echo $layout->button(__('Add a new milestone',true),array('action'=>'add'),'large add');
        
        echo $layout->button(__('Add ten at a time',true),array('action'=>'add_multiple'),'inline add-grey');
      }
    ?>
    

    <?php
    
      //Small calendar
      echo $this->element('calendar/month',array(
        'type'    => 'small',
        'month'   => date('n'),
        'year'    => date('Y'),
        'records' => $upcoming
      ));
      
    ?>
    
    
  </div>
  
</div>

