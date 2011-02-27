
<div class="personal-banner">
  <h2><?php echo $html->link(__('Edit your personal information',true),array('project'=>false,'controller'=>'users','action'=>'edit'),array('class'=>'strong')); ?></h2>
  <p><?php __('Change your name, photo, email address, username or password.'); ?></p>
    
  <div class="card person">
    <div class="banner">
      <h2>Your Opencamp ID</h2>
    </div>
    <div class="content">
      <div class="avatar">
        <?php echo $this->Layout->avatar($this->Auth->read('Person.user_id'),'small'); ?>
      </div>
      <div class="detail">
        <h3><?php echo $this->Auth->read('Person.full_name'); ?></h3>
        <p class="email"><?php echo $this->Auth->read('Person.email'); ?></p>
        <p class="username">
        Username: <?php echo $this->Session->read('Auth.User.username'); ?><br />
        Password: ••••••
        </p>
      </div>
    </div>
  </div>
  
</div>
