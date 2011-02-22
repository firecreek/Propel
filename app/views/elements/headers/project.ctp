
<div id="launchbar">
  <ul class="sf-menu">
    <li class="first"><?php echo $html->link('Opencamp','/'); ?></li>
    <li class="account"><?php echo $html->link($auth->read('Account.name'),array('project'=>false,'controller'=>'accounts','action'=>'index')); ?></li>
    <li class="project">
      <?php
        echo $html->link(__('Project',true).': '.$auth->read('Project.name'),array('project'=>false,'controller'=>'accounts','action'=>'index'));
      ?>
      <?php
        echo $this->element('headers/_projects');
      ?>
    </li>
  </ul>
</div>


<header>
  <h1><?php echo $auth->read('Project.name'); ?> <span><?php echo $auth->read('Company.name'); ?></span></h1>

  <nav id="account">
      <ul>
          <li><span><?php echo $session->read('AuthAccount.Person.first_name').' '.$session->read('AuthAccount.Person.last_name'); ?></span></li>
          <li><?php echo $html->link(__('Project Settings',true),array('controller'=>'projects','action'=>'edit')); ?></li>
          <li><?php echo $html->link(__('My info',true),array('controller'=>'people','action'=>'edit',$session->read('AuthAccount.Person.id'))); ?></li>
          <li><?php echo $html->link(__('Sign out',true),array('account'=>false,'project'=>false,'controller'=>'users','action'=>'logout')); ?></li>
      </ul>
  </nav>

  <?php
    if(!isset($activeMenu))
    {
      $activeMenu = Inflector::underscore($this->name);
    }
  ?>

  <nav class="main top tabs">
    <?php
      $menu = array(
        'projects'    => array('name'=>__('Overview',true),'url'=>array('controller'=>'projects','action'=>'index')),
        'posts'       => array('name'=>__('Messages',true),'url'=>array('controller'=>'posts','action'=>'index')),
        'todos'       => array('name'=>__('To-Dos',true),'url'=>array('controller'=>'todos','action'=>'index')),
        'milestones'  => array('name'=>__('Milestones',true),'url'=>array('controller'=>'milestones','action'=>'index')),
      );
      echo $layout->menu($menu,array('permissions'=>'Project','active'=>$activeMenu));
    ?>
  </nav>

  <nav class="extra top tabs">
    <?php
      $menu = array(
        'companies'   => array('name'=>__('People & Permissions',true),'url'=>array('controller'=>'companies','action'=>'index')),
        'search'      => array('name'=>__('Search',true),'url'=>array('controller'=>'search','action'=>'index'))
      );
      echo $layout->menu($menu,array('permissions'=>'Project','active'=>$activeMenu));
    ?>
  </nav>
  
</header>
