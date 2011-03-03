
<div class="box" rel="edit-milestone-<?php echo $id; ?>">
  <div class="banner">
    <h2><?php __('Edit milestone'); ?></h2>
  </div>
  <div class="content">
    
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
    ?>
    
    <?php
      echo $form->create('Milestone',array('url'=>$this->here,'class'=>'block'));
      
      echo $form->input('deadline',array('label'=>__('When is it due?',true)));
      echo $form->input('title',array('label'=>__('Enter a title',true),'after'=>'<small>'.__('(e.g. Design review 3)',true).'</small>'));
      echo $form->input('responsible',array('options'=>$responsibleOptions,'empty'=>true,'label'=>__('Who\'s responsible?',true)));
      echo $form->input('email',array('label'=>__('Email 48 hours before it\'s due',true)));
      
      echo $form->hidden('ident',array('value'=>$this->params['form']['objId']));
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
    
    <p class="move-project-link"><?php echo $html->link('Move...','#',array('class'=>'unimportant')); ?></p>
    
    <div class="dialog default move-project-form" style="display:none;">
      <div class="wrapper">
        <?php
          echo $form->create('Milestone',array('class'=>'block','url'=>array(
            'controller'  => 'milestones',
            'action'      => 'move_project',
            $id
          )));
        ?>
        <?php
          $authProjects = $this->Auth->read('Projects');
          $projectOptions = array();
          
          foreach($authProjects as $authProject)
          {
            if($authProject['Project']['id'] != $this->Auth->read('Project.id'))
            {
              $projectOptions[$authProject['Project']['id']] = $authProject['Project']['name'];
            }
          }
        
          echo $form->input('Milestone.project_id',array('empty'=>true,'options'=>$projectOptions,'label'=>__('Move this milestone to another project:',true)));
        ?>
        <?php
          echo $form->submit(__('Move this milestone',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
          echo $form->end();
        ?>
      </div>
    </div>
    
    
  </div>
</div>

<?php
  echo $this->Javascript->codeBlock("
    Milestones.initMoveProject(".$id.");
  ");
?>
