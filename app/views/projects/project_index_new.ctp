
<div class="blank-slate">

  <div class="icon">
    <?php echo $html->image('icons/grow-128.png'); ?>
  </div>
  
  <div class="content">
    <h2><?php __('Welcome to your new project'); ?></h2>
    
    <p><?php __('This Overview screen will show you the latest activity in your project. But before we can show you activity, you\'ll need to get the project started.'); ?></p>
    
    <ul>
      <li><?php echo $html->link(__('Post the first message',true),array('controller'=>'messages','action'=>'add')); ?></li>
      <li><?php echo $html->link(__('Create the first to-do list',true),array('controller'=>'todos','action'=>'add')); ?></li>
      <li><?php echo $html->link(__('Add the first milestone',true),array('controller'=>'milestones','action'=>'add')); ?></li>
    </ul>
    
  </div>

</div>

