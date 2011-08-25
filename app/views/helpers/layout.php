<?php

  /**
   * Layout Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class LayoutHelper extends AppHelper
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Html', 'Auth','Image');
    
    /**
     * Constructor
     *
     * @param array $options options
     * @access public
     */
    public function __construct($options = array()) {
        $this->View =& ClassRegistry::getObject('view');
        return parent::__construct($options);
    }
    
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
     * Status
     *
     * instead of 0/1, show tick/cross
     *
     * @param integer $value 0 or 1
     * @return string formatted img tag
     */
    public function status($value) {
        if ($value == 1) {
            $output = $this->Html->image('/img/icons/tick.png');
        } else {
            $output = $this->Html->image('/img/icons/cross.png');
        }
        return $output;
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
        'permissions' => false,
        'active'      => null
      );
      $options = array_merge($_options, $options);
      
      //Active menu
      if(!$options['active'])
      {
        if(isset($this->View->viewVars['active']))
        {
          $options['active'] = $this->View->viewVars['active'];
        }
        else
        {
          $options['active'] = $this->params['controller'];
          if(isset($this->params['prefix']) && !empty($this->params['prefix']))
          {
            $options['active'] = Inflector::camelize($this->params['prefix']).'.'.$options['active'];
          }
        }
      }
        
      $output = '';
      $count = 0;
      
      foreach($links as $key => $link)
      {
        $count++;
      
        //Check if person has access to this controller
        if($options['permissions'] && !$this->Auth->check($link['url']))
        {
          continue;
        }
        
        if(!isset($link['options'])) { $link['options'] = array(); }
        if($link['url'] == false) { $link['url'] = '#'; $link['options'] = array('onclick'=>'return false;'); }
      
        $tagLink = $this->Html->link($link['name'], $link['url'], $link['options']);
        
        $classes = array();
        $tagOptions = array();
        
        if($count == 1)
        {
          $classes[] = 'first';
        }
        
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
     * Persons avatar
     *
     * @access public
     * @return void
     */
    public function avatar($person,$size = 'large')
    {
      if($size == 'large')
      {
        $size = 55;
      }
      elseif($size == 'small')
      {
        $size = 32;
      }
      
      if(is_array($person))
      {
        $userId = $person['user_id'];
      }
      elseif(is_numeric($person))
      {
        $userId = $person;
      }
    
      if(isset($userId) && !empty($userId))
      {
        $dir = ASSETS_DIR.DS.'users'.DS.$userId.DS.'avatar';
        
        if(file_exists($dir.DS.'avatar.png'))
        {
          return $this->Image->resize($dir.DS.'avatar.png', $size, $size, true);
        }
      }
      
      return $this->Html->image('avatar-'.$size.'.png');
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
        'nobody'  => true,
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
        $list[] = array('name'=>__('Anyone',true),'value'=>'');
      }
      
      //Add nobody
      if($options['nobody'])
      {
        $list[] = array('name'=>__('Nobody',true),'value'=>'nobody');
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
      
      $list = array();
      
      foreach($people as $person)
      {
        if($this->Auth->read('Person.id') != $person['Person']['id'])
        {
          $list[$person['Person']['id']] = '<strong>'.$person['Company']['name'].':</strong> '.$person['Person']['full_name'];
        }
      }
    
      return $list;
    }
    
    
    /**
     * Project list
     *
     * @param array $people List of people
     * @param array $options
     * @access public
     * @return array
     */
    public function projectList($projects,$options = array())
    {
      $_options = array(
        'ignoreActive' => true
      );
      $options = array_merge($_options,$options);
      
      $list = array();
  
      foreach($projects as $project)
      {
        if(
          ($options['ignoreActive'] == false) ||
          ($options['ignoreActive'] == true && $project['Project']['id'] != $this->Auth->read('Project.id'))
        )
        {
          $list[$project['Project']['id']] = $project['Project']['name'];
        }
      }
    
      return $list;
    }
    
    
    /**
     * Category list
     *
     * @param array $people List of people
     * @param array $options
     * @access public
     * @return array
     */
    public function categoryList($list,$options = array())
    {
      $_options = array(
        'add' => true
      );
      $options = array_merge($_options,$options);
      
      if($options['add'])
      {
        $list['_add'] = '- '.__('add new category',true).' -';
      }
    
      return $list;
    }
    
  }
  
?>
