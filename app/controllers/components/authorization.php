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
      $this->reset();
    
      //Load account, company, project
      $this->__loadAccount();
      $this->__loadCompany();
      $this->__loadProject();
      
      //Set prefix
      $this->__loadPrefix();
      
      //Load person
      $this->__loadPerson();
      
      //Accounts this person has access to
      $this->__loadPersonAccess('Account');
      
      //Projects this person has access to
      $this->__loadPersonAccess('Project',array(
        'contain' => array(
          'Account' => array(
            'fields' => array('id','name','slug')
          ),
          'Company' => array(
            'fields' => array('id','name')
          )
        )
      ));
      
      //Load permissions
      $this->__loadModelPermissions('Account');
      
      if(isset($this->controller->params['projectId']))
      {
        $this->__loadModelPermissions('Project');
      }
      
      $this->__loadPrefixCompanies();
      $this->__loadPrefixPeople();
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
    
      $this->write('Prefix',array(
        'name'    => $this->prefix,
        'id'      => $this->prefixId,
        'aco_id'  => $acoId
      ));
      
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
      if(!isset($this->controller->params['projectId'])) { return; }
    
      $project = $this->controller->Account->Project->find('first',array(
        'conditions' => array(
          'Project.id' => $this->controller->params['projectId']
        ),
        'contain' => array(
          'Company' => array('id','name','can_see_private'),
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
          'PersonAccess',
          'User' => array('id','username','email','email_format','email_send','last_activity'),
          'Company' => array('id','name','can_see_private')
        ),
        'cache' => array(
          'name' => 'person_'.$this->user('id').'_'.$this->read('Account.id'),
          'config' => 'auth',
        )
      ));
      
      if(empty($person))
      {
        $this->cakeError('personNotFound');
      }
      
      $write = $person['Person'];
      foreach($person as $key => $val)
      {
        if($key != 'Person') { $write[$key] = $val; }
      }
      
      $this->write('Person',$write);
    
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
    private function __loadPersonAccess($model,$query = array())
    {
      $person = $this->read('Person');
      $cacheKey = 'person_'.$person['id'].'_'.strtolower($model);
    
      $records = $this->controller->{$model}->findCached($cacheKey, 'auth');
   
      if(empty($records))
      {
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
        
        Cache::write($cacheKey,$records,'auth');
      }
      
      $this->write(Inflector::pluralize($model),$records);
      
      return true;
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
    
      $cacheKey = 'prefix_'.$prefix.'_'.$this->read('Account.id');
      $permissions = Cache::read($cacheKey,'auth');
   
      if(empty($permissions))
      {      
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
          //Person access ACO
          $personAccess = Set::extract($this->read('Person'),'/PersonAccess[model='.$name.'][model_id='.$accessModelId.']');
          $this->controller->Person->PersonAccess->id = $personAccess[0]['PersonAccess']['id'];
          $aro = $this->Acl->Aro->node($this->controller->Person->PersonAccess);
          $aro = $aro[0]['Aro']['id'];
          
          //What this person has been removed access from
          $personActions = $this->controller->Acl->Aco->Permission->find('all', array(
              'conditions' => array(
                  'Permission.aro_id' => $aro
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
              'create' => 1,
              'read' => 1,
              'update' => 1,
              'delete' => 1,
            );
          
            if(isset($personActions[$aco['Aco']['id']]))
            {
              $permission['create'] = $personActions[$aco['Aco']['id']]['_create'];
              $permission['read'] = $personActions[$aco['Aco']['id']]['_read'];
              $permission['update'] = $personActions[$aco['Aco']['id']]['_update'];
              $permission['delete'] = $personActions[$aco['Aco']['id']]['_delete'];
            }
            
            $permissions[] = $permission;
          }
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
      $prefix = $this->read('Prefix');    
      $cacheKey = 'companies_'.implode('_',$prefix);
      $companies = Cache::read($cacheKey,'auth');

      if(empty($companies))
      {
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
      $prefix = $this->read('Prefix');    
      $cacheKey = 'people_'.implode('_',$prefix);
      $people = Cache::read($cacheKey,'auth');

      if(empty($people))
      {
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
          $people[$key]['Company'] = $person['Person']['Company'];
          $people[$key]['User'] = $person['Person']['User'];
        }
        
        Cache::write($cacheKey,$people,'auth');
      }
      
      $this->write('People',$people);
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
    
    
  }
  
?>
