<?php

  class AppHelper extends Helper
  {
  
    public function url($url = null, $full = false)
    {
      if(is_array($url))
      { 
        //Add account slug
        if(
          isset($this->params['accountSlug']) &&
          !isset($url['accountSlug']) &&
          (!isset($url['account']) || (isset($url['account']) && $url['account'] !== false)))
        {
          $url['accountSlug'] = $this->params['accountSlug'];
        }
        
        //Add project id
        if(
          isset($this->params['projectId']) &&
          !isset($url['projectId']) &&
          (!isset($url['project']) || (isset($url['project']) && $url['project'] !== false)))
        {
          $url['projectId'] = $this->params['projectId'];
        }
      } 
      return parent::url($url, $full); 
    }
  
  }


?>
