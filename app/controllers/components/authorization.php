<?php

  App::import('Component','Auth'); 

  /**
   * Authorization Component
   *
   * @category Component
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AuthorizationComponent extends AuthComponent
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
    public $components = array('Session');
    

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
      
      parent::initialize($controller, $settings);
    }
    
    
    /**
     * Login
     *
     * @param array $data Login details
     * @access public
     * @var object
     */
    public function login($data)
    {
      if($out = parent::login($data))
      {
        $this->reload();
      }
      
      return $out;
    }
    
    
    /**
     * Logout
     *
     * @access public
     * @var object
     */
    public function logout()
    {
      $this->Session->delete('AuthAccount');
      return parent::logout();
    }
    
    
    /**
     * Reload user details
     *
     * @access public
     * @return void
     */
    public function reload()
    {
      if(!parent::user('id')) { return false; }
      
      $this->controller->loadModel('User');
    
      $record = $this->controller->User->find('first',array(
        'conditions' => array('User.id'=>parent::user('id')),
        'contain' => array(
          'Account',
          'Company',
          'Person'
        )
      ));
    
      foreach($record as $model => $data)
      {
        $this->Session->write('Auth.'.$model,$data);
      }
    }
    
    
    /**
     * After ACL Auth
     *
     * @access public
     * @return void
     */
    public function afterAclAuth()
    {
      //Load projects this user can access
      $aroId = $this->read('Person._aro_id');
      
      $records = $this->controller->Acl->Aco->Permission->find('all',array(
        'conditions' => array(
          'Permission.aro_id' => $aroId,
          'Permission._read' => true,
          'Aco.model' => 'Project',
        ),
        'fields' => array('Aco.foreign_key')
      ));
      $projectIds = Set::extract($records,'{n}.Aco.foreign_key');
      
      $projects = $this->controller->Account->Project->find('all',array(
        'conditions' => array(
          'Project.id'      => $projectIds,
          'Project.status'  => 'active'
        ),
        'contain' => array(
          'Account' => array(
            'fields' => array('id','slug')
          ),
          'Company' => array(
            'fields' => array('id','name')
          )
        )
      ));
      
      $this->write('Projects',$projects);
    }
    
    
    /**
     * Write data
     *
     * @param string $slug Account slug
     * @access public
     * @return void
     */
    public function write($key,$data)
    {
      $this->Session->write('AuthAccount.'.$key,$data);
      $this->access[$key] = $data;
    }
    
    
    /**
     * Read data
     *
     * @param string $path
     * @access public
     * @return void
     */
    public function read($path)
    {
      return Set::extract($this->access,$path);
    }
    
    
  }
  
?>
