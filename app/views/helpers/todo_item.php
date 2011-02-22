<?php

  /**
   * TodoItem Helper
   *
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class TodoItemHelper extends AppHelper
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
    
  }
  
?>
