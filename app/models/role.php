<?php

  /**
   * Role Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Role extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Role';
    
    /**
     * Validation rules
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'name' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      ),
    );
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
        'Acl' => array(
            'type' => 'requester',
        ),
    );

    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'User' => array(
        'className' => 'User',
        'foreignKey' => 'role_id',
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

    
    /**
     * Parent node
     *
     * @access public
     * @return null
     */
    public function parentNode()
    {
      return null;
    }
      
  }
  
?>
