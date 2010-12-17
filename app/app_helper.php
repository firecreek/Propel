<?php

  class AppHelper extends Helper
  {
  
    public function url($url = null, $full = false)
    {
      if (is_array($url))
      { 
        if(
          isset($this->params['accountSlug']) &&
          (!isset($url['account']) || (isset($url['account']) && $url['account'] !== false)))
        {
          $url['accountSlug'] = $this->params['accountSlug'];
        }
      } 
      return parent::url($url, $full); 
    }
  
  }


?>
