<?php

  /**
   * Timezone Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Timezone extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Timezone';
    
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
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'timezone_id',
        'dependent' => false
      ),
      'User' => array(
        'className' => 'User',
        'foreignKey' => 'timezone_id',
        'dependent' => false
      )
    );

  }
  
?>
