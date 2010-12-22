
<div class="cols">
  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php __('Project overview & activity'); ?></h2>
        <ul class="right">
          <li><?php echo $html->link(__('New message',true),array('controller'=>'messages','action'=>'add')); ?></li>
          <li><?php echo $html->link(__('New to-do list',true),array('controller'=>'accounts','action'=>'add')); ?></li>
          <li><?php echo $html->link(__('New milestone',true),array('controller'=>'milestones','action'=>'add')); ?></li>
        </ul>
      </div>
      <div class="content">
        <p>overview here</p>
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
          
          foreach($projectPeople as $person)
          {
            if(!isset($companies[$person['Company']['name']])) { $companies[$person['Company']['name']] = array(); }
            $companies[$person['Company']['name']][] = $person;
          }
        ?>
        <?php foreach($companies as $company => $people): ?>
          <h4><strong><?php echo $company; ?></strong></h4>
          <ul>
            <?php foreach($people as $person): ?>
              <li><?php echo $person['Person']['full_name']; ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </div>
    </div>
  
  </div>
</div>
