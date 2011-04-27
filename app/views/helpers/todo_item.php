<?php

  /**
   * TodoItem Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class TodoItemHelper extends AppHelper
  {
  
    public $helpers = array('Html');
    
    /**
     * Before log output
     *
     * @access public
     * @return boolean
     */
    public function beforeLog($data)
    {
      $options['badge'] = 'todo';
      $options['name'] = 'Todo';
      
      if($data['Log']['action'] == 'completed')
      {
        $options['description'] = '<span class="strike">'.$data['Log']['description'].'</span>';
      }
      elseif($data['Log']['action'] == 'assigned')
      {
        $options['action'] = __('Assigned to',true);
        
        if(empty($data['Log']['extra3']))
        {
          $options['person'] = __('Anyone',true);
        }
        else
        {
          $options['person'] = $data['Log']['extra3'];
        }
      }
      
      //Add the list name + link
      if(!empty($data['Log']['extra1']))
      {
        $listLink = $this->Html->link($data['Log']['extra1'],array('controller'=>'todos','action'=>'view',$data['Log']['extra2']),array('class'=>'unimportant'));
      
        if(isset($options['description']))
        {
          $options['description'] .= ' '.$listLink;
        }
        else
        {
          $options['description'] = $data['Log']['description'].' '.$listLink;
        }
      }
      
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
      
      $options['badge'] = 'todo';
      $options['name'] = 'Todo';
      
      $options['url'] = array(
        'accountSlug' => $data['Account']['slug'],
        'projectId'   => $data['Project']['id'],
        'controller'  => 'todos',
        'action'      => 'view',
        $data['SearchIndex']['extra1'],
      );
      
      return $options;
    }
    
  }
  
?>
