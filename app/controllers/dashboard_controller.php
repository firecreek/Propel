<?php

  /**
   * Dashboard Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class DashboardController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array('Authorization');
    
    /**
     * Uses
     *
     * @access public
     * @access public
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
