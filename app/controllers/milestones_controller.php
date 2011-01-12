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
    public $helpers = array('Listable');
    
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
      if(!empty($this->data))
      {
        foreach($this->data['Milestone'] as $id => $checked)
        {
          $this->Milestone->id = $id;
          
          //Check permissions then update
          if($this->Milestone->canUpdate())
          {
            if($checked)
            {
              $this->Milestone->complete();
            }
            else
            {
              $this->Milestone->pending();
            }
          }
        }
      }
    
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
        'contain' => array(
          'Responsible'
        ),
        'order' => 'Milestone.deadline ASC',
        'limit' => 10
      ));
      
      //Upcoming
      $upcoming = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id'  => $this->Authorization->read('Project.id'),
          'Milestone.deadline >=' => date('Y-m-d'),
          'Milestone.completed'   => false
        ),
        'contain' => array('Responsible'),
        'order' => 'Milestone.deadline ASC',
        'limit' => 5
      ));
      
      //Upcoming next 14 days
      $upcoming14Days = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.completed'  => false,
          'Milestone.deadline >=' => date('Y-m-d'),
          'Milestone.deadline <=' => date('Y-m-d',strtotime('+14 days')),
        ),
        'contain' => array('Responsible'),
        'limit' => 100
      ));
      
      //Completed
      $completed = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.completed'  => true
        ),
        'contain' => array('Responsible'),
        'order' => 'Milestone.completed_date ASC',
        'limit' => 5
      ));
    
      //Nothing added yet
      if(!$milestoneCount)
      {
        return $this->render('project_index_new');
      }
      
      $this->set(compact('upcoming','completed','overdue','upcoming14Days'));
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
    
    
    /**
     * Project edit milestone
     * 
     * @access public
     * @return void
     */
    public function project_edit($id)
    {
      $this->Milestone->id = $id;
      
      if(!empty($this->data))
      {
        $this->data['Milestone']['id'] = $id;
        $this->Milestone->set($this->data);
        
        if($this->Milestone->validates())
        {
          $this->Milestone->save();
          
          if($this->RequestHandler->isAjax())
          {
            $this->set(compact('id'));
            return $this->render();
          }
          
          $this->Session->setFlash(__('Milestone updated',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      else
      {
        $this->data = $this->Milestone->find('first',array(
          'conditions' => array(
            'Milestone.id' => $id
          ),
          'contain' => array(
            'Responsible'
          )
        ));
      }
      
      $this->set(compact('id'));
    }
    
    
    /**
     * Project update milestone completed
     * 
     * @access public
     * @return void
     */
    public function project_update($id,$completed = false)
    {
      if($completed == 'true')
      {
        $this->Milestone->updateAll(
          array(
            'completed' => '1',
            'completed_date' => '"'.date('Y-m-d').'"',
            'completed_person_id' => $this->Authorization->read('Person.id')
          ),
          array('Milestone.id'=>$id)
        );
      }
      else
      {
        $this->Milestone->updateAll(
          array('completed' => '0'),
          array('Milestone.id'=>$id)
        );
      }
      
      $this->set(compact('id','completed'));
    }
    
    
    /**
     * Project delete milestone
     * 
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      if($this->Milestone->delete($id))
      {
        $this->Session->setFlash(__('Milestone record deleted',true),'default',array('class'=>'success'));
      }
      else
      {
        $this->Session->setFlash(__('Failed to delete Milestone record',true),'default',array('class'=>'error'));
      }
      
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Project comments
     * 
     * @access public
     * @return void
     */
    public function project_comments($id)
    {
      if(!empty($this->data))
      {
        $this->Milestone->id = $id;
        
        if($this->Milestone->addComment($this->data))
        {
          $this->data = null;
        }
      }
    
      $record = $this->Milestone->find('first',array(
        'conditions' => array('Milestone.id'=>$id),
        'contain' => array(
          'Responsible' => array(),
          'Comment' => array('Person'),
          'CommentPerson' => array(
            'Person' => array(
              'fields' => array('id','full_name','email','user_id','company_id'),
              'Company' => array('id','name')
            )
          )
        )
      ));
      
      $this->set(compact('id','record'));
    }
    
  
  }
  
  
?>
