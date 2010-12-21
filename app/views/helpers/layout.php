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
     * button
     *
     * @access public
     * @return void
     */
    public function button($text,$url,$type = '')
    {
      return $this->Html->link('<span>'.$text.'</span>',$url,array('class'=>'button action '.$type,'escape'=>false));
    }
    
    
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
        
        if(!isset($link['options'])) { $link['options'] = array(); }
        if($link['url'] == false) { $link['url'] = '#'; $link['options'] = array('onclick'=>'return false;'); }
      
        $tagLink = $this->Html->link($link['name'], $link['url'], $link['options']);
        
        $classes = array();
        $tagOptions = array();
        
        if($key == $options['active'])
        {
          $classes[] = 'active';
        }
        
        if(isset($link['class']))
        {
          $classes[] = $link['class'];
        }
        
        $tagOptions['class'] = implode(' ',$classes);
        
        $output .= $this->Html->tag('li', $tagLink, $tagOptions);
      }
      
      $output = $this->Html->tag('ul', $output, $menuOptions);
      
      return $output;
    }
    
  }
  
?>
