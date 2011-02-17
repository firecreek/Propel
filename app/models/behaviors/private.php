<?php

  /**
   * Private Behavior
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PrivateBehavior extends ModelBehavior
  {
    public $dueOptions = array();

    
    public function setup(&$model, $config = array())
    {
    }
    
    
    /**
     * Before find
     * 
     * @access public
     * @return string
     */
    public function beforeFind(&$model, $query)
    {
    }
    

  }

?>
