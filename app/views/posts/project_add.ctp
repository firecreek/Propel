<?php

  $javascript->link('projects/posts_add.js', false);
  
  $html->css('rte', null, array('inline'=>false));
  $javascript->link('jquery/jquery-rte.js', false);
  
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
        
        <fieldset class="light details">
        <?php
          echo $form->input('title',array('label'=>__('Title',true),'div'=>'input text full-width'));
          
          echo $form->input('body',array('type'=>'textarea','label'=>false,'class'=>'wysiwyg','div'=>'input textarea editor full-width'));
          echo $form->hidden('format',array('value'=>'textile'));
          
          echo $form->input('private',array(
            'label' => __('Private',true).' <span>('.__('Visible only to your company',true).')</span>'
          ));
        ?>
        </fieldset>
        
        <hr />
      
        <?php if(!empty($milestoneOptions)): ?>
          <?php
            echo $form->input('milestone_id',array(
              'label'=>__('Does this list relate to a milestone?',true),
              'options'=>$milestoneOptions,
              'empty' => true
            ));
          ?>
          <hr />
        <?php endif; ?>
        
        <?php
          echo $this->element('comments/people');
        ?>
        
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
