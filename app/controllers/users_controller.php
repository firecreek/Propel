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
     * @var array
     * @access public
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array('Assets','Message');
    
    /**
     * Uses
     *
     * @var array
     * @access public
     */
    public $uses = array('User','Person','Account');
    
    /**
     * Auth allow
     *
     * @var array
     * @access public
     */
    public $authAllow = array(
      'account_edit'
    );
    
    
    /**
     * Before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->Authorization->allow(array('register','invitation','forgotten','reset'));
      
      parent::beforeFilter();
    }
    
    
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
            $this->AclManager->allow($this->User->Company, 'accounts', $this->User->Account->id);
            
            //Create directories for saving files
            //@todo Move this
            mkdir(ASSETS_DIR.DS.'accounts'.DS.$this->User->Account->id, 0700);
            mkdir(ASSETS_DIR.DS.'users'.DS.$this->User->id, 0700);
            
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
     * Invitation
     *
     * @access public
     * @return void
     */
    public function invitation($code,$type = 'new')
    {
      $this->layout = 'plain';
    
      $record = $this->Person->find('first',array(
        'conditions' => array('Person.invitation_code'=>$code),
        'contain' => array(
          'PersonInvitee',
          'Account'
        )
      ));
      
      //No invitation code found
      if(empty($record))
      {
        $this->cakeError('invitationInvalid');
      }
      
      //Already logged in
      if($this->Authorization->user('id'))
      {
        $this->Person->updateAll(
          array(
            'Person.user_id' => $this->Authorization->user('id'),
            'Person.status' => '"active"',
            'Person.invitation_code' => null
          ),
          array('Person.id'=>$record['Person']['id'])
        );
        
        $added = true;
      }
      
      //Post
      if(!empty($this->data))
      {
        if($type == 'new')
        {
          //New user
          $this->data['User']['email'] = $record['Person']['email'];
          $this->User->set($this->data);
          
          if($this->User->validates())
          {
            $this->User->save();
          
            //Update person record
            $this->Person->updateAll(
              array(
                'Person.user_id' => $this->User->id,
                'Person.status' => '"active"',
                'Person.invitation_code' => null
              ),
              array('Person.id'=>$record['Person']['id'])
            );
            
            //Email
            $data = array_merge($record,$this->data);
            $this->Message->send('welcome_invite',array(
              'subject' => __('Your account has been created',true),
              'to' => $record['Person']['email']
            ),$data);
            
            //Automatically login and redirect
            $this->Authorization->login($this->data);
            
            $added = true;
          }
        }
        else
        {
          //Existing user, try and log them in, if successful then attach them to this account and project
          if($this->Authorization->login($this->data))
          {
            //Update person record
            $this->Person->updateAll(
              array(
                'Person.user_id' => $this->Authorization->user('id'),
                'Person.status' => '"active"',
                'Person.invitation_code' => null
              ),
              array('Person.id'=>$record['Person']['id'])
            );
            
            $added = true;
          }
        }
      }
      
      //Added?
      if(isset($added) && $added)
      {
        $this->Session->setFlash(sprintf(__('You now have access to %s\'s account page',true),$record['Account']['name']),'default',array('class'=>'success'));
      
        $this->redirect(array(
          'controller'  => 'accounts',
          'action'      => 'index',
          'prefix'      => 'account',
          'accountSlug' => $record['Account']['slug']
        ));
      }
      
      //Continue view
      $this->set(compact('type','code','record'));
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
        if($this->Authorization->user('role_id') == 1)
        {
          $this->redirect(array(
            'prefix'      => 'admin',
            'controller'  => 'dashboard',
            'action'      => 'index'
          ));
        }
        else
        {
          $this->redirect(array(
            'controller'  => 'accounts',
            'action'      => 'index',
            'prefix'      => 'account',
            'accountSlug' => $accountSlug
          ));
        }
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
      if(!empty($this->data))
      {
        $record = $this->User->find('first',array(
          'conditions' => array('User.email'=>$this->data['User']['email']),
          'contain' => false
        ));
        
        if(!empty($record))
        {
          $this->User->id = $record['User']['id'];
          $record['User']['activate_token'] = $this->User->setToken('activate');
        
          $this->Message->send('forgotten',array(
            'subject' => __('Reset your password',true),
            'to' => $this->data['User']['email']
          ),$record);
        
          $this->Session->setFlash(__('Instructions for signing in have been emailed to you',true),'default',array('class'=>'success'));
          
          $this->redirect(array('action'=>'login'));
        }
      }
    }
    
    
    /**
     * Reset password
     *
     * @access public
     * @return void
     */
    public function reset($token)
    {
      $record = $this->User->find('first',array(
        'conditions' => array(
          'User.activate_token' => $token
        ),
        'contain' => array(
          'Account'
        )
      ));
      
      if(empty($record))
      {
        return $this->render('reset_expired');
      }
      
      //
      $this->data['User']['username'] = $record['User']['username'];
      
      if(!empty($this->data))
      {
        $this->data['User']['id'] = $record['User']['id'];
        $this->User->set($this->data);
        
        if($this->User->validates())
        {
          $this->User->save($this->data,array('fields'=>array('password')));
          
          //Automatically login and redirect
          $this->Authorization->login($this->data);
          
          $this->redirect(array(
            'controller'  => 'accounts',
            'action'      => 'index',
            'prefix'      => 'account',
            'accountSlug' => $record['Account']['slug']
          ));
        }
        else
        {
          unset($this->data['User']['password']);
          unset($this->data['User']['password_confirm']);
        }
      }
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
    
    
    /**
     * Account edit your user
     *
     * @access public
     * @return void
     */
    public function account_edit()
    {
      if(!empty($this->data))
      {
        if(empty($this->data['User']['password']) || $this->data['User']['password_confirm'] == 'password')
        {
          unset($this->data['User']['password']);
          unset($this->data['User']['password_confirm']);
        }
        
        $this->data['User']['id'] = $this->Authorization->read('User.id');
        $this->User->set($this->data['User']);
        
        if($this->User->validates())
        {
          //Save avatar
          if(!empty($this->data['User']['avatar']))
          {
            $this->Assets->save('avatar',$this->data['User']['avatar'],array('userId'=>$this->Authorization->read('User.id'),'filename'=>'avatar.png'));
          }
        
          $this->User->save();
          
          $this->Person->Behaviors->detach('Acl');
          
          //Update all people
          $this->Person->updateAll(
            array(
              'first_name' => '"'.$this->data['Person']['first_name'].'"',
              'last_name' => '"'.$this->data['Person']['last_name'].'"',
            ),
            array('Person.user_id'=>$this->Authorization->read('User.id'))
          );
        }
        
        $this->Session->setFlash(__('Your information has been updated',true),'default',array('class'=>'success'));
        
        $this->redirect(array('controller'=>'people','action'=>'edit',$this->Authorization->read('Person.id')));
      }
    
      $this->data = array(
        'Person' => $this->Authorization->read('Person'),
        'User' => $this->Authorization->read('User'),
      );
      
    }
    
  
  }
  
  
?>
