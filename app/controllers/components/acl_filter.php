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
     * CRUD permission map
     *
     * @access public
     * @var array
     */
    public $permissionMap = array(
      'index'   => '_read',
      'view'    => '_read',
      'edit'    => '_update',
      'add'     => '_create',
      'delete'  => '_delete'
    );
    

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
        //Permissions array
        $permissions = array('Account'=>null,'Project'=>null);
      
        //Load Person based on account slug and user id
        $accountSlug = $this->controller->params['accountSlug'];
        $userId = $this->Authorization->user('id');
        
        $record = $this->controller->User->Person->query("
          SELECT *
          FROM people as Person
          INNER JOIN companies as Company ON (Company.id = Person.company_id)
          INNER JOIN accounts as Account ON (Account.id = Company.account_id AND Account.slug = '".$accountSlug."')
          WHERE Person.user_id = ".$userId."
        ");
        
        if(empty($record))
        {
          $this->_throwError(__('You do not have access to this account',true),1);
        }
        
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
        $person['_aro_id'] = $aroId;
        
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
        
        //Handle responses
        if(empty($permission))
        {
          $this->_throwError(__('You do not have access to this account',true),2);
        }
        elseif(!$permission['_read'])
        {
          $this->_throwError(__('You do not have access to this account',true),3);
        }
        
        $permissions['Account'] = $permission;
        
        //Load projects this user can access
        $projects = array();
        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission._read' => true,
            'Aco.model' => 'Project',
          ),
          'fields' => array('Aco.foreign_key','Permission.*')
        ));
        
        if(!empty($records))
        {
          $projectPermissions = Set::combine($records, '{n}.Aco.foreign_key', '{n}.Permission');
          $projectIds = Set::extract($records,'{n}.Aco.foreign_key');
          
          $projectRecords = $this->controller->Account->Project->find('all',array(
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
          
          foreach($projectRecords as $key => $projectRecord)
          {
            $projects[$projectRecord['Project']['id']] = array_merge(
              array('Permission'=>$projectPermissions[$projectRecord['Project']['id']]),
              $projectRecord
            );
          }
        }
        
        //Check if project id is set
        $project = null;        
        if(isset($this->controller->params['projectId']) && isset($projects[$this->controller->params['projectId']]))
        {
          $permissions['Project'] = $projects[$this->controller->params['projectId']]['Permission'];
      
          $project = array_merge(
            $projects[$this->controller->params['projectId']]['Project'],
            $projects[$this->controller->params['projectId']]
          );
        }
        
        //Check permissions depending on prefix and called action
        $prefixPermissions = $permissions[Inflector::classify($prefix)];
        $action = str_replace($prefix.'_','',$this->controller->action);
        
        //Fatal error on not defined map
        if(!isset($this->permissionMap[$action]))
        {
          trigger_error(sprintf(
            __('%s action has not been assigned a permission map record', true), $action
          ), E_USER_ERROR);
        }
        
        //No permission
        if(!$prefixPermissions[$this->permissionMap[$action]])
        {
          $this->_throwError(__('You do not have access to do that action',true),5);
        }
        
        //Set to authorization component
        $this->Authorization->write('Permissions',$permissions);
        $this->Authorization->write('Company',$company);
        $this->Authorization->write('Account',$account);
        $this->Authorization->write('Person',$person);
        $this->Authorization->write('Projects',$projects);
        $this->Authorization->write('Project',$project);
        
        $this->Authorization->allowedActions = array('*');
        
      }
      
    }
    
    
    /** 
     * Handles requests to unauthorized actions 
     * 
     * @param Controller $controller 
     * @access private
     * @return boolean 
     */ 
    private function _throwError($error,$errorNumber = null)
    {
      if($errorNumber)
      {
        $error .= ' (E'.$errorNumber.')';
      }
    
      $this->Session->setFlash($error, 'default', array('class' => 'error'));
      $this->controller->redirect($this->controller->referer(), null, true); 
    }
    
  }
  
?>
