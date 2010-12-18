<?php

  /**
   * Country Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
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
