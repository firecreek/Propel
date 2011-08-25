<?php
  $html->css('settings', null, array('inline'=>false));
?>

<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <?php echo $this->element('settings/menu'); ?>
      </div>
      <div class="content">
        
        <div class="logos clearfix">
        
          <div class="row account">
            <p class="type"><strong><?php __('Your logo'); ?></strong> (<?php echo $html->link(__('Change',true),array('action'=>'logo_edit','account')); ?>)</p>
            <p class="info"><?php __('Your logo appears on the sign in screen, the Dashboard, and Overview pages.'); ?></p>
            <div class="image"><?php
              $imageFile = ASSETS_DIR.DS.'accounts'.DS.$auth->read('Account.id').DS.'logo'.DS.'account.png';
              if(file_exists($imageFile))
              {
                echo $image->resize($imageFile,230,110);
              }
              else
              {
                echo '<span>'.__('No logo uploaded',true).'</span>';
              }
            ?></div>
          </div>
          
          
          <div class="row apple">
            <p class="type"><strong><?php __('iPhone/iPad icon'); ?></strong> (<?php echo $html->link(__('Change',true),array('action'=>'logo_edit','apple')); ?>)</p>
            <p class="info"><?php __('Appears when you add a home screen icon on your iPhone, iPad, or iPod Touch (apple-touch-icon.png).'); ?></p>
            <div class="image"><?php
              $imageFile = ASSETS_DIR.DS.'accounts'.DS.$auth->read('Account.id').DS.'logo'.DS.'apple.png';
              
              if(file_exists($imageFile))
              {
                echo $image->resize($imageFile,114,114);
              }
              else
              {
                echo $html->image('/apple-touch-icon.png');
              }
            ?></div>
          </div>
          
          <div class="row shortcut">
            <p class="type"><strong><?php __('Shortcut icon'); ?></strong> (<?php echo $html->link(__('Change',true),array('action'=>'logo_edit','shortcut')); ?>)</p>
            <p class="info"><?php __('Your shortcut icon, or favicon, appears in some web browsers on the address bar, tabs or bookmarks menu.'); ?></p>
            <div class="image"><?php
              $imageFile = ASSETS_DIR.DS.'accounts'.DS.$auth->read('Account.id').DS.'logo'.DS.'shortcut.ico';
              
              if(file_exists($imageFile))
              {
                echo $image->resize($imageFile,16,16);
              }
              else
              {
                echo $html->image('/favicon-16.ico');
              }
            ?></div>
          </div>
          
        </div>
        
      </div>
        

  </div>
  <div class="col right">

    <p>Temp</p>
  
  </div>
</div>
