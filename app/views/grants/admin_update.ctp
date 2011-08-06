<?php
    if ($success == 1) {
    
      $state = true;
      if($switch) { $state = false; }
    
      echo $this->Html->link($this->Layout->status($switch),array('action'=>'update','aco'=>$acoId,$id,$state),array('escape'=>false,'class'=>'toggle'));
    
    } else {
        __('error');
    }

    Configure::write('debug', 0);
?>