<?php

  /**
   * Project Handler Component
   *
   * @category Component
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class ProjectHandlerComponent extends Object
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
    public $components = array('Acl','Authorization');
    
    /**
     * Project People
     *
     * @access public
     * @var array
     */
    public $projectPeople = array();
    

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
      if(isset($this->controller->params['prefix']) && $this->controller->params['prefix'] == 'project')
      {
        $this->projectPeople = $this->people($this->controller->params['projectId']);
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
      $this->controller->set('projectPeople',$this->projectPeople);
    }
    
    
    
    
    /**
     * List of people in this project
     *
     * @access public
     * @return array
     */
    public function people($projectId)
    {
      //Load people who are in this project
      $node = $this->Acl->Aco->node('opencamp/projects/'.$projectId);

      $allowed = $this->controller->Acl->Aco->Permission->find('all', array(
        'conditions' => array(
          'Aro.model' => 'Person',
          'Permission.aco_id' => $node[0]['Aco']['id'],
          'Permission._read' => true
        ),
        'fields' => array('Aro.foreign_key')
      ));
      
      $ids = Set::extract($allowed,'{n}.Aro.foreign_key');
      $records = array();
      
      if(!empty($ids))
      {
        $records = $this->controller->Person->find('all',array(
          'conditions' => array(
            'Person.id' => $ids
          ),
          'fields' => array('id','full_name','first_name','last_name','email','title'),
          'contain' => array(
            'Company' => array('id','name'),
            'User' => array('id')
          )
        ));
      }
      
      return $records;
    }
    
    
    
  }
  
?>
