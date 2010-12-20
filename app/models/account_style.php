<?php

  /**
   * Account Style Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AccountStyle extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'AccountStyle';
    
    /**
     * Use table
     *
     * @access public
     * @var string
     */
    public $useTable = 'accounts_styles';
    
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
     * Validation rules
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
      'Account' => array(
        'className' => 'Account'
      )
    );

  }
  
?>
