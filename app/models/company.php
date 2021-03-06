<?php

  /**
   * Company Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Company extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var string
     */
    public $name = 'Company';
    
    /**
     * Display field
     *
     * @access public
     * @var string
     */
    public $displayField = 'name';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Acl' => array('type' => 'requester'),
      'Auth',
      'Cached' => array(
        'prefix' => array(
          'companies_',
          'company_',
          'person_',
          'people_',
        ),
      ),
      'Containable'
    );
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'account_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'name' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
        'unique' => array(
          'rule' => 'uniqueName',
          'message' => 'This account already has a company with this name'
        )
      ),
      'private' => array(
        'boolean' => array(
          'rule' => array('boolean'),
        ),
      ),
      'person_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
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
      'PersonOwner' => array(
        'className' => 'Person',
        'foreignKey' => false,
        'conditions' => array(
          'PersonOwner.company_id = Company.id',
          'PersonOwner.company_owner = 1'
        ),
      ),
      'Account' => array(
        'className' => 'Account',
        'foreignKey' => 'account_id'
      ),
      'Country' => array(
        'className' => 'Country',
        'foreignKey' => 'country_id'
      ),
      'Timezone' => array(
        'className' => 'Timezone',
        'foreignKey' => 'timezone_id'
      )
    );

    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'company_id',
        'dependent' => true
      )
    );

    /**
     * Has and belongs to many
     *
     * @access public
     * @var array
     */
    public $hasAndBelongsToMany = array(
    );
    
    
    /**
     * Before Save
     *
     * @access public
     * @return boolean
     */
    public function beforeSave($q,$created = false)
    {
      if(!isset($this->data[$this->alias]['account_id']) || empty($this->data[$this->alias]['account_id']))
      {
        $this->data[$this->alias]['account_id'] = $this->authRead('Account.id');
      }
      
      if(!isset($this->data[$this->alias]['person_id']) || empty($this->data[$this->alias]['person_id']))
      {
        $this->data[$this->alias]['person_id']  = $this->authRead('Person.id');
      }
      
      return true;
    }
    
    
    /**
     * Parent Node
     *
     * @access public
     * @return void
     */
    public function parentNode()
    {
      return false;
    }
    
    
    /**
     * Validation check if company name is unique to this account
     *
     * @access public
     * @return boolean
     */
    public function uniqueName()
    {
      if(!isset($this->data[$this->alias]['account_id']) && $this->id)
      {
        $accountId = $this->field('account_id',array('id'=>$this->id));
      }
      elseif(!isset($this->data[$this->alias]['account_id']) && !$this->id)
      {
        $accountId = $this->authRead('Account.id');
      }
      else
      {
        $accountId = $this->data[$this->alias]['account_id'];
      }
    
      $conditions = array();
      if($this->id)
      {
        $conditions['id !='] = $this->id;
      }
    
      $check = $this->find('count',array(
        'conditions' => array_merge(array(
          'account_id'  => $accountId,
          'name'        => $this->data[$this->alias]['name'],
        ),$conditions),
        'recursive' => -1
      ));
      
      /*debug(array(
        'conditions' => array_merge(array(
          'account_id'  => $accountId,
          'name'        => $this->data[$this->alias]['name'],
        ),$conditions),
        'recursive' => -1
      ));
      debug($check);
      exit;*/
      
      return $check > 0 ? false : true;
    }

  }
  
?>
