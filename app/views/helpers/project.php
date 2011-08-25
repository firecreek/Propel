<?php

  /**
   * Project Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class ProjectHelper extends AppHelper
  {
  
    public $helpers = array('Html');
    
    /**
     * Before log output
     *
     * @access public
     * @return boolean
     */
    public function beforeLog($log)
    {
      $options = array();
      
      switch($log['Log']['action'])
      {
        case 'create':
        case 'add':
          $options['action'] = __('Created by',true);
          break;
      }
      
      $options['description'] = $this->Html->link($log['Log']['description'],array(
        'accountSlug' => $log['Account']['slug'],
        'projectId'   => $log['Log']['model_id'],
        'controller'  => 'projects',
        'action'      => 'index'
      ));
      
      return $options;
    }
    
    
  }
  
?>
