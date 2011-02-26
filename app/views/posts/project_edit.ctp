
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
