<?php

  /**
   * Opencamp Component
   *
   * @category Component
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class OpencampComponent extends Object
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
      if(isset($this->controller->params['prefix']))
      {
        $this->_layoutScheme();
        $this->_helpers();
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
    }
    

    /**
     * Initialize component
     *
     * @todo Is this used anymore?
     * @access public
     * @return void
     */
    public function findResponsible()
    {
      $key = $this->controller->data['Todo']['responsible'];
      
      $responsible = array('name'=>__('Anyone',true));
      
      if($key == 'self')
      {
        $model = 'Person';
        $id = $this->Authorization->read('Person.id');
        $nameField = 'full_name';
      }
      elseif(substr($key,0,7) == 'person_')
      {
        $model = 'Person';
        $id = substr($key,7);
        $nameField = 'full_name';
      }
      elseif(substr($key,0,8) == 'company_')
      {
        $model = 'Company';
        $id = substr($key,8);
        $nameField = 'name';
      }
      
      //Load responsible person/company model data
      if(isset($model) && isset($id))
      {
        $record = $this->controller->{$model}->find('first',array(
          'conditions' => array('id' => $id),
          'contain' => false
        ));
        
        $responsible = array(
          'id'    => $record[$model]['id'],
          'name'  => $record[$model][$nameField],
          'model' => $model,
          'id'    => $id
        );
      }
      
      return $responsible;
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
        'recursive' => -1
      ));
      
      return $this->Session->write('Style',$style);
    }
    
    
    
    
  }
  
?>
