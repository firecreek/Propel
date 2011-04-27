<?php

  /**
   * Auth Behavior
   *
   * @category Behavior
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AuthBehavior extends ModelBehavior
  {
    /**
     * Auth data from session
     *
     * @access public
     * @var array
     */
    public $authData = array();


    /**
     * Setup behavior
     * 
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
    }


    /**
     * Set auth data
     *
     * Normally set from app_controller. Check if this behavior exists then pass Permission array
     * 
     * @access public
     * @return void
     */
    public function setAuthState(&$model, $data)
    {
      $this->authData = $data;
    }


    /**
     * Is owner
     * 
     * @access public
     * @return void
     */
    public function isOwner(&$model,$options = array())
    {
      $record = $model->find('first',array(
        'conditions'  => array('id'=>$model->id),
        'recursive'   => -1,
        'fields'      => 'person_id'
      ));
    
      if(!empty($record) && $record[$model->alias]['person_id'] == $this->_authRead('Person.id'))
      {
        return true;
      }
      else
      {
        return false;
      }
    }


    /**
     * Create check
     * 
     * @access public
     * @return void
     */
    public function canCreate(&$model,$options = array())
    {
      if($this->authCheck($model,'create') && $this->authIsRelated($model,'project_id',$this->_authRead('Project.id')))
      {
        return true;
      }
      else
      {
        return false;
      }
    }


    /**
     * Check if record can be updated by current person
     * 
     * @access public
     * @return void
     */
    public function canUpdate(&$model,$options = array())
    {
      if($this->authCheck($model,'update') && $this->authIsRelated($model,'project_id',$this->_authRead('Project.id')))
      {
        return true;
      }
      else
      {
        return false;
      }
    }


    /**
     * Check if record can be delete by current person
     * 
     * @access public
     * @return void
     */
    public function canDelete()
    {
      if($this->authCheck($model,'update') && $this->authIsRelated($model,'project_id',$this->_authRead('Project.id')))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    
    
    /**
     * Check permission
     *
     * @param string $alias ACO alias
     * @param string $type create, update, permission type
     * @access public
     * @return boolean
     */
    public function authCheck(&$model,$type)
    {
      $alias = Inflector::pluralize($model->alias);
      return Set::extract($this->authData,'Permissions.Project.'.$alias.'.'.$type);
    }
    
    
    /**
     * Read authData
     *
     * @access private
     * @return boolean
     */
    public function _authRead($path)
    {
      return $this->authRead(new Object,$path);
    }
    
    
    /**
     * Read authData
     *
     * @access public
     * @return boolean
     */
    public function authRead(&$model,$path)
    {
      return Set::extract($this->authData,$path);
    }
    
    
    /**
     * Is related
     *
     * @access private
     * @return boolean
     */
    private function authIsRelated(&$model,$fieldKey,$foreignId)
    {
      return $model->find('count',array(
        'conditions' => array(
          'id'        => $model->id,
          $fieldKey   => $foreignId
        ),
        'recursive' => -1
      ));
    }
    
    

  }

?>
