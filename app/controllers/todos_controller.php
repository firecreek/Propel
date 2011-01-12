<?php

  /**
   * Todos Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class TodosController extends AppController
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
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Todo','Company');
    
    /**
     * Action map
     *
     * @access public
     * @var array
     */
    public $actionMap = array(
      'add_item'  => '_create',
      'edit_item'  => '_update',
      'update_item'  => '_update',
      'update_positions' => '_update'
    );
    
    /**
     * Due options
     *
     * @access public
     * @var array
     */
    public $dueOptions = array();
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->dueOptions = array(
        'anytime'     => __('Anytime',true),
        '_0'          => '----------------',
        'today'       => __('Today',true),
        'tomorrow'    => __('Tomorrow',true),
        'this-week'   => __('This week',true),
        'next-week'   => __('Next week',true),
        'later'       => __('Later',true),
        '_1'          => '----------------',
        'past'        => __('In the past',true),
        'no-date'     => __('(No date set)',true),
      );
      
      parent::beforeFilter();
    }
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      $this->set('dueOptions',$this->dueOptions);
      parent::beforeRender();
    }
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      //Params
      $this->data['Todo']['responsible']  = isset($this->params['url']['responsible']) ? $this->params['url']['responsible'] : 'all';
      $this->data['Todo']['due']          = isset($this->params['url']['due']) ? $this->params['url']['due'] : 'anytime';
      
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
      //Params
      $this->data['Todo']['responsible']  = isset($this->params['url']['responsible']) ? $this->params['url']['responsible'] : 'all';
      $this->data['Todo']['due']          = isset($this->params['url']['due']) ? $this->params['url']['due'] : 'anytime';
      
      
      //Todo lists
      $todos = $this->Todo->find('all',array(
        'conditions' => array(
          'Todo.project_id' => $this->Authorization->read('Project.id')
        ),
        'fields' => array('id','name'),
        'order' => 'Todo.position ASC',
        'contain' => false,
        'items' => array(
          'conditions' => array(
            'TodoItem.completed' => false
          ),
          'contain' => array(
            'Responsible'
          ),
          'recent' => true,
          'count' => true
        )
      ));
      
      $responsible = $this->Opencamp->findResponsible();
      
      $this->set(compact('responsible','todos'));
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
        $this->data['Todo']['project_id'] = $this->Authorization->read('Project.id');
        $this->data['Todo']['person_id'] = $this->Authorization->read('Person.id');

        //
        $this->Todo->set($this->data);
        
        if($this->Todo->validates())
        {
          $this->Todo->save();
          
          $this->Todo->setFlash(__('Todo list added',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
      }
    }
    
    
    /**
     * Project edit todo
     * 
     * @access public
     * @return void
     */
    public function project_edit($id)
    {
      $this->Todo->id = $id;
      
      if(!empty($this->data))
      {
        $this->data['Todo']['id'] = $id;
        $this->Todo->set($this->data);
        
        if($this->Todo->validates())
        {
          $this->Todo->save();
          
          if($this->RequestHandler->isAjax())
          {
            $this->set(compact('id'));
            return $this->render();
          }
          
          $this->Session->setFlash(__('Todo updated',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      else
      {
        $this->data = $this->Todo->find('first',array(
          'conditions' => array(
            'Todo.id' => $id
          ),
          'contain' => false
        ));
      }
      
      $this->set(compact('id'));
    }
    
    
    /**
     * Add todo item
     *
     * @access public
     * @return void
     */
    public function project_add_item($todoId)
    {
      if(!empty($this->data))
      {
        $this->data['TodoItem']['todo_id'] = $todoId;
        $this->data['TodoItem']['project_id'] = $this->Authorization->read('Project.id');
        $this->data['TodoItem']['person_id'] = $this->Authorization->read('Person.id');
        
        $this->Todo->TodoItem->set($this->data);

        if($this->Todo->TodoItem->validates())
        {
          $this->Todo->TodoItem->save();
        }
        else
        {
          $this->Session->setFlash(__('Failed to save the record, please check the form',true),'default',array('class'=>'error'));
        }
        
        $this->redirect(array('action'=>'index'));
      }
    }
    
    
    /**
     * Edit todo item
     *
     * @access public
     * @return void
     */
    public function project_edit_item($todoId,$id)
    {
      if(!empty($this->data))
      {
        $this->data['TodoItem']['id'] = $id;
        
        $this->Todo->TodoItem->set($this->data);

        if($this->Todo->TodoItem->validates())
        {
          $this->Todo->TodoItem->save();
          
          if($this->RequestHandler->isAjax())
          {
            $this->set(compact('id'));
            return $this->render();
          }
          
          $this->Session->setFlash(__('Todo item updated',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Failed to save the record, please check the form',true),'default',array('class'=>'error'));
        }
      }
      else
      {
        $this->data = $this->Todo->TodoItem->find('first',array(
          'conditions' => array(
            'TodoItem.id' => $id
          ),
          'contain' => array(
            'Responsible'
          )
        ));
      }
      
      $this->set(compact('todoId','id'));
    }
    
    
    /**
     * Project update todo item completed
     * 
     * @access public
     * @return void
     */
    public function project_update_item($todoId,$id,$completed = false)
    {
      if($completed == 'true')
      {
        $this->Todo->TodoItem->updateAll(
          array(
            'completed' => '1',
            'completed_date' => '"'.date('Y-m-d').'"',
            'completed_person_id' => $this->Authorization->read('Person.id')
          ),
          array('TodoItem.id'=>$id)
        );
      }
      else
      {
        $this->Todo->TodoItem->updateAll(
          array('completed' => '0'),
          array('TodoItem.id'=>$id)
        );
      }
      
      $this->set(compact('id','completed'));
    }
    
    
    /**
     * Project update todo item positions
     * 
     * @access public
     * @return void
     */
    public function project_update_positions()
    {
      foreach($this->params['form'] as $key => $data)
      {
        $datasplit = explode('-',$data);
        
        $todoId = $datasplit[0];
        $position = $datasplit[1];
        
        $todoItemId = str_replace('TodoItem','',$key);
        
        
        $this->Todo->TodoItem->updateAll(
          array(
            'TodoItem.todo_id' => $todoId,
            'TodoItem.position' => $position
          ),
          array('TodoItem.id'=>$todoItemId)
        );
        
      }
      debug($this->params);
      echo 'ok';
      exit;
    }
  
  
  }
  
  
?>
