
<?php
  $people = $layout->notificationList($auth->read('People'));
  if(!empty($people)):
?>
  
  <div class="subscribe-people">
    
    <h5><?php __('Subscribe people to receive email notifications'); ?></h5>
    <p>
      <?php __('The people you select will get an email when you post this comment.'); ?><br />
      <?php __('They\'ll also be notified by email every time a new comment is added.'); ?>
    </p>
    
    <?php
      echo $form->input('CommentPeople.person_id',array(
        'multiple'  => 'checkbox',
        'options'   => $people,
        'label'     => false,
        'escape'    => false,
        'legend'    => false,
        'prefixResponsible' => true
      ));
    ?>
  
  </div>
  
  <hr />

<?php
  endif;
?>
