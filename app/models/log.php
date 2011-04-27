<?php

  /**
   * Log Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Log extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Log';
    
    /**
     * Display field
     *
     * @access public
     * @var string
     */
    public $displayField = 'description';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Auth',
      'Containable',
      'Private'
    );
    
    /**
     * Name of model
     *
     * @access public
     * @var array
     */
    public $validate = array();

    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'project_id'
      ),
      'Account' => array(
        'className' => 'Account',
        'foreignKey' => 'account_id'
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id'
      )
    );
  }
  
?>
