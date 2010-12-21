<?php

  class Project extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var array
     */
    public $name = 'Project';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Containable'
    );
    
    
  var $validate = array(
    'account_id' => array(
      'numeric' => array(
        'rule' => array('numeric')
      ),
    ),
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty')
      ),
    ),
    'status' => array(
      'notempty' => array(
        'rule' => array('notempty')
      ),
    ),
    'person_id' => array(
      'numeric' => array(
        'rule' => array('numeric')
      ),
    ),
  );
  

  var $belongsTo = array(
    'Account' => array(
      'className' => 'Account',
      'foreignKey' => 'account_id',
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
    ),
    'Company' => array(
      'className' => 'Company',
      'foreignKey' => 'company_id'
    )
  );

  var $hasMany = array(
    'Attachment' => array(
      'className' => 'Attachment',
      'foreignKey' => 'project_id',
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
    'Category' => array(
      'className' => 'Category',
      'foreignKey' => 'project_id',
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
      'foreignKey' => 'project_id',
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
      'foreignKey' => 'project_id',
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
    'Post' => array(
      'className' => 'Post',
      'foreignKey' => 'project_id',
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
      'foreignKey' => 'project_id',
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
      'foreignKey' => 'project_id',
      'dependent' => false,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'exclusive' => '',
      'finderQuery' => '',
      'counterQuery' => ''
    )
  );

}
?>
