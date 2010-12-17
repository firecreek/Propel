<?php
class User extends AppModel {
  var $name = 'User';
  
    public $actsAs = array(
      'Acl' => array('type' => 'requester'),
      'Containable'
    );
    
  var $validate = array(
    'role_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'username' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'allowEmpty' => false
      ),
      'alphanumeric' => array(
        'rule' => 'alphanumeric',
        'message' => 'Username may only consist of letter and numbers'
      ),
      'length' => array(
        'rule' => array('between', 3, 20),
        'message' => 'Username must be between 3 and 20 characters in length'
      ),
      /*'isUnique' => array(
        'rule' => 'isUnique',
        'message' => 'This username has already been taken.'
      )*/
    ),
    'password' => array(
      'notempty' => array(
        'rule' => array('notempty')
      ),
      'passwordsMatch' => array(
        'rule' => array('passwordsMatch', 'password_confirm'),
        'message' => 'Your password and confirmation passwords must match'
      )
    ),
    'email' => array(
      'email' => array(
        'rule' => array('email'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'deleted' => array(
      'boolean' => array(
        'rule' => array('boolean'),
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
    'Company' => array(
      'className' => 'Company',
      'foreignKey' => 'company_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Role' => array(
      'className' => 'Role',
      'foreignKey' => 'role_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Timezone' => array(
      'className' => 'Timezone',
      'foreignKey' => 'timezone_id',
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
    'Account' => array(
      'className' => 'Account',
      'foreignKey' => 'account_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );

  var $hasMany = array(
    'Apicall' => array(
      'className' => 'Apicall',
      'foreignKey' => 'user_id',
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
  
  

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (!isset($data['User']['role_id']) || !$data['User']['role_id']) {
            return null;
        } else {
            return array('Role' => array('id' => $data['User']['role_id']));
        }
    }
    
    
    /**
     * After save
     *
     * @param boolean $created
     * @access public
     * @return boolean
     */
    public function afterSave($created) {
      if(!$created)
      {
        $parent = $this->parentNode();
        $parent = $this->node($parent);
        $node = $this->node();
        $aro = $node[0];
        $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
        $this->Aro->save($aro);
      }
      else
      {
        //@todo Make a behavior to populate missing advanced associations

          
          //debug($this->Account->id);
      //debug($this->data);
      
      }
      
    }
    
    
    /**
     * Passwords match
     *
     * Function to check that a users password and confirmation passwords are the same
     *
     * @access public
     * @return boolean
     */
    public function passwordsMatch($data, $field)
    {
      $dataToMatch = Security::hash($this->data[$this->name][$field], null, true);
      return array_pop($data) == $dataToMatch;
    }

}
?>
