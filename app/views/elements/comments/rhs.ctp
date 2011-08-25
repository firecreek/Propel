

<div class="box">
  <div class="banner">
    <h3><?php __('Comment Notification'); ?></h3>
  </div>
  <div class="content">
    <?php
      //Check if person is subscribed if not then show subscribe link
      $subscribed = false;
      
      if(!empty($record['CommentPerson']))
      {
        foreach($record['CommentPerson'] as $cprecord)
        {
          if($cprecord['Person']['id'] == $this->Auth->read('Person.id'))
          {
            $subscribed = true;
            break;
          }
        }
      }
    ?>
    
    <?php if(!$subscribed && $this->Auth->check(array('controller'=>'comments','action'=>'subscribe'),array('prefix'=>false))): ?>
    
      <p class="highlight pad5"><strong><?php echo $html->link(__('Subscribe to this message',true),array('action'=>'subscribe',$id),array('class'=>'normal')); ?></strong> <?php __('to receive an email when new comments are posted.'); ?></p>

      <p><?php __('If you post a comment you\'ll automatically be subscribed to receive email notifications.'); ?></p>
    
    <?php endif; ?>
  
  
    <?php if(!empty($record['CommentPerson'])): ?>
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
                    echo __('You',true);
                    
                    if($this->Auth->check(array('controller'=>'comments','action'=>'unsubscribe'),array('prefix'=>false)))
                    {
                      echo ' ('.$html->link(__('Unsubscribe',true),array('action'=>'unsubscribe',$id)).')';
                    }
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
    <?php endif; ?>
    
  </div>
</div>


<?php if(!empty($record['Comment'])): ?>
  <div class="box">
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
