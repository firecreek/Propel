<?php

  /**
   * Projects Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class ProjectsController extends AppController
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
    public $uses = array('Project','Company');
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_add()
    {
      if(!empty($this->data))
      {
        $this->data['Project']['account_id']  = $this->Authorization->read('Account.id');
        $this->data['Project']['person_id']   = $this->Authorization->read('Person.id');
        $this->data['Project']['company_id']  = $this->Authorization->read('Company.id');
        $this->data['People'] = array('id' => $this->Authorization->read('Person.id'));
      
        $this->Project->set($this->data);
        
        if($this->Project->validates())
        {
          if($this->Project->saveAll($this->data))
          {
            //Create ACO for this account
            $this->AclManager->create('projects',$this->Project->id);
            
            //Give this person permission for this project
            $this->User->Person->id = $this->Authorization->read('Person.id');
            $this->AclManager->allow($this->User->Person, 'projects', $this->Project->id, array('set' => 'owner'));
            
            //Give this company permission for this project
            $this->User->Company->id = $this->Authorization->read('Company.id');
            $this->AclManager->allow($this->User->Company, 'projects', $this->Project->id, array('set' => 'company'));
            
            //Create a new company?
            if(isset($this->data['Permission']['action']) && $this->data['Permission']['action'] == 'add')
            {
              if($this->data['Permission']['option'] == 'create')
              {
                //Create new company and give permission
                $this->Company->create();
                $checkCompany = $this->Company->save(array(
                  'name'        => $this->data['Permission']['company_new'],
                  'account_id'  => $this->Authorization->read('Account.id'),
                  'person_id'   => $this->Authorization->read('Person.id')
                ));
                
                if($checkCompany)
                {
                  //Add permission for this account and project
                  $this->AclManager->allow($this->Company, 'accounts', $this->Authorization->read('Account.id'), array('set' => 'company'));
                  $this->AclManager->allow($this->Company, 'projects', $this->Project->id, array('set' => 'company'));
                }
                
              }
              elseif($this->data['Permission']['option'] == 'select' && is_numeric($this->data['Permission']['company_id']))
              {
                //Add company permission that already exists
                $this->Company->id = $this->data['Permission']['company_id'];
                $this->AclManager->allow($this->Company, 'projects', $this->Project->id, array('set' => 'company'));
              }
            }
          
            //
            $this->Session->setFlash(__('Project created',true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'accounts','action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('Failed to save project',true), 'default', array('class'=>'error'));
          }
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true), 'default', array('class'=>'error'));
        }
        
      }
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      $this->loadModel('Milestone');
    
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
          'Milestone.completed'  => false,
          'Milestone.deadline >=' => date('Y-m-d'),
          'Milestone.deadline <=' => date('Y-m-d',strtotime('+14 days')),
        ),
        'contain' => array('Responsible'),
        'limit' => 100
      ));
      
      $this->set(compact('overdue','upcoming'));
    }
    
  
  }
  
  
?>
