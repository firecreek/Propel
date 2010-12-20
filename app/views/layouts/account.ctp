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
  <style type="text/css" media="screen"><!--
    <?php echo $this->element('style'); ?>
  --></style>
</head>

<body>


    <div id="launchbar">
      <p><?php __('OpenCamp'); ?></p>
    </div>

    <header>
      <h1><?php echo $session->read('AuthAccount.Account.name'); ?></h1>
      
      <nav id="account">
          <ul>
              <li><span><?php echo $session->read('AuthAccount.Person.first_name').' '.$session->read('AuthAccount.Person.last_name'); ?></span></li>
              <li><?php echo $html->link(__('My info',true),array('controller'=>'people','action'=>'edit',$session->read('AuthAccount.Person.id'))); ?></li>
              <li><?php echo $html->link(__('Sign out',true),array('account'=>false,'controller'=>'users','action'=>'logout')); ?></li>
          </ul>
      </nav>
      
      <?php
        $active = $this->name;
      ?>
      
      <nav class="main top tabs">
        <?php
          $menu = array(
            'accounts' => array('name'=>__('Dashboard',true),'url'=>array('controller'=>'accounts','action'=>'index')),
            'todos' => array('name'=>__('To-Dos',true),'url'=>array('controller'=>'todos','action'=>'index')),
            'milestones' => array('name'=>__('Milestones',true),'url'=>array('controller'=>'milestones','action'=>'index')),
          );
          echo $layout->menu($menu,array('permissions'=>'Account'));
        ?>
      </nav>
      
      <nav class="extra top tabs">
        <?php
          $menu = array(
            'companies' => array('name'=>__('All People',true),'url'=>array('controller'=>'companies','action'=>'index')),
            'search' => array('name'=>__('Search',true),'url'=>array('controller'=>'search','action'=>'index')),
            'settings' => array('name'=>__('Settings',true),'url'=>array('controller'=>'settings','action'=>'index')),
          );
          echo $layout->menu($menu,array('permissions'=>'Account'));
        ?>
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
