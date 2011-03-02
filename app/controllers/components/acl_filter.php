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
    public $components = array('Authorization','AclManager','Acl','Session');
    
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
      
      $this->Authorization->authorize = 'controller';
      $this->Authorization->autoRedirect = false;
      $this->Authorization->loginAction = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->logoutRedirect = array('prefix' => false, 'controller' => 'users', 'action' => 'login');
      $this->Authorization->userScope = array('User.status' => 1);
      $this->Authorization->actionPath = 'openbase/';
      
      //Extended action map
      if(isset($this->controller->actionMap))
      {
        $this->actionMap = array_merge($this->actionMap,$this->controller->actionMap);
      }
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
      else
      {
        /**
         * Permission checking
         */
        $isAllowed  = false;
        $prefix     = isset($this->controller->params['prefix']) ? $this->controller->params['prefix'] : null;
        $modelId    = $this->Authorization->read('Prefix.id');
        $accountId  = $this->Authorization->read('Account.id');
        $personAro  = $this->Authorization->read('Person._aro_id');
        
        //Action map
        $action     = str_replace($prefix.'_','',$this->controller->action);
        $actionKey  = $this->actionMap[$action];
        
        //Controller name to check
        if(!isset($this->controllerName))
        {
          if(isset($this->controller->associatedController))
          {
            $this->controllerName = $this->controller->associatedController;
          }
          else
          {
            $this->controllerName = $this->controller->name;
          }
        }
        
        
        //Check if this person is allowed to be in this controller and has the correct CRUD access
        $permissionNode = $this->AclManager->acoNode('opencamp/'.Inflector::pluralize($prefix).'/'.$modelId.'/'.$this->controllerName);
        
        if(!empty($permissionNode))
        {
          $isAllowed = $this->controller->Acl->Aco->Permission->find('count', array(
            'conditions' => array(
              'Aro.model' => 'Person',
              'Permission.aco_id' => $permissionNode[0]['Aco']['id'],
              'Permission.aro_id' => $this->Authorization->read('Person._aro_id'),
              'Permission.'.$actionKey => true
            ),
            'cache' => array(
              'name' => 'permission_'.$permissionNode[0]['Aco']['id'].'_'.$this->Authorization->read('Person._aro_id').'_'.$actionKey,
              'config' => 'acl',
            )
          ));
          
          if(!$isAllowed)
          {
            $this->cakeError('badCrudAccess');
          }
        }
        
        //Check record
        //This will force set the isAllowed variable to false if problems are found
        //Loads in more data so correct error messages can be given
        if(
          $actionKey !== '_create' &&
          (
            isset($this->modelId) || 
            (
              isset($this->controller->params['pass'][0]) && 
              is_numeric($this->controller->params['pass'][0]))
            )
          )
        {
          if(!isset($this->modelId))
          {
            $this->modelId = isset($this->controller->params['pass'][0]) ? $this->controller->params['pass'][0] : null;
          }
          
          //Check individual record belongs to this prefix and privacy settings
          $modelAlias = Inflector::classify($this->controllerName);
          $fieldKey   = $prefix.'_id';
          
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
            
            //Check if private
            if(empty($modelRecord))
            {
              //No record found
              $this->cakeError('recordNotFound');
            }
            elseif($modelRecord[$modelAlias][$fieldKey] != $modelId)
            {
              //Record not matching the prefix
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
