<?php

  /**
   * AppController
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AppController extends Controller
  {
    /**
     * Components uses
     *
     * @access public
     * @var array
     */
    public $components = array('Acl','Authorization','AclFilter','Session','DebugKit.Toolbar');
    
    public $uses = array('User','Account','Person');
    
    public $helpers = array('Html','Form','Auth');
    
    
    /**
     * Before Filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      //Auth settings
      $this->AclFilter->auth();
      
      parent::beforeFilter();
    }
    
    
    /**
     * Redirect
     *
     * @access public
     * @return void
     */
    public function redirect($url, $status = null, $exit = true)
    {
      if(is_array($url))
      { 
        if(
          isset($this->params['accountSlug']) &&
          !isset($url['accountSlug']) &&
          (!isset($url['account']) || (isset($url['account']) && $url['account'] !== false)))
        {
          $url['accountSlug'] = $this->params['accountSlug'];
        }
      } 
      
      return parent::redirect($url, $status, $exit);
    }
  
  }


?>
