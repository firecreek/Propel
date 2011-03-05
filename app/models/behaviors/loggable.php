<?php

  /**
   * Loggable Behavior
   *
   * Based on Logable Behavior by Alexander Morland
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class LoggableBehavior extends ModelBehavior
  {
    public $dueOptions = array();
    
    public $defaults = array(
      'create' => true,
      'edit' => true,
      'delete' => true,
      'complete' => true,
      'enabled' => true,
      'titleField' => 'title'
    );

    
    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
      $this->settings[$model->alias] = array_merge($this->defaults,$config);
    
      $this->Log =& ClassRegistry::init('Log');
    }
    
    
    /**
     * Enable logging
     *
     * @access public
     * @return boolean
     */
    public function enableLog(&$model) {
      $this->settings[$model->alias]['enabled'] = true;
      return $this->settings[$model->alias]['enabled'];
    }
    
    
    /**
     * Disable logging
     *
     * @access public
     * @return boolean
     */
    public function disableLog(&$model) {
      $this->settings[$model->alias]['enabled'] = false;
      return $this->settings[$model->alias]['enabled'];
    }
    
    
    /**
     * Before delete
     * 
     * @access public
     * @return string
     */
    public function beforeDelete(&$model)
    {
      //Skip
      if(!$this->settings[$model->alias]['enabled'])
      {
        return true;
      }
      elseif(!$this->settings[$model->alias]['delete'])
      {
        return true;
      }
      
      $model->recursive = -1;
      $model->read();
      
      return true;
    }
    
    
    /**
     * After delete
     * 
     * @access public
     * @return string
     */
    public function afterDelete(&$model)
    {
      //Skip
      if(!$this->settings[$model->alias]['enabled'])
      {
        return true;
      }
      elseif(!$this->settings[$model->alias]['delete'])
      {
        return true;
      }
      
      $logData = array(
        'action' => 'delete'
      );
      
      return $this->_saveLog($model,$logData);
    }
    
  
    /**
     * After save
     * 
     * @access public
     * @return string
     */
    public function afterSave(&$model, $created)
    {
      //Enabled
      if(!$this->settings[$model->alias]['enabled'])
      {
        return true;
      }
      
      //Check action
      $action = 'edit';
      if($created)
      {
        $action = 'create';
      }
      elseif(isset($model->data[$model->alias]['completed']) && $model->data[$model->alias]['completed'] == true)
      {
        $action = 'complete';
      }
      
      //Skip
      if(!$this->settings[$model->alias][$action])
      {
        return true;
      }
      
      //Build and save log
      $logData = array(
        'action'      => $action,
      );
      
      $this->_saveLog($model,$logData);
      
      return true;
    }
    
    
    /**
     * Custom log
     * 
     * @access public
     * @return string
     */
    public function customLog(&$model, $action, $id, $logData = array(), $options = array())
    {
      $_logData = array(
        'action'      => $action,
      );
      $logData = array_merge($_logData,$logData);
      
      $model->id = $id;
      
      return $this->_saveLog($model,$logData);
    }
    
    
    /**
     * Save log
     *
     * Auto populate, model, model_id, person_id and title/description
     * 
     * @access public
     * @return boolean
     */
    private function _saveLog(&$model, $logData, $title = null)
    {
      $_logData = array(
        'model'       => $model->alias,
        'person_id'   => $model->authRead('Person.id'),
        'account_id'  => $model->authRead('Account.id'),
        'project_id'  => $model->authRead('Project.id'),
      );
      
      $logData = array_merge($_logData,$logData);
      
      //Model id
      if($model->id)
      {
        $logData['model_id'] = $model->id;
      }
      elseif($model->insertId)
      {
        $logData['model_id'] = $model->insertId;
      }
      elseif(isset($model->data[$model->alias]['id']) && is_numeric($model->data[$model->alias]['id']))
      {
        $logData['model_id'] = $model->data[$model->alias]['id'];
      }
      
      //Checks
      if(!isset($logData['model_id']) || empty($logData['model_id']))
      {
        return false;
      }
      
      //
      $field = $this->settings[$model->alias]['titleField'];
      
      //Build description
      if(!isset($logData['description']))
      {
        if(isset($model->data[$model->alias][$field]))
        {
          $logData['description'] = $model->data[$model->alias][$field];
        }
        elseif($model->hasField($model->displayField))
        {
          $logData['description'] = $model->field($field);
        }
      }
      
      //Build description
      if($model->hasField('private'))
      {
        $logData['private'] = $model->field('private');
      }
      
      $this->Log->create();
      return $this->Log->save($logData);
    }
    

  }

?>
