<?php

  $javascript->link('listable.js', false);
  $javascript->link('projects/companies_permissions.js', false);
  
  $html->css('projects/companies_permissions', null, array('inline'=>false));
  
?>

<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('People on this project'); ?></h2>
        <?php echo $layout->button(__('Add another company to this project',true),array('action'=>'add'),'add large'); ?>
      </div>
      <div class="content" id="CompanyPermissions">
        
        <?php
          echo $form->create('Permissions',array('url'=>$this->here));
        ?>
        
        <?php foreach($records as $company): ?> 
            
          <?php
            //My company?
            $myCompany = false;
            if($company['Company']['id'] == $session->read('Auth.Company.id'))
            {
              $myCompany = true;
            }
          ?>
          
        <div class="section">
          <div class="banner">
            <h3><?php echo $company['Company']['name']; ?> <?php if($myCompany) { echo '('.__('Your company',true).')'; } ?></h3>
          </div>
          <div class="content">
          

            <div class="listable inline-right">

              <div class="items">
                <?php
                  //
                  foreach($company['People'] as $person)
                  {                 
                    $name = $person['full_name'];
                  
                    $disabled = false;
                    if($person['company_owner'])
                    {
                      $disabled = true;
                      $name .= ' ('.__('Account owner',true).')';
                    }
                    
                    $checked = false;
                    if($person['_access']) { $checked = true; }
                    
                    echo $listable->item('Category',$person['id'],$name,array(
                      'position'  => false,
                      'checkbox'  => false,
                      'edit'      => true,
                      'comments'  => false,
                      'delete'    => false,
                      'editUrl'   => $html->url(array('controller'=>'people','action'=>'edit',$person['id']))
                    ));
                  }
                ?>
              </div>
              
              <div class="add-record-container">
                <div class="add-record-link">
                  <?php
                    echo $html->link(__('Add a new person',true),array('controller'=>'people','action'=>'add',$company['Company']['id']),array('class'=>'important'));
                  ?>
                </div>
              </div>

            </div>
            
          </div>
        </div>
        
        
        <?php endforeach; ?>
        
        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel and go back',true),array('action'=>'index') ) ));
          echo $form->end();
        ?>
        
      </div>
    </div>
    
    <?php
      //Make it listable
      echo $javascript->codeBlock("
        $('.listable').listable({
        });
      ");
    ?>
    
  </div>
  
  <div class="col right">
  
  
    <div class="area">
      <div class="banner"><h3><?php __('Giving / Removing Access'); ?></h3></div>
      <div class="content">
        <p><?php __('Check the box in front of someone\'s name to give them access to this project. Uncheck to remove access. People without access won\'t see the project.'); ?></p>
      </div>
    </div>
  
  
    <div class="area">
      <div class="banner"><h3><?php __('Additional Permissions'); ?></h3></div>
      <div class="content">
        <p><?php __('People from other companies with access to this project can always post messages, leave comments, and upload files.'); ?></p>
        
      </div>
    </div>
  
      
  
  </div>
  
</div>
