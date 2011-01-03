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
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Html', 'Auth');
    
    
    /**
     * Button
     *
     * @param string $text
     * @param mixed $url
     * @param string $type
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
     * @param array $links
     * @param array $options
     * @param array $menuOptions
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
     * @param array $people List of people
     * @param array $options
     * @access public
     * @return array
     */
    public function permissionList($people,$options = array())
    {
      $space = 0;
      
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
        $list[] = array('name'=>__('Anyone',true),'value'=>'anyone');
      }
      
      //Add self
      if($options['self'])
      {
        $list[] = array('name'=>__('Me',true) . ' ('.$this->Auth->read('Person.full_name').')','value'=>'self');
      }
      
      //Build list
      foreach($companies as $record)
      {
        $list[] = array('name'=>'---------------------------','disabled'=>true,'value'=>($space++));
        $list[] = array('name'=>strtoupper($record['Company']['name']),'value'=>'company_'.$record['Company']['id']);
          
        foreach($record['People'] as $person)
        {
          $list[] = array('name'=>$person['Person']['full_name'],'value'=>'person_'.$person['Person']['id']);
        }
      }
    
      return $list;
    }
    
    
    /**
     * Generate list of people for notifications
     *
     * @param array $people List of people
     * @param array $options
     * @access public
     * @return array
     */
    public function notificationList($people,$options = array())
    {
      $_options = array(
      );
      $options = array_merge($_options,$options);
      
      foreach($people as $person)
      {
        if($this->Auth->read('Person.id') != $person['Person']['id'])
        {
          $list[$person['Person']['id']] = '<strong>'.$person['Company']['name'].':</strong> '.$person['Person']['full_name'];
        }
      }
    
      return $list;
    }
    
  }
  
?>
