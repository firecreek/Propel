<?php

  $javascript->link('projects/companies_add.js', false);
  $html->css('projects/companies_add', null, array('inline'=>false));
  
?>

<div class="box" id="CompanyAdd">
  <div class="banner">
    <h2><?php __('Which company do you want to add to the project?'); ?></h2>
  </div>
  <div class="content">
    
    <p><?php __('After you add a company you\'ll be able to specify which people from this company can access the project.'); ?></p>
    
    <?php
      echo $form->create('Company',array('url'=>$this->here,'class'=>'block'));
      echo $form->hidden('Permission.option',array('value'=>'select'));
    ?>
    
    <div id="CompanyAddNew" class="option">
      <p>
        <strong><?php __('New company name') ?></strong>
        <span>(<?php __('or') ?> <?php echo $html->link(__('select an existing company',true),'#'); ?>)</span>
      </p>

      <?php
        //Create
        echo $form->input('name',array('label'=>false,'div'=>'input text strong'));
      ?>
    </div>
      
    <?php if(!empty($companies)): ?>
      <div id="CompanyAddExisting" class="option">
        <p>
          <strong><?php __('Choose a company') ?></strong>
          <span>(<?php __('or') ?> <?php echo $html->link(__('create a new company',true),'#'); ?>)</span>
        </p>
        <?php
          //Choose
          echo $form->input('id',array('options'=>$companies,'empty'=>true,'label'=>false,'div'=>'input text strong'));
          
          //
          echo $form->input('Permission.add_people',array('checked'=>true,'type'=>'checkbox','label'=>__('Give everyone from this company access to this project now.',true)));
        ?>
      </div>
    <?php endif; ?>
    
    
    <?php
      echo $form->submit(__('Add company',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'permissions') ) ));
      echo $form->end();
    ?>
  </div>
</div>
