<?php

  App::import('Sanitize');

  /**
   * Sanitizer Behavior
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class SanitizerBehavior extends ModelBehavior
  {
    public $settings = array();

    
    public function setup(&$model, $config = array())
    {
      $this->settings = array_merge($this->settings,$config);
    }
    
    
    /**
     * After find
     * 
     * @access public
     * @return string
     */
    public function afterFind(&$model, $results, $primary)
    {
      if(!empty($results))
      {
        foreach($results as $key => $data)
        {
          foreach($this->settings['fields'] as $field)
          {
            if(isset($data[$model->alias][$field]))
            {
              $results[$key][$model->alias][$field] = str_replace("\n",'<br />',Sanitize::paranoid($data[$model->alias][$field],array("\n")));
            }
          }
        }
      }
      
      return $results;
    }
    

  }

?>
