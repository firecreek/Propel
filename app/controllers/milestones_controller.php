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
      $milestoneCount = $this->Milestone->find('count',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id')
        ),
        'recursive' => -1
      ));
      
      
      //Overdue
      $overdue = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.deadline <' => date('Y-m-d'),
          'Milestone.completed'  => false
        ),
        'contain' => array('Responsible'),
        'order' => 'Milestone.deadline ASC',
        'limit' => 10
      ));
      
      //Upcoming
      $upcoming = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.deadline >' => date('Y-m-d'),
          'Milestone.completed'  => false
        ),
        'contain' => array('Responsible'),
        'limit' => 5
      ));
      
      //Completed
      $completed = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.completed'  => true
        ),
        'order' => 'Milestone.completed_date ASC',
        'contain' => array('Responsible'),
        'limit' => 5
      ));
    
      //Nothing added yet
      if(!$milestoneCount)
      {
        return $this->render('project_index_new');
      }
      
      $this->set(compact('upcoming','completed','overdue'));
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
