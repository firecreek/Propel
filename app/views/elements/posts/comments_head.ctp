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
    <?php if($this->Auth->check(array('controller'=>'posts','action'=>'add'))): ?>
      <li><?php echo $html->link(__('New message',true),array('controller'=>'posts','action'=>'add')); ?></li>
    <?php endif; ?>
    
    <?php if($this->Auth->check(array('controller'=>'posts','action'=>'edit'),array('type'=>'model','record'=>$record,'model'=>'Post'))): ?>
      <li><?php echo $html->link(__('Edit this message',true),array('controller'=>'posts','action'=>'edit',$id)); ?></li>
    <?php endif; ?>
    
    <?php if($this->Auth->check(array('controller'=>'posts','action'=>'delete'),array('type'=>'model','record'=>$record,'model'=>'Post'))): ?>
      <li><?php echo $html->link(__('Delete',true),array('controller'=>'posts','action'=>'delete',$id)); ?></li>
    <?php endif; ?>
  </ul>
  
</div>

<div class="content post-detail">
  <div class="avatar"><?php echo $layout->avatar($record['Person']['user_id']); ?></div>
  <h3><?php echo $record['Post']['title']; ?></h3>
  <div class="detail">
  
    <p><span><?php __('From'); ?>:</span> <?php echo $record['Person']['full_name']; ?></p>
    
    <p><span><?php __('Date'); ?>:</span> <?php echo date('D, j M Y \a\t g:ma',strtotime($record['Post']['created'])); ?></p>
    
    <?php if(isset($record['Milestone']['id']) && !empty($record['Milestone']['id'])): ?>
      <p><span><?php __('Milestone'); ?>:</span> <?php echo $this->Html->link($record['Milestone']['title'],array('controller'=>'milestones','action'=>'index','#Milestone-'.$record['Milestone']['id'])); ?></p>
    <?php endif; ?>
    
    <?php if(isset($record['Category']['id']) && !empty($record['Category']['id'])): ?>
      <p><span><?php __('Category'); ?>:</span> <?php echo $this->Html->link($record['Category']['name'],array('controller'=>'posts','action'=>'index','category'=>$record['Category']['id'])); ?></p>
    <?php endif; ?>
    
  </div>
  <div class="body restore-html">
    <?php
      if($record['Post']['format'] == 'textile')
      {
        echo $textile->parse($record['Post']['body']);
      }
      else
      {
        echo $record['Post']['body'];
      }
    ?>
  </div>
</div>

