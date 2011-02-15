
<?php
  $active = $this->name;
?>

<nav class="main top tabs">
  <?php
    $menu = array(
      'projects'    => array('name'=>__('Overview',true),'url'=>array('controller'=>'projects','action'=>'index')),
      'posts'       => array('name'=>__('Messages',true),'url'=>array('controller'=>'posts','action'=>'index')),
      'todos'       => array('name'=>__('To-Dos',true),'url'=>array('controller'=>'todos','action'=>'index')),
      'milestones'  => array('name'=>__('Milestones',true),'url'=>array('controller'=>'milestones','action'=>'index')),
    );
    echo $layout->menu($menu,array('permissions'=>'Project'));
  ?>
</nav>

<nav class="extra top tabs">
  <?php
    $menu = array(
      'companies'   => array('name'=>__('People & Permissions',true),'url'=>array('controller'=>'companies','action'=>'index')),
      'search'      => array('name'=>__('Search',true),'url'=>array('controller'=>'search','action'=>'index'))
    );
    echo $layout->menu($menu,array('permissions'=>'Project'));
  ?>
</nav>
