<?php

  /**
   * Acl Filter Component
   *
   * @category Component
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AclFilterComponent extends Object
  {
    /**
     * Controller object
     *
     * @access public
     * @var object
     */
    public $controller = null;
    
    /**
     * Components used
     *
     * @access public
     * @var object
     */
    public $components = array('Authorization','Session');
    

    /**
     * Initialize component
     *
     * @param object $controller
     * @param array $settings
     * @access public
     * @return void
     */
    public function initialize(&$controller, $settings = array())
    {
      $this->controller =& $controller;
    }


    /**
     * Check and set auths
     *
     * @access public
     * @return void
     */
    public function auth()
    {
      //Configure AuthComponent
      $this->Authorization->authorize = 'crud';
      $this->Authorization->autoRedirect = false;
      $this->Authorization->loginAction = array('controller' => 'users', 'action' => 'login');
      $this->Authorization->logoutRedirect = array('controller' => 'users', 'action' => 'login');
      $this->Authorization->userScope = array('User.status' => 1);
      $this->Authorization->actionPath = 'controllers/';
      
      $prefix = isset($this->controller->params['prefix']) ? $this->controller->params['prefix'] : null;
      $userRoleId = $this->Authorization->user('role_id');

      if(empty($prefix))
      {
        //Front end pages, no auth required
        $this->Authorization->allowedActions = array('*');
      }
      elseif(!$this->Authorization->user())
      {
        //Not logged in
        $this->Session->setFlash(__('You must login'),'default',array('class'=>'error'));
        $this->controller->redirect($this->Authorization->loginAction);
      }
      else
      {
        //Load account details
        if(!$this->Authorization->loadAccount($this->controller->params['account']))
        {
          //No such account
          $this->cakeError('error404'); 
        }
        
        //Check they have access
        if(!$this->controller->Account->hasAccess($this->Authorization->account('id'),$this->Session->read('Auth')))
        {
          $this->_throwError(__('You do not have access to this account'));
        }
        
        $this->controller->layout = 'account';
      }
      
      $this->Authorization->allowedActions = array('*');
    }
    
    
    /** 
     * Handles requests to unauthorized actions 
     * 
     * @param Controller $controller 
     * @access private
     * @return boolean 
     */ 
    private function _throwError($error)
    {
      $this->Session->setFlash($error, 'default', array('class' => 'error'));
      $this->controller->redirect($this->controller->referer(), null, true); 
    }
    
  }
  
?>
