
<div class="box">
  <div class="banner">
    <h2><?php __('People on this project'); ?></h2>
    <ul class="right">
      <li class="permissions"><?php echo $html->link(__('Add people, remove people, change permissions',true),array('action'=>'permissions'),array('class'=>'important')); ?></li>
    </ul>
  </div>
  <div class="content" id="CompanyPermissions">
      
    <?php
      echo $this->element('companies/list');
    ?>
  
  </div>
</div>
