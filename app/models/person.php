<?php

  /**
   * Person Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Person extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var string
     */
    public $name = 'Person';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array('Containable');
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'company_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'country_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'first_name' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          'allowEmpty' => false
        ),
      ),
      'last_name' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          'allowEmpty' => false
        ),
      )
    );
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'company_id'
      ),
      'User' => array(
        'className' => 'User',
        'foreignKey' => 'user_id'
      ),
      'Country' => array(
        'className' => 'Country',
        'foreignKey' => 'country_id'
      )
    );
    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Comment' => array(
        'className' => 'Comment',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'Log' => array(
        'className' => 'Log',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'MilestoneResponsible' => array(
        'className' => 'Milestone',
        'foreignKey' => 'responsible_person_id',
      ),
      'MilestoneCompleted' => array(
        'className' => 'Milestone',
        'foreignKey' => 'completed_person_id',
        'conditions' => array('MilestoneCompleted.completed' => true),
      ),
      'Post' => array(
        'className' => 'Post',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'Time' => array(
        'className' => 'Time',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'TodoItem' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'person_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'TodoItemResponsible' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'responsible_person_id',
      ),
      'TodoItemCompleted' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'completed_person_id',
        'conditions' => array('TodoItemCompleted.completed' => true),
      ),
    );
    
    /**
     * Has Access
     *
     * If this User.id is listed in People, which is associated with the main company of the account
     *
     * @param string $slug Account slug
     * @param array $user User data array
     * @access public
     * @return boolean
     */
    public function hasAccess($userId,$accountId)
    {
      return $this->find('count',array(
        'conditions' => array(
          'Person.user_id' => $userId,
          'Company.account_id' => $accountId
        ),
        'contain' => array(
          'Company' => array('id')
        )
      ));
    }

  }
  
?>
