
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
  
    <p>right</p>
  
  </div>
</div>
