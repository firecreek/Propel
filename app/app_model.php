<?php

  App::import('Lib', 'LazyModel');
  
  /**
   * AppHelper
   *
   * Caching by Croogo
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AppModel extends LazyModel
  {
    /**
     * Use Caching
     *
     * @access public
     * @var string
     */
    public $useCache = true;
    
    /**
     * Auth
     *
     * @access public
     * @var array
     */
    public $actsAs = array('Auth');
    
    
    /**
     * Override find function to use caching
     *
     * Caching can be done either by unique names,
     * or prefixes where a hashed value of $options array is appended to the name
     * 
     * @param mixed $type 
     * @param array $options 
     * @return mixed
     * @access public
     */
    public function find($type, $options = array())
    {
      //Build cached name
      if(
        $this->Behaviors->attached('Auth') && 
        isset($options['cache']['config']) && 
        $options['cache']['config'] == 'system'
      )
      {
        $name = array();
        $name[] = $options['cache']['name'];
        if($this->authRead('Person.id')) { $name[] = $this->authRead('Person.id'); }
        if($this->authRead('Account.id')) { $name[] = $this->authRead('Account.id'); }
        if($this->authRead('Project.id')) { $name[] = $this->authRead('Project.id'); }
        $name[] = md5(Router::url());
        
        $options['cache']['name'] = implode('_',$name);
      }
    
      //
      if($this->useCache)
      {
        $cachedResults = $this->_findCached($type, $options);
        if ($cachedResults)
        {
          return $cachedResults;
        }
      }
      
      $args = func_get_args();
      $results = call_user_func_array(array('parent', 'find'), $args);
      if ($this->useCache)
      {
        if (isset($options['cache']['name']) && isset($options['cache']['config'])) {
            $cacheName = $options['cache']['name'];
        } elseif (isset($options['cache']['prefix']) && isset($options['cache']['config'])) {
            $cacheName = $options['cache']['prefix'] . md5(serialize($options));
        }

        if (isset($cacheName)) {
            Cache::write($cacheName, $results, $options['cache']['config']);
        }
      }
  
      return $results;
    }
    
    
    /**
     * Find cached
     *
     * @access public
     * @return array
     */
    public function findCached($name,$config)
    {
      return $this->_findCached('all',array(
        'cache' => array(
          'name' => $name,
          'config' => $config
        )
      ));
    }
    
    
   /**
    * Check if find() was already cached
    *
    * @param mixed $type
    * @param array $options
    * @return void
    * @access private
    */
    function _findCached($type, $options)
    {
      if (isset($options['cache']['name']) && isset($options['cache']['config'])) {
          $cacheName = $options['cache']['name'];
      } elseif (isset($options['cache']['prefix']) && isset($options['cache']['config'])) {
          $cacheName = $options['cache']['prefix'] . md5(serialize($options));
      } else {
          return false;
      }
      
      $results = Cache::read($cacheName, $options['cache']['config']);
      if ($results) {
          return $results;
      }
      return false;
    }
    
    
    /**
     * Updates multiple model records based on a set of conditions.
     *
     * call afterSave() callback after successful update.
     *
     * @param array $fields     Set of fields and values, indexed by fields.
     *                          Fields are treated as SQL snippets, to insert literal values manually escape your data.
     * @param mixed $conditions Conditions to match, true for all records
     * @return boolean True on success, false on failure
     * @access public
     */
    public function updateAll($fields, $conditions = true)
    {
        $args = func_get_args();
        $output = call_user_func_array(array('parent', 'updateAll'), $args);
        if ($output) {
            $created = false;
            $options = array();
            //Do not enable this
            /*$this->Behaviors->trigger($this, 'afterSave', array(
                $created,
                $options,
            ));*/
            $this->afterSave($created);
            $this->_clearCache();
            return true;
        }
        return false;
    }
    
  }
  
?>
