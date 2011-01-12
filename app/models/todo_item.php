<?php

  class TodoItem extends AppModel
  {
    var $useTable = 'todos_items';
    
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'TodoItem';
    
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
      'Commentable',
      'Auth'
    );
    
    
    
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
      'todo_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
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
      'comment_count' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'completed' => array(
        'boolean' => array(
          'rule' => array('boolean'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'person_id' => array(
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
    
  }
?>
