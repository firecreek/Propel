<?php

  $this->Javascript->link('projects/posts_add.js', false);
  
?>
<div class="cols">
  <div class="col left">


    <div class="box">
      <div class="banner">
        <h2><?php __('Edit this message'); ?></h2>
        <ul class="right important">
          <li><?php echo $this->Html->link(__('Delete this message',true),array('action'=>'delete',$id)); ?></li>
        </ul>
      </div>
      <div class="content">
        
        <?php
          echo $this->Form->create('Post',array('url'=>$this->here,'class'=>'block'));
        ?>
        
        <?php echo $this->element('posts/form'); ?>
        
        <?php
          //Show if more than one person (me)
          $people = $this->Auth->read('People');
          if(count($people) > 1):
        ?>
          <fieldset class="notify-changes">
            <h5><?php __('Notify the people checked off above that you\'ve edited this message?'); ?></h5>
            <p class="light">
              <?php __('Anyone checked off above will receive an email with the full content of the message.'); ?><br />
              <?php __('They will also be notified every time a comment is added.'); ?>
            </p>
            
            <?php
              echo $this->Form->input('_notify_changes',array('type'=>'checkbox','label'=>__('Yes, notify the subscribers above of these changes via email',true)));
            ?>
          </fieldset>
          <hr />
        <?php endif; ?>
          
          
        <?php
          echo $this->Form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$this->Html->link(__('Cancel',true),array('controller'=>'comments','action'=>'index','associatedController'=>'posts',$id) ) ));
          echo $this->Form->end();
        ?>

        <?php
          //Move record to a different project
          $message = '<p>'.__('All comments and attached files will move with this message.',true).'</p>';
          
          if(isset($record['Milestone']['title']) && !empty($record['Milestone']['title']))
          {
            $message .= '<p>'.sprintf(__('This message will no longer be associated with the milestone, %s',true),$record['Milestone']['title']).'</p>';
          }
          
          echo $this->element('projects/move_record',array(
            'id'        => $id,
            'alias'     => 'Post',
            'name'      => 'Message',
            'message'   => $message
          ));
        ?>
        
      </div>
    </div>

  
  </div>
  <div class="col right">
  
    <!-- nothing here -->
  
  </div>
</div>
