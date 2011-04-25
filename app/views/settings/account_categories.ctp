<?php

  $html->css('pages/settings', null, array('inline'=>false));
  
?>


<div class="box" id="SettingCategories">
  <div class="banner">
    <?php echo $this->element('settings/menu'); ?>
  </div>
  <div class="content">    

    <h3 class="tight"><?php __('Default message categories'); ?></h3>
    <p class="unimportant"><?php __('These are the message categories that every new project starts with'); ?></p>
  
    <?php
      echo $this->element('categories/list',array('type'=>'post','records'=>$categories['post']));
    ?>


    <h3 class="tight"><?php __('Default file categories'); ?></h3>
    <p class="unimportant"><?php __('These are the file categories that every new project starts with.'); ?></p>
  
    <?php
      echo $this->element('categories/list',array('type'=>'file','records'=>$categories['file']));
    ?>
    
  </div>
</div>



<?php
  //Make it listable
  echo $javascript->codeBlock("
    $('.listable').listable({
    });
  ");
?>
