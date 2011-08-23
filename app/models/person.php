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
      'Containable',
      'Cached' => array(
        'prefix' => array(
          'person',
          'people',
          'prefix',   /** Permissions */
        ),
      ),
      'Auth'
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
      /*'Time' => array(
        'className' => 'Time',
        'foreignKey' => 'person_id',
        'dependent' => false
      ),*/
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
      'PersonAccess' => array(
        'className' => 'PersonAccess',
        'foreignKey' => 'person_id',
        'dependent' => true
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
     * Before save
     *
     * @access public
     * @return boolean
     */
    public function beforeSave($q)
    {
      if(isset($this->data[$this->alias]['status']) && $this->data[$this->alias]['status'] == 'invited')
      {
        $this->data[$this->alias]['invitation_date']  = date('Y-m-d H:i:s');
        $this->data[$this->alias]['invitation_person_id'] = $this->authRead('Person.id');
        $this->data[$this->alias]['invitation_code'] = md5(time());
      }
      
      if(!isset($this->data[$this->alias]['account_id']) || empty($this->data[$this->alias]['account_id']))
      {
        $this->data[$this->alias]['account_id'] = $this->authRead('Account.id');
      }
      
      return true;
    }
    
    
    /**
     * Validation check if company name is unique to this account
     *
     * @access public
     * @return boolean
     */
    public function uniqueEmail()
    {
      if(!isset($this->data[$this->alias]['company_id']))
      {
        return true;
      }
    
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
     * Aro record
     *
     * @access public
     * @return void
     */
    public function aro(&$model,$create = false,$data)
    {
      //Grant
      $grant = $this->PersonAccess->Grant->find('first',array(
        'conditions' => array(
          'Grant.model' => $model->alias,
          'Grant.alias' => $data['alias']
        ),
        'contain' => false
      ));
    
      $data = array(
        'person_id' => $this->id,
        'model' => $model->alias,
        'model_id' => $model->id,
        'grant_id' => $grant['Grant']['id']
      );
      
      $record = $this->PersonAccess->find('first',array(
        'conditions' => $data,
        'recursive' => -1
      ));
      
      if(empty($record) && $create)
      {        
        //Create access
        $this->PersonAccess->create();
        $this->PersonAccess->save($data);
      }
      elseif(!empty($record))
      {
        $this->PersonAccess->id = $record['PersonAccess']['id'];
      }
      
      return $this->PersonAccess;
    }
    
    
    /**
     * After Delete Aro
     *
     * @access public
     * @return boolean
     */
    public function afterAroDelete($aro,$aco)
    {
      return $this->PersonAccess->deleteAll(array(
        'person_id' => $aro->id,
        'model' => $aco->alias,
        'model_id' => $aco->id
      ));
    }
    

  }
  
?>
