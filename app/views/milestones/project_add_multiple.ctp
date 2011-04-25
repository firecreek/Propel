<?php

  $html->css('projects/milestones', null, array('inline'=>false));

?>
<div class="box">
  <div class="banner">
    <h2><?php __('Add mulitple milestones'); ?></h2>
  </div>
  <div class="content">
    
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
    ?>
    
    <?php
      echo $form->create('Milestone',array('url'=>$this->here));
    ?>
    

    <div id="Calendar" class="dialog default calendar" style="display:none;">
      <p class="title"><?php __('Set the due date'); ?></p>
      <div class="cal"></div>
      <?php
        echo $javascript->codeBlock("
          $('.dialog.calendar').calendar({
            datepicker: {
              onSelect: function(dateText) { Calendar.updateDate(dateText); }
            }
          });
        ");
      ?>
    </div>
    
    <table class="zebra add-multiple">
      <thead>
        <tr>
          <th class="due"><?php __('Date due'); ?></th>
          <th class="title"><?php __('Milestone title'); ?></th>
          <th class="responsible"><?php __('Party responsible'); ?></th>
          <th class="reminder">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
      <?php for($ii = 1; $ii <= 10; $ii++): ?>
        <tr>
          <td class="due"><?php
            echo $form->input('Milestone.'.$ii.'.deadline',array(
              'label'=>false,
              'type'=>'text',
              'class'=>'date-select unimportant',
              'value' => __('Pick a date',true)
            ));
          ?></td>
          <td class="title"><?php echo $form->input('Milestone.'.$ii.'.title',array('label'=>false)); ?></td>
          <td class="responsible"><?php echo $form->input('Milestone.'.$ii.'.responsible',array('options'=>$responsibleOptions,'empty'=>true,'label'=>false)); ?></td>
          <td class="reminder"><?php echo $form->input('Milestone.'.$ii.'.email',array('label'=>__('Send reminder',true))); ?></td>
        </tr>
      <?php endfor; ?>
      </tbody>
    </table>
    
    <?php
      echo $this->Javascript->codeBlock("
        $('input.date-select').bind('click',function(){
          Calendar.show($(this).attr('id'));
        });
      ");
    ?>

      
    <hr />
      
    <?php
      echo $form->submit(__('Create these milestones',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
      echo $form->end();
    ?>

    
  </div>
</div>
