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
      $this->controller->Auth->authorize = 'crud';
      $this->controller->Auth->autoRedirect = false;
      $this->controller->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
      $this->controller->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
      $this->controller->Auth->userScope = array('User.active' => 1);
      $this->controller->Auth->actionPath = 'controllers/';
      
      $prefix = isset($this->controller->params['prefix']) ? $this->controller->params['prefix'] : null;
      $userRoleId = $this->controller->Auth->user('role_id');
      
      //Depending on prefix and user group id
      if(empty($prefix))
      {
        //Front end pages, no auth required
        $this->controller->Auth->allowedActions = array('*');
      }
      
    }
    
  }
  
?>
