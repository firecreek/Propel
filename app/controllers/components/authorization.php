<?php

  App::import('Component','Auth'); 

  /**
   * Authorization Component
   *
   * @category Component
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
    public $components = array('Session','Acl','RequestHandler','AclManager');
    
    /**
     * Access data
     *
     * @access public
     * @var array
     */
    public $cache = true;
    
    /**
     * Access data
     *
     * @access public
     * @var array
     */
    public $access = array();
    

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
      
      //Load person, etc..
      if($this->user('id') && isset($this->controller->params['accountSlug']))
      {
        $this->prefix = null;
        $this->accountSlug  = $this->controller->params['accountSlug'];
        
        if(isset($this->controller->params['prefix']))
        {
          $this->prefix = $this->controller->params['prefix'];
        }
        elseif(isset($this->controller->authPrefix))
        {
          $this->prefix = $this->controller->authPrefix;
        }
        
        $this->_loadUser();
      }
      
      parent::initialize($controller, $settings);
    }
    
    
    /**
     * Load User
     *
     * @todo Centralise caching
     * @access public
     * @return void
     */
    public function _loadUser()
    {
      $this->reset();
      
      //Load account, company, project
      $this->_call('Account');
      $this->_call('Company');
      $this->_call('Project');
      $this->_call('Prefix');
      $this->_call('Person');
      
      /**
       * Records accessible
       */
      $this->_call('Accounts',array('Accounts'),array('method'=>'PersonAccess'));
      
      $this->_call('Projects',array('Projects',array(
        'contain' => array(
          'Account' => array(
            'fields' => array('id','name','slug')
          ),
          'Company' => array(
            'fields' => array('id','name')
          )
        )
      )),array('method'=>'PersonAccess'));
      
      
      /**
       * Permissions
       */
      $this->_call('Permissions.Account',array('Account'),array('method'=>'ModelPermissions'));
      if($this->prefix !== 'account')
      {
        $key = Inflector::classify($this->prefix);
        $this->_call('Permissions.'.$key,array($key),array('method'=>'ModelPermissions'));
      }
      
      /**
       * Companies and people with access
       */
      $this->_call('Companies',array(),array('method'=>'PrefixCompanies'));
      $this->_call('People',array(),array('method'=>'PrefixPeople'));
      
      //
      $this->updateUserActivity();
    }
    
    
    
    /**
     * Load prefix
     * 
     * @access private
     * @return boolean
     */
    private function _call($name,$args = array(),$options = array())
    {
      $_options = array(
        'cache' => $this->cache,
        'method' => $name
      );
      $options = array_merge($_options,$options);
  
      //Build unique cache key
      $keyArr = array();
      $keyArr[] = Inflector::camelize($name);
      $keyArr[] = 'account-'.$this->accountSlug;
      
      if(isset($this->controller->params['projectId']))
      {
        $keyArr[] = 'project-'.$this->controller->params['projectId'];
      }
      
      if($personId = $this->read('Person.id'))
      {
        $keyArr[] = 'person-'.$personId;
      }
      else
      {
        $keyArr[] = 'user-'.$this->user('id');
      }
      $cacheKey = implode('_',$keyArr);
      
      //
      if($options['cache'] == true)
      {
        //Check cache
        $data = Cache::read($cacheKey,'auth');
      }
      
      //Method
      if(!isset($data) || (isset($data) && empty($data)))
      {
        //debug('call:' .$options['method'].' // '.$cacheKey);
        $method = '__load'.$options['method'];
        
        $data = call_user_func_array(array($this, $method), $args);
      }
      
      $this->write($name,$data);
      
      //Cache
      if($options['cache'] == true)
      {
        Cache::write($cacheKey,$data,'auth');
      }
      
      return true;
    }
    
    
    /**
     * Load account
     * 
     * @access private
     * @return boolean
     */
    private function __loadAccount()
    {
      $account = $this->controller->Account->find('first',array(
        'conditions' => array(
          'Account.slug' => $this->accountSlug
        ),
        'fields' => array('id','name','slug','scheme_id'),
        'contain' => array(
          'CompanyOwner' => array('id','name')
        )
      ));
      
      if(empty($account))
      {
        $this->cakeError('accountNotFound');
      }
      
      return $account['Account'];
    }
    
    
    /**
     * Load company
     * 
     * @access private
     * @return boolean
     */
    private function __loadCompany()
    {
      $company = $this->controller->Account->Company->find('first',array(
        'conditions' => array(
          'Company.account_id' => $this->read('Account.id'),
          'Company.account_owner' => true
        ),
        'fields' => array('id','name'),
        'contain' => false
      ));
      
      if(empty($company))
      {
        $this->cakeError('companyNotFound');
      }
      
      return $company['Company'];
    }
    
    
    /**
     * Load project
     * 
     * @access private
     * @return boolean
     */
    private function __loadProject()
    {
      if(!isset($this->controller->params['projectId'])) { return; }
    
      $project = $this->controller->Account->Project->find('first',array(
        'conditions' => array(
          'Project.id' => $this->controller->params['projectId']
        ),
        'contain' => array(
          'Company' => array('id','name','can_see_private'),
          'Person' => array('id','user_id','full_name','email','user_id')
        )
      ));
      
      if(empty($project))
      {
        $this->cakeError('prefixNotFound');
      }
      
      return $project['Project'];
    }
    
    
    /**
     * Load person
     * 
     * @access private
     * @return boolean
     */
    private function __loadPerson()
    {
      $person = $this->controller->Person->find('first',array(
        'conditions' => array(
          'Person.account_id' => $this->read('Account.id'),
          'Person.user_id'    => $this->user('id')
        ),
        'fields' => array('id','first_name','last_name','full_name','email','company_owner','user_id'),
        'contain' => array(
          'PersonAccess' => array(
            'Grant'
          ),
          'User' => array('id','username','email','email_format','email_send','last_activity'),
          'Company' => array('id','name','can_see_private')
        )
      ));
      
      if(empty($person))
      {
        $this->cakeError('personNotFound');
      }
      
      $record = $person['Person'];
      foreach($person as $key => $val)
      {
        if($key != 'Person') { $record[$key] = $val; }
      }
      
      return $record;
    }
    
    
    
    /**
     * Load prefix
     * 
     * @access private
     * @return boolean
     */
    public function __loadPrefix()
    {
      $this->prefixId = $this->read(Inflector::classify($this->prefix).'.id');
      
      //Aco ID
      $acoId = $this->Acl->Aco->field('id',array(
        'model' => $this->prefix,
        'foreign_key' => $this->prefixId
      ));
      
      $record = array(
        'name'    => $this->prefix,
        'id'      => $this->prefixId,
        'aco_id'  => $acoId
      );
      
      return $record;
    }
    
    
    /**
     * Load persons accounts they can access
     * 
     * @access private
     * @return boolean
     */
    private function __loadPersonAccess($key,$query = array())
    {
      $person = $this->read('Person');
      $model = Inflector::singularize($key);
    
      $personAccessIds = Set::extract($person,'/PersonAccess[model='.$model.']/id');

      $acos = $this->controller->Acl->Aco->Permission->find('all',array(
        'conditions' => array(
          'Aco.model'       => $model,
          'Aro.model'       => 'PersonAccess',
          'Aro.foreign_key' => $personAccessIds
        )
      ));
      $acos = Set::extract($acos,'{n}.Aco.foreign_key');
      
      $records = $this->controller->{$model}->find('all',array_merge(array(
        'conditions' => array(
          $model.'.id' => $acos
        ),
        'contain' => false
      ),$query));
      
      return $records;
    }
    
    
    
    /**
     * Load prefix permissions
     * 
     * @access private
     * @return boolean
     */
    private function __loadModelPermissions($name)
    {
      $prefix = strtolower($name);
      $recordId = $this->read($name.'.id');
    
      //Load model permissions
      $node = $this->Acl->Aco->node('controllers');
      $controllerNodeId = $node[0]['Aco']['id'];
      
      $acos = $this->controller->Acl->Aco->children($controllerNodeId,false,array('Aco.id','Aco.alias','Aco.parent_id'));

      //Controllers indexed
      $controllers = Set::extract($acos,'/Aco[parent_id='.$controllerNodeId.']');
      $controllers = Set::combine($controllers,'{n}.Aco.id','{n}.Aco');
      
      //Record id
      $accessModelId = $this->read($name.'.id');
      
      //Check we have access before adding permission tree
      $permissions = array();
      $records = $this->read(Inflector::pluralize($name));
      
      if(Set::extract($records,'/'.Inflector::camelize($name).'[id='.$accessModelId.']'))
      {
        //Person access
        $personAccess = Set::extract($this->read('Person'),'/PersonAccess[model='.$name.'][model_id='.$accessModelId.']');
        $personAccess = $personAccess[0]['PersonAccess'];
        
        //Grant Aro
        $this->controller->Person->PersonAccess->Grant->id = $personAccess['Grant']['id'];
        $grantAro = $this->Acl->Aro->node($this->controller->Person->PersonAccess->Grant);
        $grantAro = $grantAro[0]['Aro']['id'];
        
        //Grant Permissions
        $grantActions = $this->controller->Acl->Aco->Permission->find('all', array(
            'conditions' => array(
                'Permission.aro_id' => $grantAro
            ),
            'recursive' => '-1',
        ));
        $grantActions = Set::combine($grantActions,'{n}.Permission.aco_id','{n}.Permission');
        
        
        //Person Aro
        $this->controller->Person->PersonAccess->id = $personAccess['id'];
        $personAro = $this->Acl->Aro->node($this->controller->Person->PersonAccess);
        $personAro = $personAro[0]['Aro']['id'];
        
        //Person Permissions
        $personActions = $this->controller->Acl->Aco->Permission->find('all', array(
            'conditions' => array(
                'Permission.aro_id' => $personAro
            ),
            'recursive' => '-1',
        ));
        $personActions = Set::combine($personActions,'{n}.Permission.aco_id','{n}.Permission');

   
        //Build a list of controller permissions
        foreach($acos as $aco)
        {
          if($aco['Aco']['parent_id'] == $controllerNodeId) { continue; }
          
          $permission = array(
            'controller' => $controllers[$aco['Aco']['parent_id']]['alias'],
            'action' => $aco['Aco']['alias'],
            'aco_id' => $aco['Aco']['id'],
            'create' => 0,
            'read' => 0,
            'update' => 0,
            'delete' => 0,
          );
        
          if(isset($personActions[$aco['Aco']['id']]))
          {
            $permission['create'] = $personActions[$aco['Aco']['id']]['_create'];
            $permission['read'] = $personActions[$aco['Aco']['id']]['_read'];
            $permission['update'] = $personActions[$aco['Aco']['id']]['_update'];
            $permission['delete'] = $personActions[$aco['Aco']['id']]['_delete'];
          }
          elseif(isset($grantActions[$aco['Aco']['id']]))
          {
            $permission['create'] = $grantActions[$aco['Aco']['id']]['_create'];
            $permission['read'] = $grantActions[$aco['Aco']['id']]['_read'];
            $permission['update'] = $grantActions[$aco['Aco']['id']]['_update'];
            $permission['delete'] = $grantActions[$aco['Aco']['id']]['_delete'];
          }
          
          $permissions[] = $permission;
        }
      }
      
      return $permissions;
    }
    
    
    /**
     * Load prefix companies
     * 
     * @access private
     * @return boolean
     */
    private function __loadPrefixCompanies()
    {
      $prefix = $this->read('Prefix');    

      //Load companies
      $records = $this->Acl->Aco->Permission->find('all', array(
        'conditions' => array(
          'Aro.model' => 'Company',
          'Permission.aco_id' => $prefix['aco_id'],
          'Permission._read' => true
        ),
        'fields' => array('Aro.foreign_key')
      ));
      $records = Set::extract($records,'{n}.Aro.foreign_key');
      
      $companies = $this->controller->User->Company->find('all',array(
        'conditions' => array('Company.id'=>$records),
        'fields' => array('id','name','can_see_private','account_owner'),
        'order' => 'Company.created DESC',
        'contain' => false
      ));
      
      return $companies;
    }
    
    
    /**
     * Load prefix people
     * 
     * @access private
     * @return boolean
     */
    private function __loadPrefixPeople()
    {
      $prefix = $this->read('Prefix');    
    
      //Load people
      $people = $this->controller->Person->PersonAccess->find('all',array(
        'conditions' => array(
          'model' => Inflector::camelize($prefix['name']),
          'model_id' => $prefix['id']
        ),
        'contain' => array(
          'Person' => array(
            'fields' => array('id','full_name','first_name','last_name','email','title','company_owner','user_id','status'),
            'User' => array('id','last_activity'),
            'Company' => array('id','name')
          )
        )
      ));
      
      foreach($people as $key => $person)
      {
        //@todo Catch error if PersonAccess does not match with User record. This can happen when record manually deleted
        if(!isset($person['Person']['User'])) { continue; }
        
        $people[$key]['Company'] = $person['Person']['Company'];
        $people[$key]['User'] = $person['Person']['User'];
      }
      
      return $people;
    }   
    
    
    /**
     * Check permission
     *
     * @param string $path
     * @access public
     * @return int
     */
    public function check($prefix,$controller,$action)
    {
      $isAllowed = Set::extract($this->read('Permissions'),'/'.Inflector::camelize($prefix).'[controller='.$controller.'][action='.$action.'][read=1]');
      return !empty($isAllowed) ? true : false;
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
     * Reset data
     *
     * @access public
     * @return boolean
     */
    public function reset()
    {
      return $this->Session->delete('AuthAccount');
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
    public function read($path = null)
    {
      if(!$path) { return $this->access; }
      
      return $this->Session->read('AuthAccount.'.$path);
    }
    
    
    /**
     * Update user activity
     */
    public function updateUserActivity()
    {
      $user = $this->read('Person.User');

      if(
        empty($user['last_activity']) ||
        ((time() - strtotime($user['last_activity'])) / 60) > 5
      )
      {
        //Update      
        $this->controller->User->id = $user['id'];
        $this->controller->User->saveField('last_activity',date('Y-m-d H:i:s'),array('callbacks'=>false));
        $this->write('Person.User.last_activity',date('Y-m-d H:i:s'));
      }
    }
    
    
  }
  
?>
