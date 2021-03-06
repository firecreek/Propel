<?php
  $html->css('rte', null, array('inline'=>false));
  $javascript->link('jquery/jquery-rte.js', false);
  
?>

<fieldset class="light details">
<?php
  echo $form->input('title',array('label'=>__('Title',true),'div'=>'input text full-width'));
  
  echo $this->element('categories/select',array(
    'addUrl' => array('controller'=>'categories','action'=>'add','post')
  ));

  echo $form->input('body',array('type'=>'textarea','label'=>false,'class'=>'wysiwyg','div'=>'input textarea editor full-width'));
  echo $form->hidden('format',array('value'=>'textile'));
  
  echo $form->input('private',array(
    'label' => __('Private',true).' <span>('.__('Visible only to your company',true).')</span>',
    'class' => 'private'
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
  echo $this->element('people/subscribe');
?>
