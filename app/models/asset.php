<?php

  /**
   * Asset Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Asset extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Asset';
  
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Containable'
    );
      
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
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
        'foreignKey' => 'person_id'
      )
    );
  
  

  }

?>
