<?php

  /**
   * Token Behavior
   *
   * @category Behavior
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class TokenBehavior extends ModelBehavior
  {
    
    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $settings = array())
    {
    }
    
    
    /**
     * Set search setting
     *
     * @access public
     * @return string
     */
    public function setToken(&$model,$field)
    {
      $token = substr(Security::hash(microtime(), 'md5'),0,15);
      
      $model->saveField($field.'_token',$token);
      
      return $token;
    }
    
    

  }

?>
