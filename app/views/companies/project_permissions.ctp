<?php

  $html->css('pages/project_permissions', null, array('inline'=>false));
  
?>

<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Add people, remove people, and change permissions'); ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $layout->button(__('Add another company to this project',true),array('action'=>'add'),'large add margin');
        ?>
      
        <?php
          echo $session->flash();
        ?>
        
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
        
          <table class="std">
            <thead>
              <tr>
                <th><?php echo $company['Company']['name']; ?></th>
                <th class="links">
                  <?php if(!$myCompany): ?>
                    <?php echo $html->link(__('Remove company from this project',true),array('action'=>'delete',$company['Company']['id']),array('class'=>'unimportant','confirm'=>__('Are you sure you want to remove this company and every person from this project?',true))); ?>
                  <?php endif; ?>
                </th>
              </tr>
            </thead>
            <?php if(isset($company['People']) && !empty($company['People'])): ?>
            <tbody>
              <?php foreach($company['People'] as $person): ?>
                <?php
                  $pid = $person['id'];
                ?>
                <tr>
                  <td class="name">
                    <?php
                      $disabled = false;
                      if($person['company_owner']) { $disabled = true; }
                    
                      echo $form->input('Person.'.$pid,array('type'=>'checkbox','checked'=>true,'label'=>$person['full_name'],'disabled'=>$disabled));
                    ?>
                  </td>
                  <td class="permissions">
                    <?php
                      if(!$person['company_owner'])
                      {
                        echo $form->input('Permissions.'.$pid,array(
                          'type' => 'radio',
                          'label' => false,
                          'legend'    => false,
                          'options' => array(
                            1 => __('Messages & Files',true),
                            2 => __('...plus To-Dos',true),
                            3 => __('...plus Milestones',true),
                          )
                        ));
                      }
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <?php endif; ?>
            <tfoot>
              <td class="important" colspan="2">
                <?php echo $html->link(__('Add a new person',true),array('action'=>'add','company_id'=>$company['Company']['id'])); ?> to <?php echo $company['Company']['name']; ?>
              </td>
            </tfoot>
          </table>
        
        <?php endforeach; ?>
        
        <?php
          echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel and go back',true),array('action'=>'index') ) ));
        ?>
        
      </div>
    </div>
    
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
        
        <p>‘<strong><?php __('Plus To-dos'); ?></strong>’ <?php __('means the person can also add to-do items.'); ?></p>

        <p>‘<strong><?php __('Plus Milestones'); ?></strong>’ <?php __('means they can add both to-dos and milestones.'); ?></p>
      </div>
    </div>
  
      
  
  </div>
  
</div>
