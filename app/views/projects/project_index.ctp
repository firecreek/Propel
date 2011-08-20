<div class="cols">
  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php __('Project overview & activity'); ?></h2>
        <ul class="right important">
          <li><?php echo $html->link(__('New message',true),array('controller'=>'posts','action'=>'add')); ?></li>
          <li><?php echo $html->link(__('New to-do list',true),array('controller'=>'accounts','action'=>'add')); ?></li>
          <li><?php echo $html->link(__('New milestone',true),array('controller'=>'milestones','action'=>'add')); ?></li>
        </ul>
      </div>
      <div class="content">
      
      
        <?php if($project['Project']['announcement_show'] && !empty($project['Project']['announcement'])): ?>
          <div class="note announcement">
            <div class="wrapper">
              <h4><?php __('Announcement'); ?></h4>
              <p><?php echo $project['Project']['announcement']; ?></p>
            </div>
          </div>
        <?php endif; ?>
      
      
        <?php if(!empty($overdue) || !empty($upcoming)): ?>
          <div class="section outlined">
            <div class="banner">
              <h3><?php
                if(!empty($overdue) && !empty($upcoming))
                {
                  echo __('Late & Upcoming Milestones');
                }
                elseif(!empty($overdue))
                {
                  echo __('Late milestones');
                }
                elseif(!empty($upcoming))
                {
                  echo __('Upcoming milestones');
                }
              ?></h3>
            </div>
            <div class="content">
            
              <?php if(!empty($overdue)): ?>
                <ul class="overdue">
                  <?php foreach($overdue as $record): ?>
                    <?php
                      $total = floor((time() - strtotime($record['Milestone']['deadline'])) / 86400);
                    ?>
                    <li>
                      <strong><?php echo $total; ?> days late</strong>:
                      <?php echo $html->link($record['Milestone']['title'],array('projectId'=>$record['Milestone']['project_id'],'controller'=>'milestones')); ?>
                      <?php if(isset($record['Responsible'])): ?>
                        (<?php echo $record['Responsible']['name']; ?> is responsible)
                      <?php endif; ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              
              <?php if(!empty($upcoming)): ?>
                <h3 class="sub"><?php __('Due in the next 14 days'); ?></h3>
                <?php
                  echo $this->element('calendar/month',array('records'=>$upcoming,'type'=>'short','month'=>date('n'),'year'=>date('Y')));
                ?>
              <?php endif; ?>
                
            </div>
          </div>
        <?php endif; ?>
        
        
        <div class="section">
          <div class="content">
            <?php echo $this->element('logs/display',array('logs'=>$logs)); ?>
          </div>
        </div>
        
        
      
      </div>
    </div>
  
  
  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner"><h3><?php __('People on this project'); ?></h3></div>
      <div class="content">
        <?php
          //Sort by company
          $companies = array();
          
          foreach($auth->read('People') as $person)
          {
            if(!isset($companies[$person['Company']['name']])) { $companies[$person['Company']['name']] = array(); }
            $companies[$person['Company']['name']][] = $person;
          }
        ?>
        <?php foreach($companies as $company => $people): ?>
          <h4><strong><?php echo $company; ?></strong></h4>
          <ul>
            <?php foreach($people as $person): ?>
              <li>
                <?php echo $person['Person']['full_name']; ?>
                <br />
                <small>
                  <?php if($person['Person']['id'] == $this->Auth->read('Person.id')): ?>
                    <?php echo __('You are currently signed in',true); ?>
                  <?php elseif( ((time() - strtotime($person['User']['last_activity']))/60) < 10 ): ?>
                    <?php echo __('Logged in right now',true); ?>
                  <?php elseif(empty($person['User']['last_activity'])): ?>
                    <?php echo __('No recent activity',true); ?>
                  <?php else: ?>
                    <?php echo __('Latest activity',true).' '.$time->timeAgoInWords($person['User']['last_activity'],array('end'=>false)); ?>
                  <?php endif; ?>
                </small>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </div>
    </div>
  
  </div>
</div>
