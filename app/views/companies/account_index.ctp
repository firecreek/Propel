
<div class="box">
  <div class="banner">
    <h2><?php __('Every company and person in your system'); ?></h2>
    <?php echo $layout->button(__('Add a new company',true),array('action'=>'add'),'add large'); ?>
  </div>
  <div class="content">
  
    <?php
      echo $this->element('people/list',array('records'=>$records));
    ?>
    
  </div>
</div>
