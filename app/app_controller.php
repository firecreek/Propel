<?php

  /**
   * AppController
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AppController extends Controller
  {
    /**
     * Components uses
     *
     * @access public
     * @var array
     */
    public $components = array(
      'Authorization',
      'Acl',
      'AclFilter',
      'AclManager',
      'Propel',
      'RequestHandler',
      'Session',
      'DebugKit.Toolbar'
    );
    
    /**
     * Models
     *
     * @access public
     * @var array
     */
    public $uses = array(
      'Account',
      'Project',
      'User',
      'Person',
      'Setting'
    );
    
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array(
      'Html',
      'Form',
      'Javascript',
      'Auth',
      'Layout',
      'Time'
    );
    
    
    /**
     * Before Filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      //JSON
      $this->RequestHandler->setContent('json', 'application/json');
      
      //Auth state to model behavior
      foreach($this->uses as $model)
      {
        if(strpos($model,'.') !== false)
        {
          $plugin = null;
          $model  = substr($model,strpos($model,'.')+1);
        }
      
        if(isset($this->{$model}->Behaviors) && $this->{$model}->Behaviors->attached('Auth'))
        {
          $this->{$model}->setAuthState($this->Session->read('AuthAccount'));
        }
      }
      
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
      $prefix = isset($this->params['prefix']) ? $this->params['prefix'] : null;
      
      if($prefix && !$this->RequestHandler->isAjax())
      {
        if($this->layout !== false && $this->layout == 'default')
        {
          if($prefix == 'admin')
          {
            $this->layout = 'admin';
          }
          else
          {
            $this->layout = 'account';
          }
        }
      }
      
      $this->set(compact('prefix'));
    
      parent::beforeRender();
    }
    
    
    /**
     * Is Authorized check
     *
     * System uses its own CRUD check
     *
     * @access public
     * @return boolean
     */
    public function isAuthorized()
    {
      return $this->AclFilter->check();
    }
    
    
    /**
     * Redirect
     *
     * @param mixed $url
     * @param string $status
     * @param boolean $exit
     * @access public
     * @return void
     */
    public function redirect($url, $status = null, $exit = true)
    {
      if(is_array($url) && !isset($url['prefix']))
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
      
      //Associated controller
      if(
        !isset($url['associatedController']) &&
        !isset($url['controller']) &&
        isset($this->params['associatedController'])
      )
      {
        $url['associatedController'] = $this->params['associatedController'];
      }
      
      return parent::redirect($url, $status, $exit);
    }
  
  }


?>
