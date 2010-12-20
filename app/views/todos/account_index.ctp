
<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s to-do items across all projects',$name.'\'s')); ?></h2>
    <?php echo $this->element('todos/banner-form'); ?>
  </div>
  <div class="content">
  
    <?php echo $session->flash(); ?>
  
    <h3><?php __(sprintf('There are no to-dos assigned to %s.', ($responsibleSelf ? 'You' : $name) )); ?></h3>
    <p><?php __('Choose another person with the pulldown to the right.'); ?></p>
    
  </div>
</div>
