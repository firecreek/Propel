<?php

  $html->css('projects/posts', null, array('inline'=>false));
  
?>

<?php if($record['Post']['private']): ?>
  <div class="private">
    <p><?php __('Only people from your company can see this message.'); ?></p>
  </div>
<?php endif; ?>

<div class="banner">
  <h2><?php echo $html->link(__('« All Messages',true),array('controller'=>'posts','action'=>'index')); ?></h2>
  <ul class="right important">
    <li><?php echo $html->link(__('New message',true),array('controller'=>'posts','action'=>'add')); ?></li>
    <li><?php echo $html->link(__('Edit this message',true),array('controller'=>'posts','action'=>'edit',$id)); ?></li>
    <li><?php echo $html->link(__('Delete',true),array('controller'=>'posts','action'=>'delete',$id)); ?></li>
  </ul>
</div>

<div class="content post-detail">
  <div class="avatar"><?php echo $html->image('avatar.png'); ?></div>
  <h3><?php echo $record['Post']['title']; ?></h3>
  <div class="detail">
    <p><span><?php __('From'); ?>:</span> <?php echo $record['Person']['full_name']; ?></p>
    <p><span><?php __('Date'); ?>:</span> <?php echo date('D, j M Y \a\t g:ma',strtotime($record['Post']['created'])); ?></p>
  </div>
  <div class="body">
    <p><?php echo $record['Post']['body']; ?></p>
  </div>
</div>
