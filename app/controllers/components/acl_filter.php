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
      'index'   => '_read',
      'view'    => '_read',
      'edit'    => '_update',
      'add'     => '_create',
      'delete'  => '_delete',
      'update'  => '_update'
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
        $userId = $this->Authorization->user('id');
        
        
        //Load account
        $account = $this->controller->Account->find('first',array(
          'conditions' => array(
            'Account.slug' => $accountSlug
          ),
          'contain' => array(
            'CompanyOwner' => array()
          )
        ));
        
        
        //Load person
        $person = $this->controller->Person->find('first',array(
          'conditions' => array(
            'Person.account_id' => $account['Account']['id'],
            'Person.user_id'    => $userId
          ),
          'contain' => false
        ));
        
        
        //Find Person aro
        $aro = $this->Acl->Aro->find('first', array(
            'conditions' => array(
                'Aro.model' => 'Person',
                'Aro.foreign_key' => $person['Person']['id'],
            ),
            'recursive' => -1,
        ));
        $person['Person']['_aro_id'] = $aro['Aro']['id'];
        
        
        //Load project
        $project = null;        
        if($prefix == 'project')
        {
          $project = $this->controller->Account->Project->find('first',array(
            'conditions' => array(
              'Project.id' => $this->controller->params['projectId']
            ),
            'contain' => array(
              'Company' => array('id','name','private'),
              'Person' => array('id','user_id','full_name','email')
            )
          ));
        }
        
        
        //Load projects
        $projects = array();
        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $person['Person']['_aro_id'],
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
            )
          ));
        }
        
        //Load account permissions
        $accountNode = $this->controller->Acl->Aco->node('opencamp/accounts/'.$account['Account']['id']);
        $controllers = $this->controller->Acl->Aco->find('list',array(
          'conditions' => array(
            'Aco.parent_id' => $accountNode[0]['Aco']['id']
          ),
          'fields' => array(
            'Aco.id',
            'Aco.alias',
          ),
          'recursive' => '-1',
        ));
        $controllerIds = array_keys($controllers);
        $allowedControllers = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Permission.aro_id' => $person['Person']['_aro_id'],
            'Permission.aco_id' => $controllerIds
          ),
          'fields' => array('Permission.*','Aco.alias')
        ));
        
        foreach($allowedControllers as $allowedController)
        {
          $permissions['Account'][$allowedController['Aco']['alias']] = array(
            'create'  => $allowedController['Permission']['_create'],
            'read'    => $allowedController['Permission']['_read'],
            'update'  => $allowedController['Permission']['_update'],
            'delete'  => $allowedController['Permission']['_delete'],
          );
        }
        
        //Load project permissions
        if(!empty($project))
        {
          $node = $this->controller->Acl->Aco->node('opencamp/projects/'.$project['Project']['id']);

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
          $allowedControllers = $this->controller->Acl->Aco->Permission->find('all', array(
            'conditions' => array(
              'Permission.aro_id' => $person['Person']['_aro_id'],
              'Permission.aco_id' => $controllerIds
            ),
            'fields' => array('Permission.*','Aco.alias')
          ));
          
          foreach($allowedControllers as $allowedController)
          {
            $permissions['Project'][$allowedController['Aco']['alias']] = array(
              'create'  => $allowedController['Permission']['_create'],
              'read'    => $allowedController['Permission']['_read'],
              'update'  => $allowedController['Permission']['_update'],
              'delete'  => $allowedController['Permission']['_delete'],
            );
          }
        }
        
        //Prefix model id
        $modelId = ${$prefix}[Inflector::camelize($prefix)]['id'];
        
        
        //Load companies added to this prefix
        $modelRootNode = $this->controller->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$modelId);
        
        //Load companies
        $records = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Company',
            'Permission.aco_id' => $modelRootNode[0]['Aco']['id'],
            'Permission._read' => true
          ),
          'fields' => array('Aro.foreign_key')
        ));
        $records = Set::extract($records,'{n}.Aro.foreign_key');
        
        $companies = $this->controller->User->Company->find('all',array(
          'conditions' => array('Company.id'=>$records),
          'fields' => array('id','name','private','account_owner'),
          'order' => 'Company.created DESC',
          'contain' => false
        ));
        
        //Load people
        $records = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Person',
            'Permission.aco_id' => $modelRootNode[0]['Aco']['id'],
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
          )
        ));
        
        //Fix action map
        $action = str_replace($prefix.'_','',$this->controller->action);
        $actionKey = $this->actionMap[$action];
        
        //Check request permissions
        $isAllowed = false;
        $permissionNode = $this->controller->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$modelId.'/'.$this->controller->name);
        
        if(!empty($permissionNode))
        {          
          //Check if allowed
          $isAllowed = $this->controller->Acl->Aco->Permission->find('count', array(
            'conditions' => array(
              'Aro.model' => 'Person',
              'Permission.aco_id' => $permissionNode[0]['Aco']['id'],
              'Permission.aro_id' => $person['Person']['_aro_id'],
              'Permission.'.$actionKey => true
            )
          ));
        }
        
        //Check action for this controller is permitted, only if allowed already
        //This is a customised version of the model record validation
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
