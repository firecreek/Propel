<?php

  /**
   * Post Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PostHelper extends AppHelper
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Html');
    
    /**
     * Before log output
     *
     * @access public
     * @return boolean
     */
    public function beforeLog($data)
    {
      $options['description'] = $this->Html->link($data['Log']['description'],array(
        'associatedController' => 'posts',
        'controller' => 'comments',
        'action' => 'index',
        $data['Log']['model_id']
      ));
      
      return $options;
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
