<?php

  /**
   * Auth Helper
   *
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AuthHelper extends AppHelper
  {
    public $helpers = array('Session');
    
    public function canCreateProject()
    {
      return $this->Session->read('AuthAccount.Permissions._create');
    }
    
    public function read($key)
    {
      return $this->Session->read('AuthAccount.'.$key);
    }
    
  }
  
?>
