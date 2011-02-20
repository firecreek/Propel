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
    public $uses = array('User','Account');
    
    
    /**
     * Register new user, account and person
     *
     * @access public
     * @return void
     */
    public function register()
    {
      //$this->_checkUser();
      
      if(!empty($this->data))
      {
        $this->User->saveAll($this->data, array('validate' => 'only'));
        
        if($this->User->validates())
        {
          //Account
          $this->data['Account']['slug'] = $this->User->Account->makeSlug($this->data['Company']['name']);
          $this->data['Account']['name'] = $this->data['Company']['name'];
          
          $this->data['Company']['account_owner'] = true;
          $this->data['Person']['company_owner'] = true;
          $this->data['Person']['email'] = $this->data['User']['email'];
        
          $saved = $this->User->saveAll($this->data, array('validate'=>false));
          
          if($saved)
          {
            //Update some fields that saveAll didn't save
            $this->User->Company->saveField('account_id',$this->User->Account->id);
            $this->User->Person->saveField('company_id',$this->User->Company->id);
            $this->User->Person->saveField('user_id',$this->User->id);
            $this->User->Person->saveField('account_id',$this->User->Account->id);
            
            //Create ACO for this account
            $this->AclManager->create('accounts',$this->User->Account->id);
            
            //Give this person permission for this account
            $this->AclManager->allow($this->User->Person, 'accounts', $this->User->Account->id, array('set' => 'owner'));
            
            //Give this company permission for this account
            $this->AclManager->allow($this->User->Company, 'accounts', $this->User->Account->id, array('set' => 'company'));
            
            //Create assets directory for saving files
            mkdir(ASSETS_DIR.DS.'accounts'.DS.$this->User->Account->id, 0700);
            
            //Automatically login and redirect
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
      //Account set?
      if(isset($this->params['accountSlug']))
      {
        $account = $this->Account->find('first',array(
          'conditions' => array(
            'Account.slug' => $this->params['accountSlug']
          ),
          'contain' => false
        ));
        $this->set(compact('account'));
      }
    
      //Attempting to login
      if(!empty($this->data) && $this->Authorization->login($this->data))
      {
        $accountSlug = $this->Session->read('Auth.Account.slug');
      
        //Check account to log into
        if(isset($account) && $this->Account->hasAccess($account['Account']['id'],$this->Session->read('Auth')))
        {
          $accountSlug = $account['Account']['slug'];
        }
      
        //Redirect
        $this->redirect(array(
          'controller'  => 'accounts',
          'action'      => 'index',
          'prefix'      => 'account',
          'accountSlug' => $accountSlug
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
          'accountSlug' => $this->Authorization->read('Account.slug')
        ));
      }
      
      return false;
    }
  
  }
  
  
?>
