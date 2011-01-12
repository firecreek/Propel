{ success:true, reload:"<?php
  if(strpos(Controller::referer(),'/view/'))
  {
    echo $html->url(array('action'=>'view',$id,'?'=>array('highlight'=>$id)));
  }
  {
    echo $html->url(array('action'=>'index','?'=>array('highlight'=>$id)));
  }
?>" }
