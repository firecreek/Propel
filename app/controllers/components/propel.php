<?php

  /**
   * Propel Component
   *
   * @category Component
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PropelComponent extends Object
  {
    /**
     * Controller object
     *
     * @access public
     * @var object
     */
    public $controller = null;
    
    /**
     * Components used
     *
     * @access public
     * @var object
     */
    public $components = array('Acl','Authorization','Session');
    

    /**
     * Initialize component
     *
     * @param object $controller
     * @param array $settings
     * @access public
     * @return void
     */
    public function initialize(&$controller, $settings = array())
    {
      $this->controller =& $controller;
    }
    
    
    /**
     * Startup
     *
     * @access public
     * @return void
     */
    public function startup()
    {
      $this->_settings();
        
      if(isset($this->controller->params['prefix']))
      {
        $this->_layoutScheme();
        $this->_helpers();
      }
    }
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      if($this->Authorization->read('Project.id'))
      {
        $this->controller->set('title_for_layout',$this->Authorization->read('Project.name').' - '.Inflector::humanize($this->controller->viewPath));
      }
      elseif($this->Authorization->read('Account.id'))
      {
        $this->controller->set('title_for_layout',$this->Authorization->read('Account.name').': '.Inflector::humanize($this->controller->viewPath));
      }
    }
    

    /**
     * Settings
     *
     * @access private
     * @return void
     */
    private function _settings()
    {
      $settings = $this->controller->Setting->find('list',array(
        'fields' => array('key','value')
      ));
      
      foreach($settings as $key => $val)
      {
        Configure::write($key,$val);
      }
    }
    

    /**
     * Load helpers
     *
     * @todo Clean this up when using plugins, automatically find and load
     * @access public
     * @return void
     */
    public function _helpers()
    {
      $this->controller->helpers[] = 'TodoItem';
      $this->controller->helpers[] = 'Comment';
      $this->controller->helpers[] = 'Post';
      $this->controller->helpers[] = 'Project';
    }
    
    
    /**
     * Layout scheme colours
     *
     * @access private
     * @return boolean
     */
    private function _layoutScheme()
    {
      $style = $this->controller->Account->Scheme->SchemeStyle->find('list',array(
        'conditions' => array('scheme_id' => $this->Authorization->read('Account.scheme_id')),
        'fields'  => array('SchemeStyle.key','SchemeStyle.value'),
        'recursive' => -1,
        /*'cache' => array(
          'name' => 'scheme',
          'config' => 'system',
        )*/
      ));
      
      return $this->Session->write('Style',$style);
    }
    
    
    
    
  }
  
?>
