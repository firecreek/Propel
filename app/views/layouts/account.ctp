<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?></title>
  <?php
    echo $html->css(array(
      'style',
      'account',
      'listable'
    ));
    echo $javascript->link(array(
      'jquery/jquery',
      'jquery/jquery-ui',
      'jquery/jquery-ajaxsubmit',
      'jquery/jquery-oc-calendar',
      'jquery/jquery-oc-dialog',
      'jquery/superfish',
      'jquery/supersubs',
      'modernizr',
      'account'
    ));
    echo $scripts_for_layout;
  ?>
  <style type="text/css" media="screen"><!--
    <?php echo $this->element('style'); ?>
  --></style>
</head><?php
  //Body class
  $bodyClass = $this->name.' '.$this->action.' '.$this->params['prefix'];
?><body class="<?php echo $bodyClass; ?>">




  <div id="launchbar">
    <nav id="account">
      <?php
        $menu = array(
          'accounts' => array('name'=>$this->Auth->read('Account.name').' '.__('Dashboard',true),'url'=>array('account'=>true,'controller'=>'accounts','action'=>'index')),
          'projects' => array('name'=>__('Projects',true),'url'=>array('account'=>true,'controller'=>'projects','action'=>'index')),
          'todos' => array('name'=>__('To-Dos',true),'url'=>array('account'=>true,'controller'=>'todos','action'=>'index')),
          'milestones' => array('name'=>__('Milestones',true),'url'=>array('account'=>true,'controller'=>'milestones','action'=>'index')),
          'companies' => array('name'=>__('People',true),'url'=>array('account'=>true,'controller'=>'companies','action'=>'index')),
          'search' => array('name'=>__('Search',true),'url'=>array('account'=>true,'controller'=>'search','action'=>'index')),
          /*'templates' => array('name'=>__('Templates',true),'url'=>array('controller'=>'templates','action'=>'index')),*/
          'settings' => array('name'=>__('Settings',true),'url'=>array('account'=>true,'controller'=>'settings','action'=>'index')),
        );
        echo $layout->menu($menu,array('permissions'=>'Account'));
      ?>
    </nav>
    <nav id="user">
      <ul class="sf-menu">
        <li><span><?php echo $session->read('AuthAccount.Person.first_name').' '.$session->read('AuthAccount.Person.last_name'); ?></span></li>
        <li><?php echo $html->link(__('My details',true),array('controller'=>'people','action'=>'edit',$session->read('AuthAccount.Person.id'))); ?></li>
        <li><?php echo $html->link(__('Sign out',true),array('account'=>false,'controller'=>'users','action'=>'logout')); ?></li>
      </ul>
    </nav>
  </div>


  <header>
    <h1>
      <?php
        if($this->Auth->read('Project.name'))
        {
          echo $this->Auth->read('Project.name');
          echo '<span>'.$this->Auth->read('Account.name').'</span>';
        }
        else
        {
          echo $this->Auth->read('Account.name');
        }
      ?>
    </h1>


    <?php
      if(!isset($activeMenu))
      {
        $activeMenu = Inflector::underscore($this->name);
      }
    ?>


    
    <?php if($this->params['prefix'] == 'project'): ?>
      <nav class="main top tabs">
        <?php
          $menu = array(
            'projects'    => array('name'=>__('Overview',true),'url'=>array('project'=>true,'controller'=>'projects','action'=>'index')),
            'posts'       => array('name'=>__('Messages',true),'url'=>array('project'=>true,'controller'=>'posts','action'=>'index')),
            'todos'       => array('name'=>__('To-Dos',true),'url'=>array('project'=>true,'controller'=>'todos','action'=>'index')),
            'milestones'  => array('name'=>__('Milestones',true),'url'=>array('project'=>true,'controller'=>'milestones','action'=>'index')),
          );
          echo $layout->menu($menu,array('permissions'=>'Project'));
        ?>
      </nav>
      <nav class="extra top tabs">
        <?php
          $menu = array(
            'search'      => array('name'=>__('Search',true),'url'=>array('project'=>true,'controller'=>'search','action'=>'index')),
            'companies'   => array('name'=>__('People & Permissions',true),'url'=>array('project'=>true,'controller'=>'companies','action'=>'index')),
            'settings'    => array('name'=>__('Project Settings',true),'url'=>array('project'=>true,'controller'=>'projects','action'=>'edit')),
          );
          echo $layout->menu($menu,array('permissions'=>'Project'));
        ?>
      </nav>
    <?php endif; ?>
    

  </header>




    <div id="main">
      <?php echo $session->flash(); ?>
      <?php echo $content_for_layout; ?>
    </div>
    
    <footer>
      <p><?php __('Managed with'); ?> <?php echo $html->link('Propel','http://www.propelhq.com?ref=accfoot'); ?>.</p>
    </footer>


</body>
</html>
