<?php

  /**
   * Listable Helper
   *
   * Create standardised check box lists with javascript and ajax functionality
   * 
   * @category Helper
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class ListableHelper extends AppHelper
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Html', 'Form', 'Auth', 'Javascript');
    
    /**
     * Tags
     *
     * @access public
     * @var array
     */
    public $tags = array(
      'group'             => '
                              <div class="group %s">
                                <div class="banner"><h4>%s</h4></div>
                                <div class="content">%s</div>
                              </div>
                            ',
      'item'              => '<div id="%s" class="item %s" rel-url="%s">%s</div>',
      'checkbox'          => '<div class="check">%s</div>',
      'name'              => '<div class="name">%s%s</div>',
      'inline'            => '<div class="inline"></div>',
      'comments'          => '<div class="comment"><span class="icon">%s</span><span class="count">%s</span></div>',
      'maintain'          => '<div class="maintain">%s</div>',
      'delete'            => '<span class="delete">%s</span>',
      'edit'              => '<span class="edit important">%s</span>',
      'position'          => '<span class="position"></span>',
      'loading'           => '<span class="loading" style="display:none;"></span>',
    );
    
    
    /**
     * Display group
     *
     * @access public
     * @return string
     */
    public function group($alias,$title,$items,$options = array())
    {
      if(!isset($options['class'])) { $options['class'] = array(); }
    
      $itemsHtml = '';
      foreach($items as $item)
      {
        $itemsHtml .= $this->item($alias,$item['id'],$item['title'],$item['options']);
      }
      
      $output = sprintf($this->tags['group'],$options['class'],$title,$itemsHtml);
      
      return $output;
    }
    
    
    /**
     * Display item
     *
     * @param string $alias ACO alias
     * @param int $id Record ID
     * @param array $options
     * @access public
     * @return string
     */
    public function item($alias,$id,$name,$options = array())
    {
      $_options = array(
        'checkbox'            => true,
        'delete'              => true,
        'edit'                => true,
        'editUrl'             => false,
        'comments'            => true,
        'commentCount'        => 0,
        'position'            => false,
        'class'               => array(),
        'checked'             => false,
        'prefix'              => false,
        'ident'               => $alias.'-'.$id.'-'.rand(10000,99999)
      );
      $options = array_merge($_options,$options);
      
      //
      $aliasAco = Inflector::pluralize($alias);
      
      //Depending on Person permissions
      if(!$this->Auth->check('Project.'.$aliasAco,'update'))
      {
        $options['checkbox'] = false;
        $options['edit'] = false;
        $options['position'] = false;
      }
      
      if(!$this->Auth->check('Project.'.$aliasAco,'delete'))
      {
        $options['delete'] = false;
      }

      //Style
      if(!is_array($options['class'])) { $options['class'] = array($options['class']); }
      
      if($options['checkbox'])  { $options['class'][] = 'l-checkbox'; }
      if($options['delete'])    { $options['class'][] = 'l-delete'; }
      if($options['edit'])      { $options['class'][] = 'l-edit'; }
      if($options['position'])  { $options['class'][] = 'l-position'; }
      if($options['comments'])  { $options['class'][] = 'l-comments'; }
      
      if($options['commentCount'] > 0)  { $options['class'][] = 'l-comments-with'; }
      
      //Item
      $item = '';
      
      //Checkbox
      if($options['checkbox'])
      {
        $key = $alias.'.'.$id;
      
        $item .= sprintf($this->tags['checkbox'],$this->Form->input($key,array('type'=>'checkbox','label'=>false,'checked'=>$options['checked'])));
      }
      
      //Comments button
      $comments = '';
      if($options['comments'])
      {
        $commentLink = $this->Html->link(__('Comments',true),array('action'=>'comments',$id),array('title'=>__('Comments',true)));
        $commentCount = $this->Html->link($options['commentCount'],array('action'=>'comments',$id),array('title'=>__('Comments',true)));
        $comments = sprintf($this->tags['comments'],$commentLink,$commentCount);
      }
      
      //Name prefix
      if($options['prefix'] !== false && !empty($options['prefix']))
      {
        $name = '<strong>'.$options['prefix'].':</strong> '.$name;
      }
      
      //Name
      $item .= sprintf($this->tags['name'],$name,$comments);
      
      //Loading
      $item .= sprintf($this->tags['loading']);
      
      //Maintain
      $maintain = '';
      
      if($options['delete'])
      {
        $maintain .= sprintf($this->tags['delete'],$this->Html->link(__('Delete',true),array('action'=>'delete',$id),array('title'=>__('Delete',true))));
      }
      
      if($options['edit'])
      {
        $maintain .= sprintf($this->tags['edit'],$this->Html->link(__('Edit',true),array('action'=>'edit',$id)));
      }
      
      if($options['position'])
      {
        $maintain .= $this->tags['position'];
      }
      
      //Maintain left control
      if(!empty($maintain))
      {
        $item .= sprintf($this->tags['maintain'],$maintain);
      }
      
      //Inline
      $item .= sprintf($this->tags['inline']);
      
      //Build output
      $output = sprintf($this->tags['item'],$options['ident'],implode(' ',$options['class']),$options['editUrl'],$item);
      
      return $output;
    }
    
  }
  
?>
