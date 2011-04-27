<?php

  /**
   * Scheme Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Scheme extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Scheme';
    
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
          'scheme',
        ),
      )
    );
    
    /**
     * Validation rules
     *
     * @access public
     * @var array
     */
    public $validate = array(
    );
    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'SchemeStyle' => array(
        'className' => 'SchemeStyle',
        'dependent' => true
      )
    );

  }
  
?>
