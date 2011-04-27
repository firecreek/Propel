<?php

  /**
   * Category Model
   *
   * @category Model
   * @package  Propel
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   */
  class Category extends AppModel
  {
    /**
     * Name
     *
     * @access public
     * @var string
     */
    public $name = 'Category';
    
    /**
     * Display field
     *
     * @access public
     * @var string
     */
    public $displayField = 'name';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Auth',
      'Containable'
    );
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'project_id' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'type' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      ),
      'name' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      )
    );
    
    /**
     * Belongs to
     *
     * @access public
     * @var string
     */
    public $belongsTo = array(
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'project_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      )
    );
    
    
    
    /**
     * Create default categories
     *
     * @param int $accountId
     * @param int $projectId
     * @access public
     * @return boolean
     */
    public function createDefaults($accountId = null,$projectId = null)
    {
      //Build list
      if(!$projectId)
      {
        $records = $this->find('all',array(
          'fields' => array('type','name'),
          'contain' => false,
          'conditions' => array(
            'account_id' => null,
            'project_id' => null,
          )
        ));
      }
      else
      {
        $records = $this->find('all',array(
          'fields' => array('type','name'),
          'contain' => false,
          'conditions' => array(
            'account_id' => $accountId,
            'project_id' => null,
          )
        ));
      }
      
      //Insert
      foreach($records as $record)
      {
        $this->create();
        
        $record[$this->alias]['account_id'] = $accountId;
        if($projectId) { $record[$this->alias]['project_id'] = $projectId; }
        
        $this->save($record);
      }
      
      return true;
    }
    
    
    /**
     * Load by type
     *
     * @access public
     * @return array
     */
    public function findByType($type)
    {
      return $this->find('list',array(
        'conditions' => array(
          $this->alias.'.type'        => $type,
          $this->alias.'.project_id'  => $this->authRead('Project.id')
        ),
        'fields' => array('id','name'),
        'contain' => false
      ));
    }
    
  }
  
?>
