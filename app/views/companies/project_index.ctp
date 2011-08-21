
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('People on this project'); ?></h2>
        <?php echo $layout->button(__('Add another company to this project',true),array('action'=>'add'),'add large'); ?>
      </div>
      <div class="content" id="CompanyPermissions">
      
          
        <?php
          echo $this->element('companies/list');
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
        
      </div>
    </div>
  
      
  
  </div>
  
</div>
