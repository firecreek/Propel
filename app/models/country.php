<?php

  /**
   * Country Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Country extends AppModel
  {
    /**
     * Model name
     *
     * @access public
     * @var string
     */
    public $name = 'Country';
    
    /**
     * Default order
     *
     * @access public
     * @var string
     */
    public $order = 'Country.name ASC';

  }
?>
