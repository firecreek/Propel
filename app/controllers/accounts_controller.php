<?php

  /**
   * Accounts Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AccountsController extends AppController
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
     * Register new user, account and person
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      //Give this person permission for this account
      
      //$this->Person->id = 44;
      //$this->AclManager->allow($this->Person, 'accounts', 40, array('set' => 'owner'));
      //exit
      
      //$this->Person->id = 45;
      //$this->AclManager->allow($this->Person, 'accounts', 40, array('set' => 'shared'));
      //exit;
    }
  
  }
  
  
?>
