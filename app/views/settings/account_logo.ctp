<?php

  $html->css('pages/settings', null, array('inline'=>false));

?>

  <div class="box">
    <div class="banner">
      <h2><?php
        switch($type)
        {
          case 'account':
            echo __('Your logo',true);
            break;
          case 'apple':
            echo __('iPhone/iPad icon',true);
            break;
          case 'shortcut':
            echo __('Shortcut icon',true);
            break;
        }
      ?></h2>
    </div>
    <div class="content">
    
      <?php
        echo $session->flash();
        echo $form->create('Account',array('url'=>$this->here,'class'=>'block'));
      ?>
      
      <?php
        $note = '<span>'.__('Note',true).':</span> ';
        $tip = '<span>'.__('Tip',true).':</span> ';
      ?>
      
      <?php if($type == 'account'): ?>
      
        <p><?php __('Your logo appears on the sign in screen, the Dashboard, and Overview pages.'); ?></p>
        <p class="note"><?php echo $note.__('The logo must be in PNG, GIF, or JPG format and can\'t be more than 300 pixels wide.',true); ?></p>
        <p class="note"><?php echo $tip.__('If you want the logo\'s background to blend in with Opencamp\'s grey background, you should put the logo on a grey background with the hex color #e5e5e5 and then upload that version of the logo.',true); ?></p>
      
      <?php elseif($type == 'apple'): ?>
      
        <p><?php __('Appears when you add a home screen icon on your iPhone, iPad, or iPod Touch (apple-touch-icon.png).'); ?></p>
        <p class="note"><?php echo $note.__('The uploaded image must be a square PNG or JPG and at least 114 pixels by 114 pixels.',true); ?></p>
        <p class="note"><?php echo $tip.__('The uploaded image should NOT use any transparency.',true); ?></p>
      
      <?php elseif($type == 'shortcut'): ?>
      
        <p><?php __('Your shortcut icon, or favicon, appears in some web browsers on the address bar, tabs or bookmarks menu.'); ?></p>
        <p class="note"><?php echo $note.__('The uploaded image must be a square PNG, GIF or JPG and at least 16 pixels by 16 pixels (exact size is best but larger images will be resized).',true); ?></p>
        <p class="note"><?php echo $tip.__('Use a transparent PNG image to let the background show through.',true); ?></p>
      
      <?php endif; ?>
      
      <?php
        echo $form->input('image',array('type'=>'file','label'=>false));
      ?>
      
      <hr />
      
      <?php
        echo $form->submit(__('Upload logo',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
        echo $form->end();
      ?>
      
    </div>
  </div>

