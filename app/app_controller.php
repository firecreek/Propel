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
    public $components = array('Acl','Authorization','AclFilter','AclManager','Openbase','RequestHandler','Session','DebugKit.Toolbar');
    
    /**
     * Models
     *
     * @access public
     * @var array
     */
    public $uses = array('User','Account','Person');
    
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Html','Form','Auth','Layout');
    
    
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
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      if(isset($this->params['prefix']))
      {
        switch($this->params['prefix'])
        {
          case 'account':
            $this->layout = 'account';
            break;
          case 'project':
            $this->layout = 'project';
            break;
        }
      }
    
      parent::beforeRender();
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
      
      return parent::redirect($url, $status, $exit);
    }
  
  }


?>
