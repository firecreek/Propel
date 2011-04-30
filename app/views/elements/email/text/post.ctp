<?php

  App::import('Lib','Html2text');

?><?php __('Project'); ?>: <?php echo $data['Project']['name']."\n"; ?>
<?php __('Company'); ?>: <?php echo $data['Person']['Company']['name']."\n"; ?>

<?php echo $data['Person']['full_name']; ?> <?php
  if($data['Action']['type'] == 'add')
  {
    echo __('posted a new message',true);
  }
  else
  {
    echo __('edited an existing message',true);
  }
?>:

<?php
  if($data['Post']['format'] == 'richtext')
  {
    echo convert_html_to_text($data['Post']['body']);
  }
  else
  {
    echo $data['Post']['body'];
  }
?>



<?php __('This message was sent to'); ?> <?php echo $to['name']; ?>.

