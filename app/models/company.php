<?php

  /**
   * Company Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
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
          'rule' => array('notempty'),
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
     * Validation check if company name is unique to this account
     *
     * @access public
     * @return boolean
     */
    public function uniqueName()
    {
      if(!isset($this->data[$this->alias]['account_id']) && $this->id)
      {
        $this->data[$this->alias]['account_id'] = $this->field('account_id',array('id'=>$this->id));
      }
      else
      {
        return true;
      }
    
      $conditions = array();
      if($this->id)
      {
        $conditions['id !='] = $this->id;
      }
    
      $check = $this->find('count',array(
        'conditions' => array_merge(array(
          'account_id'  => $this->data[$this->alias]['account_id'],
          'name'        => $this->data[$this->alias]['name'],
        ),$conditions),
        'recursive' => -1
      ));
      
      return $check > 0 ? false : true;
    }
    
    
    /**
     *
     */
    public function parentNode()
    {
    }
    
    
    
    /**
     * Check if a user has permission
     *
     * @todo Move to behavior
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
          'Aro.model' => 'Company',
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
