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
    public $components = array('Session','Acl','RequestHandler','AclManager');
    
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
        $this->accountSlug  = $this->controller->params['accountSlug'];
        
        $this->prefix = null;
        
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
     * @access public
     * @return void
     */
    public function _loadUser()
    {
      $this->__loadAccount();
      $this->__loadCompany();
      $this->__loadPerson();
      $this->__loadPrefixPermissions('Account');
      
      if($this->prefix == 'project')
      {
        $this->__loadProject();
        $this->__loadPrefixPermissions('Project');
      }
      
      $this->prefixId = $this->read(Inflector::classify($this->prefix).'.id');
      $modelRootNode = $this->AclManager->acoNode('opencamp/'.Inflector::pluralize($this->prefix).'/'.$this->prefixId);
      $this->prefixAco = $modelRootNode[0]['Aco']['id'];
      
      $this->write('Prefix',array(
        'name' => $this->prefix,
        'id' => $this->prefixId,
        'aco' => $this->prefixAco
      ));
      
      $this->__loadPersonAccounts();
      $this->__loadPersonProjects();
      $this->__loadPrefixCompanies();
      $this->__loadPrefixPeople();
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
        ),
        'cache' => array(
          'name' => 'account_'.$this->accountSlug,
          'config' => 'auth',
        )
      ));
      
      if(empty($account))
      {
        $this->cakeError('accountNotFound');
      }
      
      $this->write('Account',$account['Account']);
      
      return true;
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
        'contain' => false,
        'cache' => array(
          'name' => 'company_'.$this->read('Account.id'),
          'config' => 'auth',
        )
      ));
      
      if(empty($company))
      {
        $this->cakeError('companyNotFound');
      }
      
      $this->write('Company',$company['Company']);
      
      return true;
    }
    
    
    /**
     * Load project
     * 
     * @access private
     * @return boolean
     */
    private function __loadProject()
    {
      $project = $this->controller->Account->Project->find('first',array(
        'conditions' => array(
          'Project.id' => $this->controller->params['projectId']
        ),
        'contain' => array(
          'Company' => array('id','name','private'),
          'Person' => array('id','user_id','full_name','email','user_id')
        ),
        'cache' => array(
          'name' => 'project_'.$this->controller->params['projectId'],
          'config' => 'auth',
        )
      ));
      
      if(empty($project))
      {
        $this->cakeError('prefixNotFound');
      }
      
      $this->write('Project',$project['Project']);
      
      return true;
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
          'User' => array('id','username','email','email_format','email_send','last_activity')
        ),
        'cache' => array(
          'name' => 'person_'.$this->user('id'),
          'config' => 'auth',
        )
      ));
      
      if(empty($person))
      {
        $this->cakeError('personNotFound');
      }
      
      //Find Person aro
      $aro = $this->Acl->Aro->find('first', array(
        'conditions' => array(
          'Aro.model' => 'Person',
          'Aro.foreign_key' => $person['Person']['id'],
        ),
        'recursive' => -1,
        'cache' => array(
          'name' => 'person_aco_'.$person['Person']['id'],
          'config' => 'auth',
        )
      ));
      $person['Person']['_aro_id'] = $aro['Aro']['id'];
      
      if(empty($aro))
      {
        $this->cakeError('personNoAro');
      }
      
      $this->write('Person',$person['Person']);
    
      //Update activity time
      //@todo When caching is recoded then clear the person cache here
      if(
        empty($person['User']['last_activity']) ||
        ((time() - strtotime($person['User']['last_activity'])) / 60) > 5
      )
      {
        $this->controller->User->id = $person['User']['id'];
        $this->controller->User->saveField('last_activity',date('Y-m-d H:i:s'));
      }
      
      return true;
    }
    
    
    /**
     * Load persons accounts they can access
     * 
     * @access private
     * @return boolean
     */
    private function __loadPersonAccounts()
    {
      $aroId = $this->read('Person._aro_id');
      $accounts = $this->controller->Account->findCached('accounts_'.$aroId, 'auth');
   
      if(empty($accounts))
      {
        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission._read' => true,
            'Aco.model' => 'Accounts',
          ),
          'fields' => array('Aco.foreign_key','Permission.*'),
          'cache' => array(
            'name' => 'accounts_aro_'.$aroId,
            'config' => 'auth',
          )
        ));
        
        if(!empty($records))
        {
          $accounts = $this->controller->Account->find('all',array(
            'conditions' => array(
              'Account.id'      => Set::extract($records,'{n}.Aco.foreign_key')
            ),
            'contain' => false,
            'fields' => array('id','name','slug'),
            'cache' => array(
              'name' => 'accounts_'.$aroId,
              'config' => 'auth',
            )
          ));
        }
      }
      
      $this->write('Accounts',$accounts);
      
      return true;
    }
    
    
    /**
     * Load persons projects they belong to
     * 
     * @access private
     * @return boolean
     */
    private function __loadPersonProjects()
    {
      $aroId = $this->read('Person._aro_id');
      $projects = $this->controller->Account->Project->findCached('projects_'.$aroId, 'auth');
   
      if(empty($projects))
      {
        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission._read' => true,
            'Aco.model' => 'Projects',
          ),
          'fields' => array('Aco.foreign_key','Permission.*'),
          'cache' => array(
            'name' => 'projects_aro_'.$aroId,
            'config' => 'auth',
          )
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
                'fields' => array('id','name','slug')
              ),
              'Company' => array(
                'fields' => array('id','name')
              )
            ),
            'cache' => array(
              'name' => 'projects_'.$aroId,
              'config' => 'auth',
            )
          ));
        }
      }
      
      $this->write('Projects',$projects);
      
      return true;
    }
    
    
    /**
     * Load prefix permissions
     * 
     * @access private
     * @return boolean
     */
    private function __loadPrefixPermissions($name)
    {
      $prefix = strtolower($name);
      $recordId = $this->read($name.'.id');
    
      $cacheKey = 'prefix_'.$prefix.'_'.$this->read('Account.id').'_'.$this->read('Person._aro_id');
      $permissions = Cache::read($cacheKey,'auth');
   
      if(empty($permissions))
      {
        //Load project permissions
        $node = $this->Acl->Aco->node('opencamp/'.Inflector::pluralize($prefix).'/'.$recordId);

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
            'Permission.aro_id' => $this->read('Person._aro_id'),
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
        
        Cache::write($cacheKey,$permissions,'auth');
      }
      
      $this->write('Permissions.'.$name,$permissions);
      
      return true;
    }
    
    
    /**
     * Load prefix companies
     * 
     * @access private
     * @return boolean
     */
    private function __loadPrefixCompanies()
    {
      $cacheKey = 'companies_aco_'.$this->prefixAco;
      $companies = Cache::read($cacheKey,'auth');

      if(empty($companies))
      {
        //Load companies
        $records = $this->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Company',
            'Permission.aco_id' => $this->prefixAco,
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
            'name' => 'companies_'.$this->prefixAco,
            'config' => 'auth',
          )
        ));
        
        Cache::write($cacheKey,$companies,'auth');
      }
      
      $this->write('Companies',$companies);
    }
    
    
    /**
     * Load prefix people
     * 
     * @access private
     * @return boolean
     */
    private function __loadPrefixPeople()
    {
      $cacheKey = 'people_aco_'.$this->prefixAco;
      $people = Cache::read($cacheKey,'auth');

      if(empty($people))
      {
        //Load people
        $records = $this->controller->Acl->Aco->Permission->find('all', array(
          'conditions' => array(
            'Aro.model' => 'Person',
            'Permission.aco_id' => $this->prefixAco,
            'Permission._read' => true
          ),
          'fields' => array('Aro.foreign_key')
        ));
        $records = Set::extract($records,'{n}.Aro.foreign_key');
    
        $people = $this->controller->Person->find('all',array(
          'conditions' => array(
            'Person.id' => $records
          ),
          'fields' => array('id','full_name','first_name','last_name','email','title','company_owner','user_id'),
          'contain' => array(
            'Company' => array('id','name'),
            'User' => array('id','last_activity')
          ),
          'cache' => array(
            'name' => 'people_aco_'.$this->prefixAco,
            'config' => 'auth',
          )
        ));
        
        Cache::write($cacheKey,$people,'auth');
      }
      
      $this->write('People',$people);
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
      return Set::extract($this->access,$path);
    }
    
    
  }
  
?>
