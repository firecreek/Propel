<?php

  /**
   * Milestones Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class MilestonesController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Listable');
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Cookie');
    
    /**
     * Uses
     *
     * @access public
     * @var array
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
      //Responsible filter
      if(isset($this->params['url']['responsible']))
      {
        $this->data['Milestone']['responsible'] = $this->params['url']['responsible'];
        $this->Cookie->write('Milestone.responsible',$this->params['url']['responsible']);
      }
      elseif($cookieResponsible = $this->Cookie->read('Milestone.responsible'))
      {
        $this->data['Milestone']['responsible'] = $cookieResponsible;
      }
      
      //Projects
      $projects = $this->Authorization->read('Projects');
      $projects = Set::extract($projects,'{n}.Project.id');
      
      
      //Responsible filter
      $filter = array();
      if(isset($this->data['Milestone']['responsible']) && !empty($this->data['Milestone']['responsible']))
      {
        $filter['Responsible'] = array(
          'value' => $this->data['Milestone']['responsible']
        );
      }
      
      
      //All upcoming
      $records = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id'  => $projects,
          'Milestone.deadline >=' => date('Y-m-d',mktime(0,0,0,date('n'),1,date('Y'))),
          'Milestone.completed'   => false
        ),
        'contain' => array(
          'Project' => array('id','account_id','name','status'),
          'Account' => array('id','name','slug'),
          'Responsible'
        ),
        'filter' => $filter
      ));
      
      //Overdue
      $overdue = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $projects,
          'Milestone.deadline <' => date('Y-m-d'),
          'Milestone.completed'  => false
        ),
        'order' => 'Milestone.deadline ASC',
        'contain' => array(
          'Project' => array('id','account_id','name','status'),
          'Account' => array('id','name','slug'),
          'Responsible'
        ),
        'filter' => $filter
      ));
      
      
      if(isset($this->Milestone->responsibleName))
      {
        $this->set('responsibleName',$this->Milestone->responsibleName);
      }
      
      
      $this->set(compact('records','overdue'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {  
      //@todo Check if this should still be here, maybe old code
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
    
      //Total count
      $milestoneCount = $this->Milestone->find('count',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id')
        ),
        'recursive' => -1,
        'cache' => array(
          'name' => 'milestone_total',
          'config' => 'system',
        )
      ));
    
      //Nothing added yet
      if(!$milestoneCount)
      {
        return $this->render('project_index_new');
      }

      
      //Standard contains for each
      //@todo Figure out how to hide private messages in Post
      //       - Containable won't do it, nor will Joins, maybe Linkable
      $contain = array(
        'Project' => array('id','account_id','name','status'),
        'Account' => array('id','name','slug'),
        'CommentUnread',
        'Responsible',
        'Todo',
        'Post'
      );
      
      //Overdue
      $overdue = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.deadline <' => date('Y-m-d'),
          'Milestone.completed'  => false
        ),
        'contain' => $contain,
        'order' => 'Milestone.deadline ASC',
        'cache' => array(
          'name' => 'milestone_overdue',
          'config' => 'system',
        )
      ));
      
      //Upcoming
      $upcoming = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id'  => $this->Authorization->read('Project.id'),
          'Milestone.deadline >=' => date('Y-m-d'),
          'Milestone.completed'   => false
        ),
        'contain' => $contain,
        'order' => 'Milestone.deadline ASC',
        'cache' => array(
          'name' => 'milestone_upcoming',
          'config' => 'system',
        )
      ));
      
      //Upcoming next 14 days
      $upcoming14Days = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.completed'  => false,
          'Milestone.deadline >=' => date('Y-m-d'),
          'Milestone.deadline <=' => date('Y-m-d',strtotime('+14 days')),
        ),
        'contain' => $contain,
        'cache' => array(
          'name' => 'milestone_upcoming14',
          'config' => 'system',
        )
      ));
      
      //Completed
      $completed = $this->Milestone->find('all',array(
        'conditions' => array(
          'Milestone.project_id' => $this->Authorization->read('Project.id'),
          'Milestone.completed'  => true
        ),
        'contain' => $contain,
        'order' => 'Milestone.completed_date ASC',
        'cache' => array(
          'name' => 'milestone_completed_'.$this->Authorization->read('Project.id'),
          'config' => 'system',
        )
      ));
      
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
        $this->data['Milestone']['account_id'] = $this->Authorization->read('Account.id');
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
     * Project add multiple
     *
     * @access public
     * @return void
     */
    public function project_add_multiple()
    {
      if(!empty($this->data))
      {
        //
        $save = array();
      
        //Build array
        foreach($this->data['Milestone'] as $key => $data)
        {
          if(!empty($data['title']))
          {
            if($data['deadline'] == __('Pick a date',true))
            {
              $data['deadline'] = date('Y-m-d');
            }
            else
            {
              $data['deadline'] = date('Y-m-d',strtotime($data['deadline']));
            }
          
            $save[] = array(
              'Milestone' => array_merge(array(
                  'account_id' => $this->Authorization->read('Account.id'),
                  'project_id' => $this->Authorization->read('Project.id'),
                  'person_id'  => $this->Authorization->read('Person.id')
               ),$data)
            );
          }
        }
        
        //Save
        if(!empty($save))
        {
          if($this->Milestone->saveAll($save))
          {
            $this->Session->setFlash(sprintf(__('Added %s milestones',true),count($save)),'default',array('class'=>'success'));
            $this->redirect(array('action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('There was an error, please check the fields',true),'default',array('class'=>'error'));
          }
        }
        else
        {
          $this->Session->setFlash(__('No milestone data to save',true),'default',array('class'=>'error'));
        }

      }
    }
    
    
    /**
     * Project edit milestone
     * 
     * @param int $id Milestone id
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
     * @param int $id Milestone id
     * @param boolean $completed Completed state
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
      
      $record = $this->Milestone->find('first',array(
        'conditions' => array('Milestone.id'=>$id),
        'contain' => array(
          'Responsible'
        )
      ));
      
      $this->set(compact('id','completed','record'));
    }
    
    
    /**
     * Move milestone to different project
     *
     * @param int $id Milestone pk
     * @access public
     * @return void
     */
    public function project_move_project($id)
    {
      //Check we have access to this project
      if(!$this->Authorization->check('Projects',$this->data['Milestone']['project_id'],array('create')))
      {
        $this->cakeError('permissionDenied');
      }
      
      $this->Milestone->id = $id;
      $this->Milestone->saveField('project_id',$this->data['Milestone']['project_id']);
    }
    
    
    /**
     * Project delete milestone
     * 
     * @param int $id Milestone pk
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      $this->Milestone->delete($id);
      
      if($this->RequestHandler->isAjax())
      {
        $this->set(compact('id'));
        return $this->render();
      }
      
      $this->Session->setFlash(__('Milestone deleted',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'index'));
    }
    
  
  }
  
  
?>
