
<?php if(isset($responsibleName) && !empty($responsibleName)): ?>
  <p>

    <?php
      if(isset($responsibleName) && isset($dueName))
      {
        echo __('Showing to-dos assigned to',true).' '.$responsibleName.' '.__('due',true).' '.$dueName;
      }
      elseif(isset($responsibleName))
      {
        echo __('Showing to-dos assigned to',true).' '.$responsibleName;
      }
      elseif(isset($dueName))
      {
        echo __('Showing to-dos due',true).' '.$dueName;
      }
    ?>
    -
    <?php
      $url = array('action'=>'index','?'=>array('responsible'=>'','due'=>''));
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
