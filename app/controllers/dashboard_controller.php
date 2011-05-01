<?php

  /**
   * Dashboard Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class DashboardController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Authorization');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array();
    
    
    /**
     * Admin dashboard
     *
     * @access public
     * @return void
     */
    public function admin_index()
    {
      
    }
  
  }
  
  
?>
