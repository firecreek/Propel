
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s milestones over the next 3 months',$responsible['name'].'\'s')); ?></h2>
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Milestones',array('url'=>$this->here,'type'=>'get','class'=>'single right'));
      echo $form->input('responsible',array('label'=>__('Show milestones assigned to',true).':','options'=>$responsibleOptions,'selected'=>$responsible));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>

  </div>
  <div class="content">
    
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
