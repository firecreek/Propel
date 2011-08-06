<?php

  /**
   * Grant Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Grant extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Grant';
    
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
      'alias' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      ),
      'model' => array(
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
      'PersonAccess' => array(
        'className' => 'PersonAccess',
        'foreignKey' => 'grant_id',
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
