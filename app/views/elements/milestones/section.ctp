
<div class="section small <?php echo $class; ?> indented">
  <div class="banner">
    <h3><?php echo $title; ?></h3>
  </div>
  <div class="content">
  
    <?php if(isset($calendar) && $calendar == true): ?>
      <h3 class="sub"><?php __('Due in the next 14 days'); ?></h3>
      <?php
        echo $this->element('calendar/month',array('type'=>'short','month'=>date('n'),'year'=>date('Y')));
      ?>
      <br />
      <h3 class="sub"><?php __('All upcoming'); ?></h3>
    <?php endif; ?>
  
    <div class="listable">
    
      <?php
        //Group records by date
        if(!isset($dateKey)) { $dateKey = 'deadline'; }
        
        $grouped = array();
        foreach($records as $record)
        {
          $key = strtotime($record['Milestone'][$dateKey]).'::'.$record['Responsible']['name'];
          if(!isset($grouped[$key])) { $grouped[$key] = array(); }
          
          $grouped[$key][] = $record;
        }
      ?>
      
      <?php foreach($grouped as $key => $records): ?>
      
        <?php
          $split = explode('::',$key);
          $date = $split[0];
          $responsibility = $split[1];
        ?>
      
        <div class="group large">
          <div class="banner">
            <h4><?php echo date('l, j F',$date); ?> <span class="responsibility"><?php echo $responsibility; ?></span></h4>
          </div>
          <div class="content">
            <?php 
              foreach($records as $milestone)
              {
                echo $this->element('listable/item',array(
                  'id'      => $milestone['Milestone']['id'],
                  'name'    => $milestone['Milestone']['title'],
                  'alias'   => 'Milestones',
                  'checked' => (isset($checked) && $checked) ? true : false
                ));
              }
            ?>
          </div>
        </div>
        
      <?php endforeach; ?>
    
      
    </div>
  
  </div>
</div>
