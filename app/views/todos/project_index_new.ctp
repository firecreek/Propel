
<div class="blank-slate">

  <div class="icon">
    <?php echo $html->image('icons/todo-128.png'); ?>
  </div>
  
  <div class="content">
    <h2><?php __('Make the first to-do list and get organized.'); ?></h2>
    
    <p><?php __('To-dos help you keep track of all the little things that need to get done. You can add them for yourself or assign them to someone else.'); ?></p>
    
    <?php
      echo $layout->button(__('Create the first to-do list',true),array('action'=>'add'),'large add');
    ?>
  </div>

</div>

