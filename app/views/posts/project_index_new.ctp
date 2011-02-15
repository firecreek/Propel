
<div class="blank-slate">

  <div class="icon">
    <?php echo $html->image('icons/posts-128.png'); ?>
  </div>
  
  <div class="content">
    <h2><?php __('Ready to post the first message to this project?'); ?></h2>
    
    <p><?php __('Messages are used to discuss ideas, ask questions, or post announcements about a project. Messages are like emails except they don\'t clutter your inbox.'); ?></p>
    
    <?php
      echo $layout->button(__('Post the first message',true),array('action'=>'add'),'large add');
    ?>
  </div>

</div>

