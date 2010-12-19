<?php

  /**
   * Layout Helper
   *
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class LayoutHelper extends AppHelper
  {
    public $helpers = array('Html', 'Auth');
    
    
    /**
     * Basic menu
     *
     * @access public
     * @return void
     */
    public function menu($links,$options = array(),$menuOptions = array())
    {
      $_options = array(
        'permissions' => false
      );
      $options = array_merge($_options, $options);
        
      if(!isset($options['active']))
      {
        $options['active'] = $this->params['controller'];
      }
        
      $output = '';
      foreach($links as $key => $link)
      {
        //Check if person has access to this controller
        if($options['permissions'] && !$this->Auth->check($options['permissions'].'.'.Inflector::camelize($link['url']['controller']),'read'))
        {
          continue;
        }
      
        $tagOptions = array();
        $tagLink = $this->Html->link($link['name'], $link['url']);
        
        if($key == $options['active'])
        {
          $tagOptions['class'] = 'active';
        }
        
        $output .= $this->Html->tag('li', $tagLink, $tagOptions);
      }
      
      $output = $this->Html->tag('ul', $output, $menuOptions);
      
      return $output;
    }
    
  }
  
?>
