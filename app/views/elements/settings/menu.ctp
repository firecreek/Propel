<?php

  $menu = array(
    'index'    => array('name'=>__('Settings',true),'url'=>array('controller'=>'settings','action'=>'index')),
    'appearance'  => array('name'=>__('Colour scheme',true),'url'=>array('controller'=>'settings','action'=>'appearance')),
    'categories'  => array('name'=>__('Categories',true),'url'=>array('controller'=>'settings','action'=>'categories')),
  );
  
  $active = str_replace('account_','',$this->action);
  
  echo $layout->menu($menu,array('permissions'=>'Account','active'=>$active),array('class'=>'left'));
  
?>
