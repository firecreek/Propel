<?php

  /**
   * Listable Helper
   * 
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
      'item'              => '
                              <div id="%s" class="item %s"
                                rel-record-id="%s"
                                rel-edit-url="%s"
                                rel-update-url="%s"
                                rel-delete-url="%s">
                                  <div class="overview">%s</div>
                                  <div class="detail">%s</div>
                              </div>
                            ',
      'label'             => '
                              <div class="label">
                                <div class="view">
                                  <div class="name">%s</div>
                                  %s
                                  %s
                                </div>
                                <div class="inline"></div>
                              </div>
                            ',
      'checkbox'          => '<div class="check">%s</div>',
      'extra'             => '<div class="extra %s">%s</div>',
      'comments'          => '<div class="comment"><span class="icon">%s</span><span class="count">%s</span></div>',
      'delete'            => '<span class="delete">%s</span>',
      'position'          => '<span class="position"%s></span>',
      'after'             => '<div class="after">%s</div>',
      'loading'           => '<span class="loading" style="display:none;"></span>',
      'private'           => '<span class="private-pop" style="display:none;"><p>%s</p></span>',
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
        'controls' => array(
          'edit'      => false,
          'delete'    => false,
          'position'  => false
        ),
        'comments' => array(
          'enabled'     => false,
          'count'       => 0,
          'unread'      => 0,
          'controller'  => null
        ),
        'checkbox' => array(
          'enabled'     => false,
          'checked'     => false,
          'url'         => null
        ),
        'url'                 => false,
        'extra'               => false,
        'extraPrepend'        => null,
        'private'             => false,
        'class'               => array(),
        'prefix'              => false,
        'append'              => false,
        'after'               => null,
        'ident'               => $alias.$id
      );
      $options = Set::merge($_options,$options);
      
      //
      $controller = Inflector::pluralize($alias);
      
      //Can edit
      if(!$this->Auth->check(array('controller'=>$controller,'action'=>'edit')))
      {
        $options['checkbox'] = false;
        $options['edit'] = false;
        $options['position'] = false;
      }
      
      //Can delete
      if(!$this->Auth->check(array('controller'=>$controller,'action'=>'delete')))
      {
        $options['delete'] = false;
      }

      //Style
      if(!is_array($options['class'])) { $options['class'] = array($options['class']); }
      if($options['checkbox'])              { $options['class'][] = 'l-checkbox'; }
      if($options['controls']['delete'])    { $options['class'][] = 'l-delete'; }
      if($options['controls']['edit'])      { $options['class'][] = 'l-edit'; }
      if($options['controls']['position'])  { $options['class'][] = 'l-position'; }
      if($options['comments']['enabled'])   { $options['class'][] = 'l-comments'; }
      
      if($options['comments']['count'] > 0)  { $options['class'][] = 'l-comments-with'; }
      else { $options['class'][] = 'l-comments-without'; }
      
      if($options['comments']['unread'] > 0)  { $options['class'][] = 'l-comments-unread'; }
      
      //Item
      $item = '';
      
      //Checkbox
      if($options['checkbox']['enabled'])
      {
        $item .= sprintf($this->tags['checkbox'],$this->Form->input($alias.'.'.$id,array('type'=>'checkbox','label'=>false,'checked'=>$options['checkbox']['checked'])));
      }
      
      //Comments button
      $comments = '';
      if($options['comments']['enabled'])
      {
        $commentUrl = array('associatedController'=>$options['comments']['controller'],'controller'=>'comments','action'=>'index',$id);
      
        $commentLink = $this->Html->link(__('Comments',true),$commentUrl,array('title'=>__('Comments',true)));
        $commentCount = $this->Html->link($options['comments']['count'],$commentUrl,array('title'=>__('Comments',true)));
        $comments = sprintf($this->tags['comments'],$commentLink,$commentCount);
      }
      
      //Private
      if($options['private'])
      {
        $options['extraPrepend'] = sprintf($this->tags['private'],__('Visible only to your company',true));
        $options['extra'] = __('Private',true);
      }
      
      //Extra
      $extra = '';
      if(!empty($options['extra']))
      {
        $extraClass = $options['private'] ? 'private' : null;
        $extra = sprintf($this->tags['extra'],$extraClass,$options['extra']).$options['extraPrepend'];
      }
      
      //Name prefix
      if($options['prefix'] !== false && !empty($options['prefix']))
      {
        $name = '<strong>'.$options['prefix'].':</strong> '.$name;
      }
      
      //Name append
      if($options['append'] !== false && !empty($options['append']))
      {
        $name = $name.$options['append'];
      }
      
      //Link
      if($options['url'])
      {
        //Link provided
        $name = $this->Html->link($name,$options['url']);
      }
      elseif($options['controls']['edit'] !== false)
      {
        //Edit link
        $name = $this->Html->link($name,$options['controls']['edit']['url'],array('class'=>'edit-link'));
      }
      
      //Name
      $item .= sprintf($this->tags['label'],$name,$extra,$comments);
      
      //Loading
      $item .= sprintf($this->tags['loading']);
      
      //Maintain
      $maintain = '';
      
      if($options['controls']['delete'] !== false)
      {
        $maintain .= sprintf($this->tags['delete'],$this->Html->link(__('Delete',true),$options['controls']['delete']['url'],array('title'=>__('Delete',true))));
      }
      
      if($options['controls']['position'] !== false)
      {
        $positionAttrs = '';
        if(isset($options['controls']['position']['hide']) && $options['controls']['position']['hide'])
        {
          $options['class'][] = 'l-position-hide';
          $positionAttrs = ' style="display:none;"';
        }
        $maintain .= sprintf($this->tags['position'],$positionAttrs);
      }
      
      //Maintain left control
      if(!empty($maintain))
      {
        $item = $maintain.$item;
      }
      
      //After
      if(!empty($options['after']))
      {
        $options['after'] = sprintf($this->tags['after'],$options['after']);
      }
      
      //Build output
      $editUrl      = isset($options['controls']['edit']['url']) ? $options['controls']['edit']['url'] : null;
      $deleteUrl    = isset($options['controls']['delete']['url']) ? $options['controls']['delete']['url'] : null;
      $updateUrl    = isset($options['checkbox']['url']) ? $options['checkbox']['url'] : null;
      
      $output = sprintf($this->tags['item'],$options['ident'],implode(' ',$options['class']),$id,$editUrl,$updateUrl,$deleteUrl,$item,$options['after']);
      
      //
      $this->lastIdent = $options['ident'];
      
      return $output;
    }
    
  }
  
?>
