<?php

  $javascript->link('projects/posts_add.js', false);
  
?>
<div class="cols">
  <div class="col left">


    <div class="box">
      <div class="banner">
        <h2><?php __('Post a new message'); ?></h2>
      </div>
      <div class="content">
        
        <?php
          echo $form->create('Post',array('url'=>$this->here,'class'=>'block'));
        ?>
        
        <?php echo $this->element('posts/form'); ?>
        
        <?php
          echo $form->submit(__('Post this message',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
          echo $form->end();
        ?>

        
      </div>
    </div>

  
  </div>
  <div class="col right">
  
    <!-- nothing here -->
  
  </div>
</div>
