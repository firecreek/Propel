
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
