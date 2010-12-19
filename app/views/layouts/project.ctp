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
      <p>
        <?php __('OpenCamp'); ?>
        <?php echo $html->link(__('Back to Projects',true),array('project'=>false,'controller'=>'accounts','action'=>'index')); ?>
      </p>
    </div>

    <header>
      <h1><?php echo $session->read('AuthAccount.Project.name'); ?></h1>
      
      <nav id="account">
          <ul>
              <li><?php echo $session->read('AuthAccount.Person.full_name'); ?></li>
              <li><?php echo $html->link(__('Project Settings',true),array('controller'=>'project','action'=>'edit')); ?></li>
              <li><?php echo $html->link(__('My info',true),array('controller'=>'people','action'=>'edit',$session->read('AuthAccount.Person.id'))); ?></li>
              <li><?php echo $html->link(__('Sign out',true),array('project'=>false,'account'=>false,'controller'=>'users','action'=>'logout')); ?></li>
          </ul>
      </nav>
      
      <?php
        $active = $this->name;
      ?>
      
      <nav class="main top tabs">
        <?php
          $menu = array(
            'overview' => array('name'=>'Overview','url'=>array('controller'=>'accounts','action'=>'index'))
          );
          
          echo $layout->menu($menu);
        ?>
        <?php /*
        <ul>
          <li<?php if($active == 'Projects') { echo ' class="active"'; } ?>><?php echo $html->link(__('Overview',true),array('controller'=>'projects','action'=>'index')); ?></li>
          <li<?php if($active == 'Messages') { echo ' class="active"'; } ?>><?php echo $html->link(__('Messages',true),array('controller'=>'messages','action'=>'index')); ?></li>
          <li<?php if($active == 'Todos') { echo ' class="active"'; } ?>><?php echo $html->link(__('To-Dos',true),array('controller'=>'todos','action'=>'index')); ?></li>
          <li<?php if($active == 'Milestones') { echo ' class="active"'; } ?>><?php echo $html->link(__('Milestones',true),array('controller'=>'milestones','action'=>'index')); ?></li>
          <li<?php if($active == 'Times') { echo ' class="active"'; } ?>><?php echo $html->link(__('Time',true),array('controller'=>'times','action'=>'index')); ?></li>
        </ul>*/ ?>
      </nav>
      
      <nav class="extra top tabs">
        <ul>
          <li<?php if($active == 'Companies') { echo ' class="active"'; } ?>><?php echo $html->link(__('People & Permissions',true),array('controller'=>'companies','action'=>'index')); ?></li>
          <li<?php if($active == 'Search') { echo ' class="active"'; } ?>><?php echo $html->link(__('Search',true),array('controller'=>'search','action'=>'index')); ?></li>
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
