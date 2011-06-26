<?php

  /**
   * Person Access Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PersonAccess extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var string
     */
    public $name = 'PersonAccess';
    
    /**
     * Use table
     *
     * @access public
     * @var string
     */
    public $useTable = 'people_access';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Acl' => array('type' => 'requester'),
      'Containable'
    );
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id',
        'dependant' => true
      )
    );
    
    
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
    

  }
  
?>
