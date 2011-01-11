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
    public $actionMap = array(
      'index'     => '_read',
      'view'      => '_read',
      'comments'  => '_read',
      'edit'      => '_update',
      'add'       => '_create',
      'delete'    => '_delete',
      'update'    => '_update',
    );
    
    
    /**
     * Checks with Auth model behavior if record can be modified
     *
     * @access public
     * @var array
     */
    public $modelAuthCheck = array(
      '_update'  => true,
      '_delete'  => true
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
      if(isset($this->controller->actionMap))
      {
        $this->actionMap = array_merge($this->actionMap,$this->controller->actionMap);
      }
    
      //Configure AuthComponent
      $this->Authorization->authorize = 'crud';
      $this->Authorization->autoRedirect = false;
      $this->Authorization->loginAction = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->logoutRedirect = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
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
        //Load Person based on account slug and user id
        $permissions = array('Account'=>array(),'Project'=>array());
        
        $accountSlug = $this->controller->params['accountSlug'];
        
        $account  = null;
        $project  = null;
        $userId   = $this->Authorization->user('id');
        
        
        //Load account
        $account = $this->controller->Account->find('first',array(
          'conditions' => array(
            'Account.slug' => $accountSlug
          ),
          'fields' => array('id','name','slug','scheme_id'),
          'contain' => array(
            'CompanyOwner' => array('id','name')
          ),
          'cache' => array(
            'name' => 'account_'.$accountSlug,
            'config' => 'acl',
          )
        ));
        
        //Load project     
        if($prefix == 'project')
        {
          $project = $this->controller->Account->Project->find('first',array(
            'conditions' => array(
              'Project.id' => $this->controller->params['projectId']
            ),
            'contain' => array(
              'Company' => array('id','name','private'),
              'Person' => array('id','user_id','full_name','email')
            ),
            'cache' => array(
              'name' => 'project_'.$this->controller->params['projectId'],
              'config' => 'acl',
            )
          ));
        }
        
        
        //Load current person
        $person = $this->controller->Person->find('first',array(
          'conditions' => array(
            'Person.account_id' => $account['Account']['id'],
            'Person.user_id'    => $userId
          ),
          'fields' => array('id','first_name','last_name','full_name','email','company_owner'),
          'contain' => false,
          'cache' => array(
            'name' => 'person_'.$account['Account']['id'].'_'.$userId,
            'config' => 'acl',
          )
        ));
        
        
        //Find Person aro
        $aro = $this->Acl->Aro->find('first', array(
          'conditions' => array(
            'Aro.model' => 'Person',
            'Aro.foreign_key' => $person['Person']['id'],
          ),
          'recursive' => -1,
          'cache' => array(
            'name' => 'person_aco_'.$person['Person']['id'],
            'config' => 'acl',
          )
        ));
        $person['Person']['_aro_id'] = $aro['Aro']['id'];
        
        
        //Load projects this person has access too
        $projects = $this->_aroProjects($person['Person']['_aro_id']);
       
        //Prefix model id
        $modelId = ${$prefix}[Inflector::camelize($prefix)]['id'];
        
        
        //Load account permissions
        $permissions['Account'] = $this->_aroPrefixPermissions('account',$account['Account']['id'],$person['Person']['_aro_id']);
        
        //Load prefix permissions
        if($prefix != 'account')
        {
          $permissions[Inflector::classify($prefix)] = $this->_aroPrefixPermissions($prefix,$modelId,$person['Person']['_aro_id']);
        }
        
        
        
        
        //Load companies added to this prefix
        $modelRootNode = $this->controller->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$modelId);
        
        $companies = $this->_loadPrefixCompanies($modelRootNode[0]['Aco']['id']);
        $people = $this->_loadPrefixPeople($modelRootNode[0]['Aco']['id']);
        
        
        //Load account style
        $style = $this->controller->Account->Scheme->SchemeStyle->find('list',array(
          'conditions' => array('scheme_id' => $account['Account']['scheme_id']),
          'fields'  => array('SchemeStyle.key','SchemeStyle.value'),
          'recursive' => -1
        ));
        
        //Sets
        $this->Authorization->write('Permissions',$permissions);
        $this->Authorization->write('Company',$account['CompanyOwner']);
        $this->Authorization->write('Account',$account['Account']);
        $this->Authorization->write('Style',$style);
        $this->Authorization->write('Person',$person['Person']);
        $this->Authorization->write('People',$people);
        $this->Authorization->write('Projects',$projects);
        $this->Authorization->write('Project',$project['Project']);
        $this->Authorization->write('Companies',$companies);
        
        
        /**
         * Permission checking
         */
        
        //Fix action map
        $action = str_replace($prefix.'_','',$this->controller->action);
        $actionKey = $this->actionMap[$action];
        
        //Check permissions for this person
        $isAllowed = false;
        
        //Check if this person is allowed to be in this controller and has the correct CRUD access
        $permissionNode = $this->controller->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$modelId.'/'.$this->controller->name);
        if(!empty($permissionNode))
        {
          $isAllowed = $this->controller->Acl->Aco->Permission->find('count', array(
            'conditions' => array(
              'Aro.model' => 'Person',
              'Permission.aco_id' => $permissionNode[0]['Aco']['id'],
              'Permission.aro_id' => $person['Person']['_aro_id'],
              'Permission.'.$actionKey => true
            )
          ));
        }
        
        //If handling individual records then check they can update this record
        //modelAuthCheck isset then the id passed in the URL will be checked
        if(isset($this->modelAuthCheck[$actionKey]) && $isAllowed == true)
        {
          $modelAlias = Inflector::classify($this->controller->name);
          $fieldKey   = $prefix.'_id';
          
          if(
            isset($this->controller->params['pass'][0]) &&
            is_numeric($this->controller->params['pass'][0]) &&
            isset($this->controller->{$modelAlias}) &&
            is_object($this->controller->{$modelAlias}) &&
            $this->controller->{$modelAlias}->hasField($fieldKey)
          )
          {
            //Model exists, and the record has to match up with the prefix
            if(!$this->controller->{$modelAlias}->find('count',array(
              'conditions' => array(
                'id'        => $this->controller->params['pass'][0],
                $fieldKey   => $modelId
              ),
              'recursive' => -1
            )))
            {
              //Failed to match prefix and updating record together
              $isAllowed = false;
            }            
          }
        }
        
        //Throw error
        if(!$isAllowed)
        {
          $this->_throwError(__('You do not have access to do that action',true),5);
        }
        
        //Allow auth
        $this->Authorization->allowedActions = array('*');
      }
      
    }
    
    
    
    /**
     * List of projects this aro has access to
     *
     * @access private
     * @return array
     */
    private function _aroProjects($aroId)
    {
      $projects = $this->controller->Account->Project->findCached('projects_'.$aroId, 'acl');
   
      if(empty($projects))
      {
        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission._read' => true,
            'Aco.model' => 'Projects',
          ),
          'fields' => array('Aco.foreign_key','Permission.*')
        ));
        
        if(!empty($records))
        {
          $projects = $this->controller->Account->Project->find('all',array(
            'conditions' => array(
              'Project.id'      => Set::extract($records,'{n}.Aco.foreign_key'),
              'Project.status'  => 'active'
            ),
            'fields' => array('id','name'),
            'contain' => array(
              'Account' => array(
                'fields' => array('id','slug')
              ),
              'Company' => array(
                'fields' => array('id','name')
              )
            ),
            'cache' => array(
              'name' => 'projects_'.$aroId,
              'config' => 'acl',
            )
          ));
        }
      }
      
      return $projects;
    }
    
    
    /**
     * Prefix permissions for this current user
     *
     * @access private
     * @return array
     */
    private function _aroPrefixPermissions($prefix,$recordId,$personAroId)
    {
      $permissions = Cache::read('aro_prefix_'.$prefix.'_'.$personAroId,'acl');
   
      if(empty($permissions))
      {
        //Load project permissions
        $node = $this->controller->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$recordId);

        $controllers = $this->controller->Acl->Aco->find('list',array(
          'conditions' => array(
            'Aco.parent_id' => $node[0]['Aco']['id']
          ),
          'fields' => array(
            'Aco.id',
            'Aco.alias',
          ),
          'recursive' => '-1',
        ));
        $controllerIds = array_keys($controllers);
        
        //Check what permissions this person has for this project
        $allowedControllers = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Permission.aro_id' => $personAroId,
            'Permission.aco_id' => $controllerIds
          ),
          'fields' => array('Permission.*','Aco.alias')
        ));
        
        foreach($allowedControllers as $allowedController)
        {
          $permissions[$allowedController['Aco']['alias']] = array(
            'create'  => $allowedController['Permission']['_create'],
            'read'    => $allowedController['Permission']['_read'],
            'update'  => $allowedController['Permission']['_update'],
            'delete'  => $allowedController['Permission']['_delete'],
          );
        }
        
        Cache::write('aro_prefix_'.$prefix.'_'.$personAroId,$permissions,'acl');
      }
      
      return $permissions;
    }
    
    
    /**
     * Companies associated to this aco
     *
     * @access private
     * @return array
     */
    private function _loadPrefixCompanies($acoId)
    {
      $companies = $this->controller->User->Company->findCached('companies_aco_'.$acoId,'acl');

      if(empty($companies))
      {
        //Load companies
        $records = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Company',
            'Permission.aco_id' => $acoId,
            'Permission._read' => true
          ),
          'fields' => array('Aro.foreign_key')
        ));
        $records = Set::extract($records,'{n}.Aro.foreign_key');
        
        $companies = $this->controller->User->Company->find('all',array(
          'conditions' => array('Company.id'=>$records),
          'fields' => array('id','name','private','account_owner'),
          'order' => 'Company.created DESC',
          'contain' => false,
          'cache' => array(
            'name' => 'companies_aco_'.$acoId,
            'config' => 'acl',
          )
        ));
      }
      
      return $companies;
    }
    
    
    /**
     * People associated to this aco
     *
     * @access private
     * @return array
     */
    private function _loadPrefixPeople($acoId)
    {
      $people = $this->controller->Person->findCached('people_aco_'.$acoId,'acl');

      if(empty($people))
      {
        //Load people
        $records = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Person',
            'Permission.aco_id' => $acoId,
            'Permission._read' => true
          ),
          'fields' => array('Aro.foreign_key')
        ));
        $records = Set::extract($records,'{n}.Aro.foreign_key');
    
        $people = $this->controller->Person->find('all',array(
          'conditions' => array(
            'Person.id' => $records
          ),
          'fields' => array('id','full_name','first_name','last_name','email','title','company_owner'),
          'contain' => array(
            'Company' => array('id','name'),
            'User' => array('id')
          ),
          'cache' => array(
            'name' => 'people_aco_'.$acoId,
            'config' => 'acl',
          )
        ));
      }
      
      return $people;
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
