<?php

  /**
   * Todo Item Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class TodoItem extends AppModel
  {
    /**
     * Model to use
     *
     * @access public
     * @var string
     */
    public $useTable = 'todos_items';
    
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'TodoItem';
    
    /**
     * Display field
     *
     * @access public
     * @var string
     */
    public $displayField = 'description';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Responsible',
      'Containable',
      'Completable',
      'Auth',
      'Commentable',
      'Searchable' => array(
        'title' => array('model'=>'Todo','field'=>'name','associatedKey'=>'todo_id'),
        'extra' => array(
          array('model'=>'Todo','field'=>'id','associatedKey'=>'todo_id')
        )
      ),
      'Loggable' => array(
        'titleField' => 'description',
        'create' => true,
        'edit' => false,
        'delete' => false,
        'complete' => true
      ),
      'Cached' => array(
        'prefix' => array(
          'todo',
          'todo_item',
        ),
      ),
    );
    

    /**
     * Belongs to
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
      'todo_id' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'position' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'comment_count' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'completed' => array(
        'boolean' => array(
          'rule' => array('boolean')
        ),
      ),
      'person_id' => array(
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
        'conditions' => '',
        'fields' => '',
        'order' => ''
      ),
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'todo_id',
        'counterCache' => 'todo_items_count'
      )
    );
    
    
    /**
     * Before save
     *
     * @access public
     * @return boolean
     */
    public function beforeSave($options)
    {
      
      if(isset($this->data[$this->alias]) && !isset($this->data[$this->alias]['position']) && isset($this->data[$this->alias]['todo_id']))
      {
        //Get last record, add position
        if($lastRecord = $this->find('first',array(
          'conditions' => array(
            'todo_id' => $this->data[$this->alias]['todo_id']
          ),
          'fields' => 'position',
          'contain' => false,
          'order' => 'position DESC'
        )))
        {
          $this->data[$this->alias]['position'] = $lastRecord[$this->alias]['position'] + 1;
        }
      }
    
      return true;
    }
    
    
    /**
     * Update
     *
     * @todo Passing the ID
     * @access public
     * @return boolean
     */
    public function updateAll($fields,$conditions = true)
    {
      if(parent::updateAll($fields,$conditions))
      {
        if(isset($conditions['TodoItem.id']))
        {
          $this->updateCompletedCount($conditions['TodoItem.id']);
        }
        return true;
      }
      
      return false;
    }
    
    
    /**
     * Update completed count
     *
     * @access public
     * @return boolean
     */
    public function updateCompletedCount($todoItemId)
    {
      //Get main
      $this->id = $todoItemId;
      
      $this->recursive = -1;
      $todoId = $this->read('todo_id');
      $todoId = $todoId[$this->alias]['todo_id'];
      
      //Count
      $count = $this->find('count',array(
        'conditions' => array(
          $this->alias.'.todo_id' => $todoId,
          $this->alias.'.completed' => true
        )
      ));
      
      //Update
      $this->Todo->id = $todoId;
      
      return $this->Todo->saveField('todo_items_completed_count',$count);
    }
    
    
    /**
     * Project completed count
     *
     * @access public
     * @return int
     */
    public function projectCompletedCount($projectId)
    {
      return $this->find('count',array(
        'conditions' => array(
          $this->alias.'.project_id' => $projectId,
          $this->alias.'.completed' => true
        ),
        'recursive' => -1
      ));
    }
    
  }
?>
