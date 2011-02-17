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
      'item'              => '
                              <div id="%s" class="item %s"
                                rel-record-id="%s"
                                rel-edit-url="%s"
                                rel-update-url="%s"
                                rel-delete-url="%s">
                                  <div class="overview">%s</div>
                                  <div class="detail">%s</div>
                                  %s
                              </div>
                            ',
      'label'             => '
                              <div class="label">
                                <div class="name">%s</div>
                                %s
                                %s
                              </div>
                            ',
      'checkbox'          => '<div class="check">%s</div>',
      'extra'             => '<div class="extra">%s</div>',
      'inline'            => '<div class="inline"></div>',
      'comments'          => '<div class="comment"><span class="icon">%s</span><span class="count">%s</span></div>',
      'maintain'          => '<div class="maintain">%s</div>',
      'delete'            => '<span class="delete">%s</span>',
      'edit'              => '<span class="edit important">%s</span>',
      'position'          => '<span class="position"%s></span>',
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
        'url'                 => false,
        'extra'               => false,
        'checkbox'            => true,
        'delete'              => true,
        'edit'                => true,
        'editUrl'             => false,
        'updateUrl'           => false,
        'deleteUrl'           => false,
        'comments'            => true,
        'commentCount'        => 0,
        'commentUnread'       => 0,
        'commentController'   => $this->params['controller'],
        'position'            => false,
        'positionHide'        => false,
        'class'               => array(),
        'checked'             => false,
        'prefix'              => false,
        'append'              => false,
        'after'               => null,
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
      else { $options['class'][] = 'l-comments-without'; }
      
      if($options['commentUnread'] > 0)  { $options['class'][] = 'l-comments-unread'; }
      
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
        $commentUrl = array('associatedController'=>$options['commentController'],'controller'=>'comments','action'=>'index',$id);
      
        $commentLink = $this->Html->link(__('Comments',true),$commentUrl,array('title'=>__('Comments',true)));
        $commentCount = $this->Html->link($options['commentCount'],$commentUrl,array('title'=>__('Comments',true)));
        $comments = sprintf($this->tags['comments'],$commentLink,$commentCount);
      }
      
      //Extra
      //@todo Clean this up and standardise
      $extra = '';
      if(!empty($options['extra']))
      {
        $extra = sprintf($this->tags['extra'],$options['extra']);
      }
      
      //Name prefix
      if($options['prefix'] !== false && !empty($options['prefix']))
      {
        $name = '<strong>'.$options['prefix'].':</strong> '.$name;
      }
      
      //Name
      if(!empty($options['url']))
      {
        $item .= sprintf($this->tags['label'],$this->Html->link($name,$options['url']),$extra,$comments);
      }
      else
      {
        $item .= sprintf($this->tags['label'],$name,$extra,$comments);
      }
      
      //Loading
      $item .= sprintf($this->tags['loading']);
      
      //Maintain
      $maintain = '';
      
      if($options['delete'])
      {
        if(!isset($options['deleteUrl'])) { $options['deleteUrl'] = array('action'=>'delete',$id); }
        $maintain .= sprintf($this->tags['delete'],$this->Html->link(__('Delete',true),$options['deleteUrl'],array('title'=>__('Delete',true))));
      }
      
      if($options['edit'])
      {
        $maintain .= sprintf($this->tags['edit'],$this->Html->link(__('Edit',true),$options['editUrl']));
      }
      
      if($options['position'])
      {
        $positionAttrs = '';
        if($options['positionHide']) { $positionAttrs = ' style="display:none;"'; }
        $maintain .= sprintf($this->tags['position'],$positionAttrs);
      }
      
      //Maintain left control
      if(!empty($maintain))
      {
        $item .= sprintf($this->tags['maintain'],$maintain);
      }
      
      //Inline
      $inline = sprintf($this->tags['inline']);
      
      //Build output
      $output = sprintf($this->tags['item'],$options['ident'],implode(' ',$options['class']),$id,$options['editUrl'],$options['updateUrl'],$options['deleteUrl'],$item,$inline,$options['after']);
      
      //
      $this->lastIdent = $options['ident'];
      
      return $output;
    }
    
  }
  
?>
