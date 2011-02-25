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
      'Containable',
      'Auth',
      'Responsible',
      'Due',
      'Private'
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
      'name' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      ),
      'time_enabled' => array(
        'boolean' => array(
          'rule' => array('boolean')
        ),
      ),
      'private' => array(
        'boolean' => array(
          'rule' => array('boolean')
        ),
      ),
      'position' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
    );
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'project_id',
        'counterCache' => true,
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'milestone_id',
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id',
        'type' => 'INNER'
      )
    );

    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'TodoItem' => array(
      )
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
    
    
    
    /**
     * Find active lists
     *
     * With privacy checks
     *
     * @access public
     * @return array
     */
    public function findActive($projectId)
    {
      return $this->find('all',array(
        'conditions' => array(
          'AND' => array(
            array(
              'Todo.project_id' => $projectId,
              'OR' => array(
                'Todo.todo_items_count' => 0,
                'NOT' => array(
                  'Todo.todo_items_count = Todo.todo_items_completed_count'
                )
              )
            ),
            array(
              'OR' => array(
                array('Todo.private' => 0),
                array(
                  'AND' => array(
                    'Todo.private' => 1,
                    'Person.company_id' => $this->authRead('Company.id')
                  )
                ),
              )
            )
          )
        ),
        'fields' => array('id','name'),
        'order' => 'Todo.name ASC',
        'contain' => array('Person'=>array('id')),
        'items' => false
      ));
    }
    
    
    
    /**
     * Find completed lists
     *
     * @access public
     * @return array
     */
    public function findCompleted($projectId)
    {
      return $this->find('all',array(
        'conditions' => array(
          'Todo.project_id' => $projectId,
          'Todo.todo_items_count >' => 0,
          'Todo.todo_items_count = Todo.todo_items_completed_count',
          array(
            'OR' => array(
              array('Todo.private' => 0),
              array(
                'AND' => array(
                  'Todo.private' => 1,
                  'Person.company_id' => $this->authRead('Company.id')
                )
              ),
            )
          )
        ),
        'fields' => array('id','name'),
        'order' => 'Todo.name ASC',
        'contain' => array('Person'=>array('id')),
        'items' => false
      ));
    }
    

  }

?>
