
<div class="box">
  <div class="banner">
    <h2><?php __('Edit milestone'); ?></h2>
  </div>
  <div class="content">
    
    <?php
      echo $session->flash();
    ?>
    
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
    ?>
    
    <?php
      echo $form->create('Milestone',array('url'=>$this->here,'class'=>'block'));
      
      echo $form->input('deadline',array('label'=>__('When is it due?',true)));
      echo $form->input('title',array('label'=>__('Enter a title',true),'after'=>'<small>'.__('(e.g. Design review 3)',true).'</small>'));
      echo $form->input('responsible',array('options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
      echo $form->input('email',array('label'=>__('Email 48 hours before it\'s due',true)));
      
      echo $form->hidden('ident',array('value'=>$this->params['url']['objId']));
    ?>
    
    <?php /*
    <div class="extra" id="shift-milestones">
      <h5><?php __('Shift future milestones too?'); ?></h5>
      <p><?php __('Would you also like to move subsequent milestones the same number of days?'); ?></p>
      <?php echo $form->input('Shift.action',array('type'=>'checkbox','label'=>__('Yes, shift future milestones too',true))); ?>
      <?php echo $form->input('Shift.avoid_weekend',array('type'=>'checkbox','label'=>__('Keep shifted milestones off weekends',true))); ?>
    </div>
    */ ?>
      
      
    <hr />
      
    <?php
      echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
      echo $form->end();
    ?>
    
  </div>
</div>
