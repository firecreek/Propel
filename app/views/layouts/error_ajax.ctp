<?php

 Configure::write('debug',0);

 echo $this->Javascript->codeBlock("
  Account.errorShow('".$this->Javascript->escapeString($content_for_layout)."');
 ");
 

?>
