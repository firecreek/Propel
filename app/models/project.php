<?php

  /**
   * Project Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
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
      'Acl' => array('type' => 'controlled'),
      'Auth',
      'Containable',
      'Cached' => array(
        'prefix' => array(
          'project'
        ),
      ),
      'Loggable' => array(
        'titleField' => 'name'
      )
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
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Account' => array(
        'className' => 'Account',
        'foreignKey' => 'account_id'
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id'
      ),
      'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'company_id'
      )
    );
    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Attachment' => array(
        'className' => 'Attachment',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      'Category' => array(
        'className' => 'Category',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      'Log' => array(
        'className' => 'Log',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      'Post' => array(
        'className' => 'Post',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      /*'Time' => array(
        'className' => 'Time',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),*/
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'project_id',
        'dependent' => false
      )
    );
    
    
    
    /**
     * Before Save
     *
     * @access public
     * @return string
     */
    public function beforeSave($q)
    {
      if(!isset($this->data[$this->alias]['account_id']))
      {
        $this->data[$this->alias]['account_id'] = $this->authRead('Account.id');
      }
      
      if(!isset($this->data[$this->alias]['person_id']))
      {
        $this->data[$this->alias]['person_id'] = $this->authRead('Person.id');
      }
      
      if(!isset($this->data[$this->alias]['company_id']))
      {
        $this->data[$this->alias]['company_id'] = $this->authRead('Company.id');
      }
      
      if(!isset($this->data['People']))
      {
        $this->data['People'] = array('id' => $this->authRead('Person.id'));
      }
    
      return true;
    }
    
    
    
    /**
     * After Save
     *
     * @access public
     * @return string
     */
    public function afterSave($created)
    {
      //Created record
      if($created)
      {
        //Default categories
        $this->Category->createDefaults($this->authRead('Account.id'),$this->id);
      }
    }
    
    
    
    /**
     * Parent ACO node
     *
     * @access public
     * @return string
     */
    public function parentNode()
    {
      return 'models';
    }

  }
  
?>
