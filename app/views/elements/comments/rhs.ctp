
<?php if(!empty($record['CommentPerson'])): ?>
  <div class="area">
    <div class="banner">
      <h3><?php __('Comment Notification'); ?></h3>
    </div>
    <div class="content">
      <p><?php __('These people are subscribed to receive email notifications when new comments are posted.'); ?><p>
      <?php
        //Sort by company
        $companies = array();
        
        foreach($record['CommentPerson'] as $cprecord)
        {
          if(!isset($companies[$cprecord['Person']['Company']['name']])) { $companies[$cprecord['Person']['Company']['name']] = array(); }
          $companies[$cprecord['Person']['Company']['name']][] = $cprecord['Person'];
        }
      ?>
      
      
      <ul class="nested">
        <?php foreach($companies as $company => $people): ?>
          <li>
            <h4><?php echo $company; ?></h4>
            <ul>
              <?php foreach($people as $person): ?>
                <li>
                <?php
                  if($person['id'] == $this->Auth->read('Person.id'))
                  {
                    echo __('You',true).' ('.$html->link(__('Unsubscribe',true),array($id,'?'=>array('unsubscribe'=>true))).')';
                  }
                  else
                  {
                    echo $person['full_name'];
                  }
                ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ul>
      
      
    </div>
  </div>
<?php endif; ?>


<?php if(!empty($record['Comment'])): ?>
  <div class="area">
    <div class="banner">
      <h3><?php __('Who\'s talking about this milestone?'); ?></h3>
    </div>
    <div class="content">
      <?php
        $people = Set::combine($record['Comment'],'{n}.Person.full_name','{n}.Person.email');
      ?>
      
      <ul>
        <?php foreach($people as $name => $email): ?>
          <li>
            <strong><?php echo $name; ?></strong><br />
            <?php echo $html->link($email,'mailto:'.$email); ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
