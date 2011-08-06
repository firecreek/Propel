<?php

  /**
   * Acl Filter Component
   *
   * @category Component
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
    public $components = array('Authorization','AclManager','Acl','Session');
    
    /**
     * Controller to check
     *
     * @access public
     * @var string
     */
    public $authController = null;
    
    /**
     * Action to check
     *
     * @access public
     * @var string
     */
    public $authAction = null;
    
    /**
     * Model to check
     *
     * @access public
     * @var string
     */
    public $authModel = null;
    
    /**
     * If the passed model id has to be the owner
     *
     * @access public
     * @var string
     */
    public $mustBeOwner = false;
    

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
      
      $this->Authorization->authorize = 'controller';
      $this->Authorization->autoRedirect = false;
      $this->Authorization->loginAction = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->logoutRedirect = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->userScope = array('User.status' => 1);
      $this->Authorization->actionPath = 'controllers/';
      
      $this->authController = $this->controller->name;
      $this->authAction = $this->controller->action;
    }


    /**
     * Check authorization
     *
     * @access public
     * @return void
     */
    public function check()
    {
      $userRoleId = $this->Authorization->user('role_id');

      if(!isset($this->controller->params['prefix']))
      {
        return true;
      }
      elseif(!$this->Authorization->user())
      {
        $this->cakeError('notLoggedIn');
      }
      elseif($userRoleId == 1)
      {
        //Admin
        return true;
      }
      elseif($userRoleId == 2)
      {
        /**
         * Permission checking
         */
        $isAllowed  = false;
        
        $prefix      = $this->Authorization->read('Prefix');
        $permissions = $this->Authorization->read('Permissions');
        
        //Controller permissions
        $isAllowed = $this->Authorization->check($prefix['name'],$this->authController,$this->authAction);

        if(!$isAllowed)
        {
          $this->cakeError('badCrudAccess');
        }
        
        //Check record
        //This will force set the isAllowed variable to false if problems are found
        //Loads in more data so correct error messages can be given
        if(
          isset($this->modelId) || 
          (
            isset($this->controller->params['pass'][0]) && 
            is_numeric($this->controller->params['pass'][0]))
          )
        {
          if(!isset($this->modelId))
          {
            $this->modelId = isset($this->controller->params['pass'][0]) ? $this->controller->params['pass'][0] : null;
          }
          
          //Check individual record belongs to this prefix and privacy settings
          $modelAlias = Inflector::classify($this->authController);
          if(!empty($this->authModel))
          {
            $modelAlias = $this->authModel;
          }
          
          $fieldKey = $prefix['name'].'_id';
          
          //Check record
          if(
            isset($this->controller->{$modelAlias}) &&
            is_object($this->controller->{$modelAlias}) &&
            $this->controller->{$modelAlias}->hasField($fieldKey)
          )
          {
            //Build fields
            $fields = array('id',$fieldKey);
            $contain = array();
            
            if($this->controller->{$modelAlias}->hasField('private'))
            {
              $fields[] = 'private';
            }
            
            //@todo Fix this, change to normalized method check
            if(isset($this->controller->{$modelAlias}->belongsTo['Person']))
            {
              $contain['Person'] = array('id','company_id');
            }
            
            //Set auth data
            if($this->controller->{$modelAlias}->Behaviors->enabled('Auth'))
            {
              $this->controller->{$modelAlias}->setAuthState($this->Session->read('AuthAccount'));
            }
            
            //Load record
            $modelRecord = $this->controller->{$modelAlias}->find('first',array(
              'conditions' => array(
                $modelAlias.'.id' => $this->modelId
              ),
              'contain' => $contain,
              'fields' => $fields
            ));
            
            //Check record
            if(empty($modelRecord))
            {
              //No record found
              $this->cakeError('recordNotFound');
            }
            elseif($modelRecord[$modelAlias][$fieldKey] != $prefix['id'])
            {
              //Record not matching the prefix record id, e.g. project_id=7 but record shows project_id=1
              $this->cakeError('recordWrongPrefix');
            }
            elseif(isset($modelRecord[$modelAlias]['private']) && $modelRecord[$modelAlias]['private'] == true)
            {
              //No permission
              if($modelRecord['Person']['company_id'] != $this->Authorization->read('Company.id'))
              {
                $this->cakeError('recordIsPrivate');
              }
            }
            elseif($this->mustBeOwner && $modelRecord['Person']['id'] != $this->Authorization->read('Person.id'))
            {
              $this->cakeError('permissionDenied');
            }
            
          }
        }
      
        //Throw error
        if(!$isAllowed)
        {
          $this->cakeError('unknownAclError');
        }
        
        return true;
      }
      
    }
    
    
  }
  
?>
