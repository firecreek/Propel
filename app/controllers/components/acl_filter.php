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
    public $components = array('Authorization','Acl','Session');
    

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
      $this->Authorization->loginAction = array('account' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->logoutRedirect = array('account' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->userScope = array('User.status' => 1);
      $this->Authorization->actionPath = 'openbase/';
      
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
        $this->Session->setFlash(__('You must be logged in to do that action', true),'default',array('class'=>'error'));
        $this->controller->redirect($this->Authorization->loginAction);
      }
      else
      {
        //Account
        $this->controller->layout = 'account';
      
        //Load Person based on account slug and user id
        $accountSlug = $this->controller->params['accountSlug'];
        $userId = $this->Authorization->user('id');
        
        $record = $this->controller->User->Person->query("
          SELECT *
          FROM people as Person
          INNER JOIN companies as Company ON (Company.account_id = Person.company_id)
          INNER JOIN accounts as Account ON (Account.id = Company.account_id AND Account.slug = '".$accountSlug."')
          WHERE Person.user_id = ".$userId."
        ");
        
        $person = Set::extract($record,'0.Person');
        $company = Set::extract($record,'0.Company');
        $account = Set::extract($record,'0.Account');
        
        //Find Person aro
        $aro = $this->Acl->Aro->find('first', array(
            'conditions' => array(
                'Aro.model' => 'Person',
                'Aro.foreign_key' => $person['id'],
            ),
            'recursive' => -1,
        ));
        $aroId = $aro['Aro']['id'];
        
        //Find Account acos
        $root = $this->Acl->Aco->node('accounts/'.$accountSlug);
        $acoId = Set::extract($root,'0.Aco.id');
        
        //Get this Persons permissions for this account
        $permission = $this->controller->Acl->Aco->Permission->find('first',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission.aco_id' => $acoId,
          ),
          'fields' => array(
            'Permission.*'
          )
        ));
        $permission = Set::extract($permission,'Permission');
        
        if(!empty($permission))
        {
          $this->Authorization->write('Permissions',$permission);
          $this->Authorization->write('Company',$company);
          $this->Authorization->write('Account',$account);
          $this->Authorization->write('Person',$person);
        }
        else
        {
          $this->_throwError(__('You do not have access to this account'));
        }
        
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
