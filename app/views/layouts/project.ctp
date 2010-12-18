<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?> - <?php __('OpenCamp'); ?></title>
  <?php
    echo $html->css(array(
      'reset',
      '960',
      'type',
      'elements',
      'account'
    ));
    echo $scripts_for_layout;
  ?>
</head>

<body>


    <div id="launchbar">
      <p><?php __('OpenCamp'); ?></p>
    </div>

    <header>
      <h1><?php echo $session->read('AuthAccount.Project.name'); ?></h1>
      
      <nav id="account">
          <ul>
              <li><?php echo $session->read('AuthAccount.Person.first_name').' '.$session->read('AuthAccount.Person.last_name'); ?></li>
              <li><?php echo $html->link(__('My info',true),array('controller'=>'people','action'=>'edit',$session->read('Auth.Person.id'))); ?></li>
              <li><?php echo $html->link(__('Sign out',true),array('account'=>false,'controller'=>'users','action'=>'logout')); ?></li>
          </ul>
      </nav>
      
      <?php
        $active = $this->name;
      ?>
      
      <nav class="main top tabs">
        <ul>
          <li<?php if($active == 'Accounts') { echo ' class="active"'; } ?>><?php echo $html->link(__('Dashboard',true),array('controller'=>'accounts','action'=>'index')); ?></li>
          <li<?php if($active == 'Todos') { echo ' class="active"'; } ?>><?php echo $html->link(__('To-Dos',true),array('controller'=>'todos','action'=>'index')); ?></li>
          <li<?php if($active == 'Milestones') { echo ' class="active"'; } ?>><?php echo $html->link(__('Milestones',true),array('controller'=>'milestones','action'=>'index')); ?></li>
        </ul>
      </nav>
      
      <nav class="extra top tabs">
        <ul>
          <li<?php if($active == 'Companies') { echo ' class="active"'; } ?>><?php echo $html->link(__('All People',true),array('controller'=>'companies','action'=>'index')); ?></li>
          <li<?php if($active == 'Search') { echo ' class="active"'; } ?>><?php echo $html->link(__('Search',true),array('controller'=>'search','action'=>'index')); ?></li>
          <li<?php if($active == 'Settings') { echo ' class="active"'; } ?>><?php echo $html->link(__('Settings',true),array('controller'=>'settings','action'=>'index')); ?></li>
        </ul>
      </nav>
      
    </header>

    <div id="main">
    <?php
      echo $content_for_layout;
    ?>
    </div>
    
    
    <?php echo $this->element('account/footer'); ?>

</body>
</html>
