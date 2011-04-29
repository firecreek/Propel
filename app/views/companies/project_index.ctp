
<div class="box">
  <div class="banner">
    <h2><?php __('People on this project'); ?> <?php echo $html->link(__('Add people, remove people, change permissions',true),array('action'=>'permissions')); ?></h2>
  </div>
  <div class="content">
    
    <?php
      echo $this->element('people/list',array('records'=>$records));
    ?>
    
  </div>
</div>
