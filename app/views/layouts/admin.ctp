<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?> - <?php __('Propel'); ?></title>
  <?php
    echo $html->css(array(
      'reset',
      'type',
      'elements',
      'admin',
    ));
    echo $javascript->link(array(
      'jquery/jquery',
      'jquery/jquery-ui'
    ));
    echo $scripts_for_layout;
  ?>
</head>
<body>

  <header>
    <h1><?php echo $html->link('Propel','/'); ?> &gt; Admin Area</h1>
  </header>

  <nav>
    <?php
      $menu = array(
        'dashboard' => array('name'=>__('Dashboard',true),'url'=>array('controller'=>'dashboard','action'=>'index')),
        'permissions' => array('name'=>__('Permissions',true),'url'=>array('controller'=>'permissions','action'=>'index')),
        'settings' => array('name'=>__('Settings',true),'url'=>array('controller'=>'settings','action'=>'index'))
      );
      echo $layout->menu($menu);
    ?>
  </nav>
  
  <div id="main"><?php echo $content_for_layout; ?></div>

</body>
</html>
