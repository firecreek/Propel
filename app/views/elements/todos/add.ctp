
<div class="cols">

  <div class="col left">
      
    <div class="box">
      <div class="banner">
        <h2><?php __('New to-do list'); ?></h2>
      </div>
      <div class="content">
        
        <?php
          $responsibleOptions = $layout->permissionList($auth->read('People'),array('anyone'=>false));
        ?>
        
        <?php
          echo $form->create('Todo',array('url'=>array('action'=>'add'),'class'=>'block larger'));
          
          echo $form->input('name',array(
            'label'=>__('First give the list a name',true).' <span>('.__('e.g. "Things for the meeting"',true).')</span>',
            'div'   => 'input text strong full-width'
          ));
        ?>
          
        <hr />
        
        <fieldset class="optional">
          <legend>Optional features</legend>
          
          <?php
            echo $form->input('private',array(
              'label' => __('Private',true).' <span>('.__('Visible only to your company',true).')</span>'
            ));
          ?>
          
          <?php
            echo $form->input('description',array('label'=>__('List description or notes about this list',true)));
          ?>
          
          <?php
            if(!empty($milestoneOptions))
            {
              echo $form->input('milestone_id',array(
                'label'=>__('Does this list relate to a milestone?',true),
                'options'=>$milestoneOptions,
                'empty' => true
              ));
            }
          ?>
        </fieldset>
        
        <hr />
          
        <?php
          echo $form->submit(__('Create this list',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
          echo $form->end();
        ?>

        
      </div>
    </div>


  </div>
  <div class="col right">
    <!-- Empty space -->
  </div>
</div>
