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
    
    
    /**
     * Generate list of people and companies for select
     *
     * @access public
     * @return array
     */
    public function permissionList($people,$options = array())
    {
      $space = 0;
      $spacer = '---------------------------';
      
      $_options = array(
        'anyone'  => true,
        'self'    => true
      );
      $options = array_merge($_options,$options);
      
      //Map data
      $companies = array();
      foreach($people as $person)
      {
        if(!isset($companies[$person['Company']['name']]))
        { 
          $companies[$person['Company']['name']] = array('Company'=>$person['Company'],'People'=>array());
        }
        $companies[$person['Company']['name']]['People'][] = $person;
      }
      
      //Build list
      $list = array();
      
      //Add anyone
      if($options['anyone'])
      {
        $list['anyone'] = __('Anyone',true);
      }
      
      //Add self
      if($options['self'])
      {
        $list['self'] = __('Me',true) . ' ('.$this->Auth->read('Person.full_name').')';
      }
      
      //Build list
      foreach($companies as $record)
      {
        $list['_'.($space++)] = $spacer;
        $list['company_'.$record['Company']['id']] = strtoupper($record['Company']['name']);
          
        foreach($record['People'] as $person)
        {
          $list['person_'.$person['Person']['id']] = '  '.$person['Person']['full_name'];
        }
      }
    
      return $list;
    }
    
  }
  
?>
