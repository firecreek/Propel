<?php

  if(!file_exists($dest)) { die('File does not exist, '.$dest); }
    
  header("Content-type: ".$type."\n");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($dest).";\n");

  readfile($dest);

?>
