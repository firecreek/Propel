<?php
  $javascript->link('accounts/milestones.js', false);
?>

<div class="box">
  <div class="banner">
    <h2><?php
      if(!isset($responsibleName))
      {
        $responsibleName = __('Everyone',true);
      }
      
      echo __(sprintf('%s milestones over the next 3 months',$responsibleName.'\'s'),true);
    ?></h2>
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Milestone',array('url'=>$this->here,'type'=>'get','class'=>'single right','id'=>'MilestoneFilterForm'));
      echo $form->input('responsible',array('label'=>__('Show milestones assigned to',true).':','options'=>$responsibleOptions));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>

  </div>
  <div class="content">

    <?php if(!empty($overdue)): ?>
      <div class="section plain late">
        <div class="banner">
          <h3><?php __('Late milestones'); ?></h3>
        </div>
        <div class="content">
      
          <ul class="overdue">
            <?php foreach($overdue as $record): ?>
              <?php
                $total = floor((time() - strtotime($record['Milestone']['deadline'])) / 86400);
              ?>
              <li>
                <strong><?php echo $total; ?> days late</strong>:
                <?php
                  echo $html->link($record['Milestone']['title'],array(
                    'accountSlug' => $record['Account']['slug'],
                    'projectId'   => $record['Project']['id'],
                    'controller'  => 'milestones',
                    'action'      => 'index',
                    '#Milestone'.$record['Milestone']['id']
                  ));
                ?>
                <?php
                  $info = array();
                  $info[] = $record['Account']['name'];
                  $info[] = $record['Project']['name'];
                  if(isset($record['Responsible'])) { $info[] = $record['Responsible']['name']; }
                  
                  echo '('.implode(' | ',$info).')';
                ?>
              </li>
            <?php endforeach; ?>
          </ul>
            
        </div>
      </div>
    <?php endif; ?>
    
    <?php
      $months = 3;
      
      for($ii = 0; $ii < $months; $ii++)
      {
        $display = strtotime('+'.$ii.' month');
        
        $class = ($ii > 0) ? 'no-head' : null;
        
        echo $this->element('calendar/month',array(
          'class'   => $class,
          'month'   => date('n',$display),
          'year'    => date('Y',$display),
          'records' => $records,
          'eventAccount' => true
        ));
      }
      
    ?>
    
  </div>
</div>
