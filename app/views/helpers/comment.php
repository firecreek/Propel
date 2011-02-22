<?php

  /**
   * Comment Helper
   *
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CommentHelper extends AppHelper
  {
    public $helpers = array('Html');
    
    /**
     * Check permission
     *
     * @param string $alias ACO alias
     * @param string $type create, update, permission type
     * @access public
     * @return boolean
     */
    public function beforeLog($data)
    {
      $options['description'] = $this->Html->link('Re: '.$data['Log']['description'],array(
        'associatedController' => $data['Log']['extra1'],
        'controller' => 'comments',
        'action' => 'index',
        $data['Log']['model_id'],
        '#Comment'.$data['Log']['extra2']
      ));
      
      return $options;
    }
    
  }
  
?>
