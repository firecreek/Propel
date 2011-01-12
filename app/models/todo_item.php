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

    var $belongsTo = array(
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
        'conditions' => '',
        'fields' => '',
        'order' => ''
      )
    );
  }
?>
