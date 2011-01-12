<?php

  /**
   * Todo Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Todo extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Todo';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Containable'
    );
    
    /**
     * Load items into set?
     *
     * @access private
     * @var string
     */
    private $_loadItems = false;
    
    
    
    /**
     * Before find
     *
     * @access public
     * @return array
     */
    public function beforeFind($query)
    {
      if(isset($query['items']))
      {
        $this->_loadItems = $query['items'];
      }
      return $query;
    }
    
    
    /**
     * After find
     *
     * @access public
     * @return array
     */
    public function afterFind($results)
    {
      if($this->_loadItems !== false)
      {
        foreach($results as $key => $result)
        {
          $results[$key]['TodoItem'] = $this->TodoItem->find('all',Set::merge(array(
            'conditions' => array(
              'TodoItem.todo_id' => $result[$this->alias]['id'],
            ),
            'contain' => array(),
            'order' => 'TodoItem.position ASC'
          ),$this->_loadItems));
          
          //Recently completed
          if(isset($this->_loadItems['recent']) && $this->_loadItems['recent'] == true)
          {
            $results[$key]['TodoItemRecent'] = $this->TodoItem->find('all',Set::merge(array(
              'conditions' => array(
                'TodoItem.todo_id' => $result[$this->alias]['id'],
                'TodoItem.completed' => true
              ),
              'contain' => array('Responsible'),
              'order' => 'TodoItem.completed_date DESC'
            ),$this->_loadItems['recent']));
          }
          
          //Count
          if(isset($this->_loadItems['count']) && $this->_loadItems['count'] == true)
          {
            $results[$key]['TodoItemCountCompleted'] = $this->TodoItem->find('count',array(
              'conditions' => array(
                'TodoItem.todo_id' => $result[$this->alias]['id'],
                'TodoItem.completed' => true
              ),
              'recursive' => -1
            ));
          }
        }
      
        $this->_loadItems = false;
      }
      
      return $results;
    }
    
    
    var $validate = array(
      'project_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'name' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'time_enabled' => array(
        'boolean' => array(
          'rule' => array('boolean'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'private' => array(
        'boolean' => array(
          'rule' => array('boolean'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'position' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'project_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'milestone_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      )
    );

    var $hasMany = array(
      'TodoItem' => array(
      )
    );

  }

?>
