<?php

  $javascript->link('projects/posts_add.js', false);
  
?>
<div class="cols">
  <div class="col left">


    <div class="box">
      <div class="banner">
        <h2><?php __('Edit this message'); ?></h2>
        <ul class="right important">
          <li><?php echo $html->link(__('Delete this message',true),array('action'=>'delete',$id)); ?></li>
        </ul>
      </div>
      <div class="content">
        
        <?php
          echo $form->create('Post',array('url'=>$this->here,'class'=>'block'));
        ?>
        
        <?php echo $this->element('posts/form'); ?>
          
        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'comments','action'=>'index','associatedController'=>'posts',$id) ) ));
          echo $form->end();
        ?>

        
      </div>
    </div>

  
  </div>
  <div class="col right">
  
    <!-- nothing here -->
  
  </div>
</div>
