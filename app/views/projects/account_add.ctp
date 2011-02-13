<?php

  $javascript->link('projects_add.js', false);
  $html->css('pages/projects_add', null, array('inline'=>false));
  
?>


<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Create a new project'); ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $session->flash();
          echo $form->create('Project',array('url'=>$this->here,'class'=>'block larger'));
        ?>
        
        <?php
          echo $form->input('name',array(
            'label' => __('Name the project',true).' <span>('.__('"Home page redesign" or "Marketing ideas", etc.',true).')</span>',
            'div'   => 'input text strong'
          ));
          //echo $form->input('description',array('label'=>__('Description',true),'after'=>'<small>'.__('(Not required)',true).'</small>'));
        ?>
        
        
        <div id="PermissionProject" style="display:none;">
          <h3><?php __('Who should be able to access this project?'); ?></h3>
          <p>
            <?php __('You can add or remove clients or companies from a project at any time,'); ?><br />
            <?php __('so don\'t worry if you aren\'t sure right now.'); ?>
          </p>
          
          <div class="options">
            <?php
              echo $form->input('Permission.action',array(
                'type' => 'radio',
                'label' => false,
                'legend' => false,
                'default' => 'own',
                'options' => array(
                  'own' => sprintf(__('Just our company (%s) for now.',true),$this->Auth->read('Company.name')),
                  'add' => __('I\'d like to give a client or another company access to this project too.',true)
                )
              ));
            ?>
            
            <div id="PermissionAddInputs" class="option" style="display:none;">
            
              <?php
                echo $form->hidden('Permission.option',array('value'=>'select'));
              ?>
          
              <?php
                $companies = $this->Auth->read('Companies');
                $companyOptions = array();
                foreach($companies as $company)
                {
                  if($company['Company']['id'] != $this->Auth->read('Company.id'))
                  {
                    $companyOptions[$company['Company']['id']] = $company['Company']['name'];
                  }
                }
              ?>
            
              <div id="PermissionSelectCompany" class="option" style="display:none;">
                <p>
                  <strong><?php __('Which company?') ?></strong>
                  <span>(<?php __('or') ?> <?php echo $html->link(__('create a new company',true),'#'); ?>)</span>
                </p>
                <?php echo $form->input('Permission.company_id',array('label'=>false,'empty'=>true,'options'=>$companyOptions)); ?>
                <?php echo $form->input('Permission.company_all',array('checked'=>true,'type'=>'checkbox','label'=>__('Give everyone from this company access to this project.',true))); ?>
              </div>
              
              <div id="PermissionCreateCompany" class="option" style="display:none;">
                <p>
                  <strong><?php __('New company name') ?></strong>
                  <span>(<?php __('or') ?> <?php echo $html->link(__('select an existing company',true),'#'); ?>)</span>
                </p>
                <?php echo $form->input('Permission.company_new',array('label'=>false)); ?>
              </div>
              
            </div>
          </div>
        </div>
        
        <?php
          //Show permission project block if JS is enabled
          echo $javascript->codeBlock("
            $('#PermissionProject').show();
            ProjectsAdd.companyCount = ".count($companyOptions).";
          ");
        ?>

        
        <hr />
        
        <?php  
          echo $form->submit(__('Create this project',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'accounts','action'=>'index') ) ));
          echo $form->end();
        ?>
      </div>
    </div>
    
  </div>
  <div class="col right">
    <!-- nothing here -->
  </div>
</div>
