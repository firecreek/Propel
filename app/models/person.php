<?php

  /**
   * Person Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
      'Containable',
      'Cached' => array(
        'prefix' => array(
          'person',
          'people',
          'prefix',   /** Permissions */
        ),
      ),
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
      'Account' => array(
        'className' => 'Account',
        'foreignKey' => 'account_id',
        'dependant' => true
      ),
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
      ),
      'PersonInvitee' => array(
        'className' => 'Person',
        'foreignKey' => 'invitation_person_id'
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
        'dependent' => false
      ),
      'Log' => array(
        'className' => 'Log',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      /*'MilestoneResponsible' => array(
        'className' => 'Milestone',
        'foreignKey' => 'responsible_person_id',
      ),
      'MilestoneCompleted' => array(
        'className' => 'Milestone',
        'foreignKey' => 'completed_person_id',
        'conditions' => array('MilestoneCompleted.completed' => true),
      ),*/
      'Post' => array(
        'className' => 'Post',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      'Time' => array(
        'className' => 'Time',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      'TodoItem' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),
      /*'TodoItemResponsible' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'responsible_person_id',
      ),
      'TodoItemCompleted' => array(
        'className' => 'TodoItem',
        'foreignKey' => 'completed_person_id',
        'conditions' => array('TodoItemCompleted.completed' => true),
      ),*/
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
      
      $conditions = array();
      
      if($this->id)
      {
        $conditions['Person.id !='] = $this->id;
      }
      
      $this->Company->bindModel(array(
        'belongsTo'=>array(
          'Person' => array(
            'className' => 'Person',
            'foreignKey' => false,
            'type' => 'INNER',
            'conditions' => array_merge(array(
              'Person.company_id = Company.id',
              'Person.email' => $this->data[$this->alias]['email']
            ),$conditions)
          )
        )
      ));
      
      $check = $this->Company->find('count',array(
        'conditions' => array(
          'Company.account_id'  => $accountId
        ),
        'contain' => array(
          'Person'
        )
      ));
      
      return $check ? false : true;
    }
    
    
    /**
     * Persons project permissions
     *
     * @todo Make this generic for any type of aro model and data
     * @access public
     * @return boolean
     */
    public function projectPermissions($id)
    {
      $this->Acl = ClassRegistry::init('Aro');
      $this->Aco = ClassRegistry::init('Aco');
    
      //Find Aros for Person
      $aro = $this->Aro->find('first', array(
        'conditions' => array(
          'Aro.model' => 'Person',
          'Aro.foreign_key' => $id,
        ),
        'recursive' => -1
      ));
      $aroId = $aro['Aro']['id'];
      
      //Load Acos
      $records = $this->Aco->Permission->find('all',array(
        'conditions' => array(
          'Permission.aro_id' => $aroId,
          'Permission._read' => true,
          'Aco.model' => 'Projects',
        ),
        'fields' => array('Aco.foreign_key','Permission.*')
      ));
      
      $projects = null;
      
      if(!empty($records))
      {
        $projects = ClassRegistry::init('Project')->find('all',array(
          'conditions' => array(
            'Project.id'      => Set::extract($records,'{n}.Aco.foreign_key'),
            'Project.status'  => 'active'
          ),
          'fields' => array('id','name'),
          'contain' => false
        ));
      }
      
      return $projects;
    }
    
    
    
    /**
     * Check if a user has permission
     *
     * @todo Make this generic for any type of aro model and data
     * @access public
     * @return boolean
     */
    public function hasPermission($id,$model,$projectId)
    {
      $this->Acl = ClassRegistry::init('Aro');
      $this->Aco = ClassRegistry::init('Aco');
    
      //Find Aros for Person
      $aro = $this->Aro->find('first', array(
        'conditions' => array(
          'Aro.model' => 'Person',
          'Aro.foreign_key' => $id,
        ),
        'recursive' => -1
      ));
      $aroId = $aro['Aro']['id'];
      
      //Load Acos
      return $this->Aco->Permission->find('count',array(
        'conditions' => array(
          'Permission.aro_id' => $aroId,
          'Permission._read' => true,
          'Aco.model' => $model,
          'Aco.foreign_key' => $projectId
        )
      ));
    }

  }
  
?>
