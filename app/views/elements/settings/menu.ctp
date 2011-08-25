<?php

  $menu = array(
    'index'       => array('name'=>__('Settings',true),'url'=>array('controller'=>'settings','action'=>'index')),
    'logo'        => array('name'=>__('Logo & Icons',true),'url'=>array('controller'=>'settings','action'=>'logo')),
    'appearance'  => array('name'=>__('Theme',true),'url'=>array('controller'=>'settings','action'=>'appearance')),
    'categories'  => array('name'=>__('Categories',true),'url'=>array('controller'=>'settings','action'=>'categories')),
  );
  
  $active = str_replace('account_','',$this->action);
  
  echo $layout->menu($menu,array('permissions'=>'Account','active'=>$active),array('class'=>'left'));
  
?>
