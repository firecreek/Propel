<?php

  /**
   * Auth Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AuthHelper extends AppHelper
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Session');
    
    
    /**
     * Check permission
     *
     * @param string $alias ACO alias
     * @param string $type create, update, permission type
     * @access public
     * @return boolean
     */
    public function check($alias,$type)
    {
      return $this->Session->read('AuthAccount.Permissions.'.$alias.'.'.$type);
    }
    
    
    /**
     * Read session key
     *
     * @param string $key
     * @access public
     * @return string
     */
    public function read($key)
    {
      return $this->Session->read('AuthAccount.'.$key);
    }
    
  }
  
?>
