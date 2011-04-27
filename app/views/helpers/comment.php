<?php

  /**
   * Comment Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CommentHelper extends AppHelper
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
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
    
    
    /**
     * Before search output
     *
     * @access public
     * @return boolean
     */
    public function beforeSearch($data)
    {
      $options = array();
      
      $options['url'] = array(
        'accountSlug' => $data['Account']['slug'],
        'projectId'   => $data['Project']['id'],
        'associatedController' => Inflector::tableize($data['SearchIndex']['extra1']),
        'controller' => 'comments',
        'action' => 'index',
        $data['SearchIndex']['extra2'],
        '#Comment'.$data['SearchIndex']['model_id']
      );
      
      $options['date'] = __('Posted by',true).' '.$data['Person']['full_name'].', '.date('j M Y',strtotime($data['SearchIndex']['model_created']));
      
      return $options;
    }
    
  }
  
?>
