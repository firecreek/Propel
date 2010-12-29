
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
            
            <?php echo $form->create('Milestones',array('url'=>$this->here)); ?>
            
            <div class="selectables">
            
              <div class="group">
                <h3>Today <span class="light">(Wednesday, 29 December)</span> <span class="responsibility">Darren Moore</span></h3>
            
                <?php for($ii = 0; $ii < 6; $ii++): ?>
              
                  <div class="item">
                    <div class="check">
                      <?php echo $form->input('test',array('type'=>'checkbox','label'=>false)); ?>
                    </div>
                    <div class="name">
                      Untitled mileston esdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd fesdfuh sdiufh sdiuhf iudsh fiu shdifuh isuhd f
                      <div class="comments"><?php echo $html->link(__('Comments',true),array('action'=>'comments','100'),array('title'=>__('Comments',true))); ?></div>
                    </div>
                    <div class="maintain important">
                      <span class="delete"><?php echo $html->link(__('Delete',true),array('action'=>'delete','100'),array('title'=>__('Delete',true))); ?></span>
                      <span class="edit"><?php echo $html->link(__('Edit',true),array('action'=>'edit','100')); ?></span>
                    </div>
                  </div>
                
                <?php endfor; ?>
                
              </div>
              
            </div>
            
            <hr />
            
            <?php echo $form->submit(__('Submit Changes',true)); ?>
            
            <?php echo $form->end(); ?>
            
            
            
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

