<?php

  /**
   * Project Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
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
      'Containable',
      'Cached' => array(
        'prefix' => array(
          'project'
        ),
      ),
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
      'Time' => array(
        'className' => 'Time',
        'foreignKey' => 'project_id',
        'dependent' => false
      ),
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'project_id',
        'dependent' => false
      )
    );

  }
  
?>
