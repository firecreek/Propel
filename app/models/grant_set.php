<?php

  /**
   * GrantSet Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class GrantSet extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var string
     */
    public $name = 'GrantSet';
    
    /**
     * Table to use
     *
     * @access public
     * @var string
     */
    public $useTable = 'grants_sets';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array('Containable');
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Grant'
    );
    
  }
  
?>
