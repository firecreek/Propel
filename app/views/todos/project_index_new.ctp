
<div class="blank-slate">

  <div class="icon">
    <?php echo $html->image('icons/calendar-128.png'); ?>
  </div>
  
  <div class="content">
    <h2><?php __('Let\'s add the first milestone to this project.'); ?></h2>
    
    <p><?php __('Milestones help you keep track of deadlines, events, and important dates related to a project. You can add them for yourself or assign them to someone else.'); ?></p>
    
    <?php
      echo $layout->button(__('Add the first milestone',true),array('action'=>'add'),'large add');
    ?>
  </div>

</div>

