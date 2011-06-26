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
    
    
    
    public function __construct()
    {
      parent::__construct();
      
      $this->Router =& Router::getInstance();
    }
    
    /**
     * Check permission
     *
     * @param string $alias ACO alias
     * @param string $type create, update, permission type
     * @access public
     * @return boolean
     */
    public function check($model,$controller = null,$action = null)
    {
      $isAllowed = false;
      $permissions = $this->Session->read('AuthAccount.Permissions');
      
      if(is_array($model))
      {
        $url = $this->Router->parse($this->url($model));
        
        $controller = $url['controller'];
        $model = $url['prefix'];
        $action = $url['action'];
        
        if(isset($url['prefix'])) { $action = $url['prefix'].'_'.$action; }
      }
      else
      {
      }
      
      $isAllowed = Set::extract($permissions,'/'.Inflector::camelize($model).'[controller='.Inflector::camelize($controller).'][action='.$action.'][read=1]');
      if(!empty($isAllowed)) { $isAllowed = true; }
      
      return $isAllowed;
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
