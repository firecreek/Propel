<?php

  /**
   * Post Helper
   *
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PostHelper extends AppHelper
  {
    
    /**
     * Before log output
     *
     * @access public
     * @return boolean
     */
    public function beforeLog($data)
    {
    }
    
    /**
     * Before search output
     *
     * @access public
     * @return boolean
     */
    public function beforeSearch($data)
    {
      $options['name'] = 'Message';
      
      $options['url'] = array(
        'accountSlug' => $data['Account']['slug'],
        'projectId'   => $data['Project']['id'],
        'associatedController'  => 'posts',
        'controller'  => 'comments',
        'action'      => 'index',
        $data['SearchIndex']['model_id'],
      );
      
      return $options;
    }
    
  }
  
?>
