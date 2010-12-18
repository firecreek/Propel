<?php

  /**
   * People Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PeopleController extends AppController
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
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array();
    
    /**
     * Permissions required
     *
     * @access public
     * @access public
     */
    public $permissions = array(
      'account_add' => 'create'
    );
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_add()
    {
    }
  
  }
  
  
?>
