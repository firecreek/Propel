
<?php if(isset($responsibleName) && !empty($responsibleName)): ?>
  <p>
    <?php echo __('Showing to-dos assigned to',true) . ' ' . $responsibleName; ?> -
    <?php
      $url = array('action'=>'index');
      $text = __('Show all to-dos',true);
    
      if(isset($id))
      {
        $url = array('action'=>'view',$id);
        $text = __('Show all to-dos for this list',true);
      }
      
      echo $html->link($text,$url);
      
    ?>
  </p>
<?php endif; ?>
