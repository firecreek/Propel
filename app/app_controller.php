<?php

  /**
   * AppController
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AppController extends Controller
  {
    /**
     * Components uses
     *
     * @access public
     * @var array
     */
    public $components = array('Acl','Authorization','AclFilter','Session','DebugKit.Toolbar');
    
    public $uses = array('User','Account');
    
    
    /**
     * Before Filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      //Auth settings
      $this->AclFilter->auth();
      
      parent::beforeFilter();
    }
  
  }


?>
