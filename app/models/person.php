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
    public $actsAs = array(
      'Acl' => array('type' => 'requester'),
      'Containable'
    );
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $virtualFields = array(
    );
    
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
      'email' => array(
        'email' => array(
          'rule' => array('email'),
          'allowEmpty' => false,
          'message' => 'Enter a valid email address'
        ),
        'unique' => array(
          'rule' => 'uniqueEmail',
          'message' => 'This account already has a person with this email address'
        )
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
        'foreignKey' => 'company_id',
        'dependant' => true
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
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
      parent::__construct($id, $table, $ds);
      
      $this->virtualFields['full_name'] = sprintf('CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias);
    }
    
    
    /**
     * Parent Node
     *
     * @access public
     * @return void
     */
    public function parentNode()
    {
    }
    
    
    /**
     * Validation check if company name is unique to this account
     *
     * @access public
     * @return boolean
     */
    public function uniqueEmail()
    {
      //Load company record to get account_id
      $accountId = $this->Company->field('account_id',array('id'=>$this->data[$this->alias]['company_id']));
      
      $this->Company->bindModel(array(
        'belongsTo'=>array(
          'Person' => array(
            'className' => 'Person',
            'foreignKey' => false,
            'type' => 'INNER',
            'conditions' => array(
              'Person.company_id = Company.id',
              'Person.email' => $this->data[$this->alias]['email']
            )
          )
        )
      ));
      
      $check = $this->Company->find('count',array(
        'conditions' => array(
          'account_id'  => $accountId
        ),
        'contain' => array(
          'Person'
        )
      ));
      
      return $check ? false : true;
    }

  }
  
?>
