<?php

  /**
   * Milestones Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class MilestonesController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('Milestone','Company');
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      $responsible = $this->Opencamp->findResponsible();
      
      $this->set(compact('responsible','records'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      $records = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id')
        )
      ));
    
      if(empty($records))
      {
        return $this->render('project_index_new');
      }
      
      $this->set(compact('records'));
    }
    
    
    /**
     * Project add
     *
     * @access public
     * @return void
     */
    public function project_add()
    {
      if(!empty($this->data))
      {
        //Fill in missing data
        if(empty($this->data['Milestone']['title'])) { $this->data['Milestone']['title'] = __('Untitled milestone',true); }
        $this->data['Milestone']['project_id'] = $this->Authorization->read('Project.id');
        $this->data['Milestone']['person_id'] = $this->Authorization->read('Person.id');

        //
        $this->Milestone->set($this->data);
        
        if($this->Milestone->validates())
        {
          $this->Milestone->save();
          
          $this->Session->setFlash(__('Milestone added',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
      }
    
    }
    
  
  }
  
  
?>
