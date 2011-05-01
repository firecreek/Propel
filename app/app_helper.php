<?php

  /**
   * AppHelper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AppHelper extends Helper
  {
  
    /**
     * Construct class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
      $this->tags['radio'] = '<input type="radio" name="%s" id="%s" %s /><label for="%2$s">%s</label>'; 
      parent::__construct(); 
    }
    
  
    /**
     * URL
     *
     * @param mixed $url
     * @param boolean $full
     * @access public
     * @return string
     */
    public function url($url = null, $full = false)
    {
      if(is_array($url) && (!isset($url['prefix']) || $url['prefix'] !== false))
      {
        //Account slug
        if(
          isset($this->params['accountSlug']) &&
          !isset($url['accountSlug']) &&
          (!isset($url['account']) || (isset($url['account']) && $url['account'] !== false)))
        {
          $url['accountSlug'] = $this->params['accountSlug'];
        }
        
        //Project id
        if(
          isset($this->params['projectId']) &&
          !isset($url['projectId']) &&
          (!isset($url['project']) || (isset($url['project']) && $url['project'] !== false)))
        {
          $url['projectId'] = $this->params['projectId'];
        }
      }
      
      //Associated controller
      if(
        !isset($url['associatedController']) &&
        !isset($url['controller']) &&
        isset($this->params['associatedController'])
      )
      {
        $url['associatedController'] = $this->params['associatedController'];
      }
      
      return parent::url($url, $full); 
    }
  
  }


?>
