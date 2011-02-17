
<div class="cols">
  <div class="col left">


    <div class="box">
      <div class="banner">
        <h2><?php __('Post a new message'); ?></h2>
      </div>
      <div class="content">
        
        <?php
          echo $session->flash();
        ?>
        
        <?php
          echo $form->create('Post',array('url'=>$this->here,'class'=>'block'));
        ?>
        
        <fieldset class="light">
        <?php
          echo $form->input('title',array('label'=>__('Title',true),'div'=>'input text full-width'));
          echo $form->input('body',array('label'=>false,'div'=>'input textarea full-width'));
          echo $form->input('private',array(
            'label' => __('Private',true).' <span>('.__('Visible only to your company',true).')</span>'
          ));
        ?>
        </fieldset>
        
        <hr />
      
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
          
        <hr />
          
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
