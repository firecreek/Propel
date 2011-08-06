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
    public function check($key,$options = array())
    {
      //
      $_options = array(
        'prefix' => true,
        'type' => 'controller'
      );
      $options = array_merge($_options,$options);
      
      //Check controller permissions
      $check = $this->_checkController($key,$options);
      
      if($options['type'] == 'model' && $check)
      {
        $check = $this->_checkModel($key,$options);
      }
      
      return $check;
    }
    
    
    /**
     * Check controller
     *
     * @param string $key
     * @param string $options
     * @access private
     * @return boolean
     */
    private function _checkController($key,$options = array())
    {
      //
      $permissions = $this->Session->read('AuthAccount.Permissions');
    
      //
      $url = $this->Router->parse($this->url($key));
      $controller = $url['controller'];
      $model = $url['prefix'];
      $action = $url['action'];
      
      if(isset($url['prefix']) && $options['prefix']) { $action = $url['prefix'].'_'.$action; }
      
      //
      if(Set::extract($permissions,'/'.Inflector::camelize($model).'[controller='.Inflector::camelize($controller).'][action='.$action.'][read=1]'))
      {
        return true;
      }
      
      return false;
    }
    
    
    /**
     * Check model
     *
     * @param string $key
     * @param string $options
     * @access private
     * @return boolean
     */
    private function _checkModel($key,$options = array())
    {
      $modelPersonId = $options['record'][$options['model']]['person_id'];
      
      if($options['record'][$options['model']]['person_id'] == $this->read('Person.id'))
      {
        return true;
      }
      
      return false;  
    }
    
    
    /**
     * Check is prefix owner
     *
     * @param string $key
     * @param string $options
     * @access private
     * @return boolean
     */
    private function _checkIsPrefixOwner($key,$options = array())
    {
      if($this->read(Inflector::camelize($model).'.person_id') == $this->read('Person.id'))
      {
        return true;
      }
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
