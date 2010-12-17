<?php

  /**
   * Users Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class UsersController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('User');
    
    
    /**
     * Register new user, account and person
     *
     * @access public
     * @return void
     */
    public function register()
    {
      $this->_checkUser();
      
      if(!empty($this->data))
      {
        $this->User->saveAll($this->data, array('validate' => 'only'));
        
        if($this->User->validates())
        {
          //Account
          $this->data['Account']['slug'] = $this->User->Account->makeSlug($this->data['Company']['name']);
          
          $this->data['Company']['account_owner'] = true;
          $this->data['Person']['company_owner'] = true;
        
          $saved = $this->User->saveAll($this->data, array('validate'=>false));
          
          if($saved)
          {
            $this->User->Company->saveField('account_id',$this->User->Account->id);
            $this->User->Person->saveField('company_id',$this->User->Company->id);
            $this->User->Person->saveField('user_id',$this->User->id);
            
            $this->Authorization->login($this->data);
            
            $this->redirect(array(
              'controller'  => 'accounts',
              'action'      => 'index',
              'prefix'      => 'account',
              'accountSlug' => $this->data['Account']['slug']
            ));

          }
          else
          {
            $this->Session->setFlash(__('Error creating user, try again', true), 'default', array('class' => 'error'));
          }
        }
        else
        {
          //Failed validation
          $this->data['User']['password'] = null;
          $this->data['User']['password_confirm'] = null;
        }
      }
    }
    
    
    /**
     * Login
     *
     * @access public
     * @return void
     */
    public function login()
    {
      $this->_checkUser();
    
      //Attempting to login
      if(!empty($this->data) && $this->Authorization->login($this->data))
      {
        $this->redirect(array(
          'controller'  => 'accounts',
          'action'      => 'index',
          'prefix'      => 'account',
          'accountSlug' => $this->Session->read('Auth.Account.slug')
        ));
      }
    }
    
    
    /**
     * Logout
     *
     * @access public
     * @return void
     */
    public function logout()
    {
      $this->Authorization->logout();
      
      $this->Session->setFlash(__('You are now logged out', true), 'default', array('class'=>'success'));
      $this->redirect($this->Authorization->logoutRedirect);
    }
    
    
    /**
     * Forgotten password reminder
     *
     * @access public
     * @return void
     */
    public function forgotten()
    {
    }
    
    
    /**
     * Check if logged in already, if so then redirect
     *
     * @access private
     * @return boolean
     */
    private function _checkUser()
    {
      //Already logged in
      if($this->Authorization->user('id'))
      {
        $this->redirect(array(
          'controller'  => 'accounts',
          'action'      => 'index',
          'prefix'      => 'account',
          'accountSlug' => $this->Authorization->account('slug')
        ));
      }
      
      return false;
    }
  
  }
  
  
?>
