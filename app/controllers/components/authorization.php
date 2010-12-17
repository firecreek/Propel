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
     * Load Account
     *
     * @param string $slug Account slug
     * @access public
     * @return array
     */
    public function loadAccount($slug)
    {
      $this->account = $this->controller->Account->find('first',array(
        'conditions' => array(
          'Account.slug' => $slug
        ),
        'contain' => array(
          'Company' => array('id','name')
        )
      ));
      $this->Session->write('AuthAccount.Account',$this->account['Account']);
      $this->Session->write('AuthAccount.Company',$this->account['Company']);
      
      return $this->account ? true : false;
    }
    
    
    /**
     * Load this users `Person` data relevant to the account
     *
     * @access public
     * @return boolean
     */
    public function loadPerson()
    {
      $this->person = $this->controller->User->Person->find('first',array(
        'conditions' => array(
          'Person.user_id' => parent::user('id'),
          'Company.account_id' => $this->account('id')
        ),
        'contain' => array(
          'Company'
        )
      ));
      
      $this->Session->write('AuthAccount.Person',$this->person['Person']);
      
      return $this->person ? true : false;
    }
    
    
    /**
     * Account details
     *
     * @param string $path Extract key
     * @access public
     * @return string
     */
    public function account($path)
    {
      if(strpos($path,'.') === false) { $path = 'AuthAccount.Account.'.$path; }
      return $this->Session->read($path);
    }
    
    
    /**
     * Person details
     *
     * @param string $path Extract key
     * @access public
     * @return string
     */
    public function person($path)
    {
      if(strpos($path,'.') === false) { $path = 'AuthAccount.Person.'.$path; }
      return $this->Session->read($path);
    }
    
  }
  
?>
