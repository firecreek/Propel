<?php

  /**
   * Todos Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
    public $components = array('Cookie');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Todo','Company');
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
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
      $this->set('dueOptions',$this->Todo->dueOptions());
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
      //Todos
      $authProjects = $this->Authorization->read('Projects');
      
      $projects = array();
      
      if(!empty($authProjects))
      {
        foreach($authProjects as $key => $project)
        {
          if($project['Project']['id'] == 27) { continue; }
          
          $todos = $this->__filterTodos(array(
            array(
              'Todo.project_id' => $project['Project']['id'],
              'OR' => array(
                'Todo.todo_items_count' => 0,
                'NOT' => array(
                  'Todo.todo_items_count = Todo.todo_items_completed_count'
                )
              )
            )
          ));
          
          if(!empty($todos))
          {
            $projects[] = array_merge($project,array('Todo'=>$todos));
          }
        }
      }
      
      $this->set(compact('projects'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      $projectId = $this->Authorization->read('Project.id');
    
      //Total todo lists
      $total = $this->Todo->find('count',array(
        'conditions' => array(
          'Todo.project_id' => $projectId
        ),
        'contain' => array('Person'),
        'cache' => array(
          'name' => 'todo_total',
          'config' => 'system',
        ),
        'recursive' => -1
      ));
    
      //Nothing added yet
      if(empty($total))
      {
        return $this->render('project_index_new');
      }
      
      //Todos
      $todos = $this->__filterTodos(array(
        array(
          'Todo.project_id' => $projectId,
          'OR' => array(
            'Todo.todo_items_count' => 0,
            'NOT' => array(
              'Todo.todo_items_count = Todo.todo_items_completed_count'
            )
          )
        )
      ));
      
      //Milestone list
      $milestoneOptions = $this->Todo->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      //Todo active/completed lists
      $todosActive = $this->Todo->findActive($this->Authorization->read('Project.id'));
      $todosCompleted = $this->Todo->findCompleted($this->Authorization->read('Project.id'));
      
      $this->set(compact('todos','todosCompleted','todosActive','milestoneOptions'));
    }
    
    
    /**
     * Project View Id
     *
     * @param int $id Todo pk
     * @access public
     * @return void
     */
    public function project_view($id)
    {
      //Todos
      $todo = $this->__filterTodos(array(
        'Todo.id' => $id
      ));
      $todo = $todo[0];
      
      //Todo active/completed lists
      $todosActive = $this->Todo->findActive($this->Authorization->read('Project.id'));
      $todosCompleted = $this->Todo->findCompleted($this->Authorization->read('Project.id'));
      
      $this->set(compact('id','todo','todosCompleted','todosActive'));
    }
    
    
    /**
     * Project list todos
     *
     * @todo Move some code to model
     * @param array $conditions
     * @access public
     * @return void
     */
    private function __filterTodos($conditions)
    {
      //Responsible filter
      if(isset($this->params['url']['responsible']))
      {
        $this->data['Todo']['responsible'] = $this->params['url']['responsible'];
        $this->Cookie->write('Todos.responsible',$this->params['url']['responsible']);
      }
      elseif($cookieResponsible = $this->Cookie->read('Todos.responsible'))
      {
        $this->data['Todo']['responsible'] = $cookieResponsible;
      }
      
      //Due filter
      if(isset($this->params['url']['due']))
      {
        $this->data['Todo']['due'] = $this->params['url']['due'];
        $this->Cookie->write('Todos.due',$this->params['url']['due']);
      }
      elseif($cookieDue = $this->Cookie->read('Todos.due'))
      {
        $this->data['Todo']['due'] = $cookieDue;
      }
      
      //Filters
      $contain = array();
      $filter = array();
      $itemConditions = array();
      
      //Responsible filter
      if(isset($this->data['Todo']['responsible']) && !empty($this->data['Todo']['responsible']))
      {
        $filter['Responsible'] = array(
          'value' => $this->data['Todo']['responsible'],
          'model' => 'TodoItem'
        );
      }
      
      //Due filter
      if(isset($this->data['Todo']['due']) && !empty($this->data['Todo']['due']))
      {
        $filter['Due'] = array(
          'value' => $this->data['Todo']['due'],
          'model' => 'TodoItem'
        );
      }
    
      //Todo lists for filter
      $todos = $this->Todo->find('all',array(
        'conditions' => $conditions,
        'fields' => array('id','name','description','private','person_id'),
        'order' => 'Todo.position ASC',
        'contain' => array(
          'Milestone' => array('id','title','deadline'),
          'Person' => array()
        ),
        'filter' => $filter,
        'items' => array(
          'conditions' => array_merge(array(
            'TodoItem.completed' => false,
          ),$itemConditions),
          'contain' => array(
            'Responsible',
            'CommentUnread'
          ),
          'recent' => array(
            'conditions' => $itemConditions,
            'limit'=>3
          ),
          'count' => true
        ),
        'cache' => array(
          'name' => 'todo_'.md5(serialize($filter)),
          'config' => 'system',
        )
      ));
      
      $this->set(compact('filter'));
      
      //
      if(isset($this->Todo->responsibleName))
      {
        $this->set('responsibleName',$this->Todo->responsibleName);
      }
      
      if(isset($this->Todo->dueName))
      {
        $this->set('dueName',$this->Todo->dueName);
      }
      
      return $todos;
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

        //Empty name
        if(empty($this->data['Todo']['name'])) { $this->data['Todo']['name'] = __('List',true); }

        //
        $this->Todo->set($this->data);
        
        if($this->Todo->validates())
        {
          $this->Todo->save();
          
          //Delete filter cookies
          if($this->Cookie->read('Todos.responsible'))
          {
            $this->Cookie->delete('Todos.responsible');
          }
          if($this->Cookie->read('Todos.due'))
          {
            $this->Cookie->delete('Todos.due');
          }
          
          //
          $this->Session->setFlash(__('Todo list added',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
      }
      
      //Milestone list
      $milestoneOptions = $this->Todo->Milestone->findProjectList($this->Authorization->read('Project.id'));

      $this->set(compact('milestoneOptions'));
    }
    
    
    /**
     * Project edit todo
     * 
     * @param int $id Todo pk
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
            $item = $this->Todo->find('first',array(
              'conditions' => array('Todo.id'=>$id),
              'contain' => array('Milestone')
            ));
          
            $this->set(compact('id','item'));
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
      
      //Milestone list
      $milestoneOptions = $this->Todo->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      $this->set(compact('id','milestoneOptions'));
    }
    
    
    /**
     * Project delete todo
     * 
     * @param int $id Todo pk
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      $this->Todo->delete($id);

      if($this->RequestHandler->isAjax())
      {
        $this->set(compact('id'));
        return $this->render();
      }
      
      $this->Session->setFlash(__('Todo deleted',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Move record to different project
     *
     * @param int $id Todo pk
     * @access public
     * @return void
     */
    public function project_move_project($id)
    {
      //Load previous record
      $this->Todo->id = $id;
      $oldProjectId = $this->Todo->field('project_id');
      
      //Check we have access to this project
      if(!$this->Authorization->check('Projects',$this->data['Todo']['project_id'],array('create')))
      {
        $this->cakeError('permissionDenied');
      }
      
      $this->Todo->TodoItem->Behaviors->disable('Commentable');
      
      $this->Todo->id = $id;
      $this->Todo->saveField('project_id',$this->data['Todo']['project_id']);
      
      $this->Todo->TodoItem->updateAll(
        array('TodoItem.project_id'=>$this->data['Todo']['project_id']),
        array('TodoItem.project_id'=>$oldProjectId)
      );
      
      $this->Todo->TodoItem->Behaviors->enable('Commentable');
      
      $this->set(compact('id'));
    }
    
    
    /**
     * Project update todo positions
     * 
     * @access public
     * @return void
     */
    public function project_update_positions()
    {
      foreach($this->params['form'] as $key => $data)
      {
        $todoId = str_replace('Todo','',$key);
        $position = $data;
        
        $this->Todo->id = $todoId;
        $check = $this->Todo->saveField('position',$position);
        
        debug($check);
      }
    }
  
  
  }
  
  
?>
