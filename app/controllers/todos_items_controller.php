<?php

  /**
   * Todos Items Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class TodosItemsController extends AppController
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
    public $uses = array('TodoItem','Todo');
    
    /**
     * Action map
     *
     * @access public
     * @var array
     */
    public $actionMap = array(
      'update_positions'      => '_update'
    );
    
    /**
     * Before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      //When auth checking we want to check the Todo model
      if(isset($this->params['pass'][0]) && is_numeric($this->params['pass'][0]))
      {
        $record = $this->TodoItem->find('first',array(
          'conditions' => array('TodoItem.id'=>$this->params['pass'][0]),
          'recursive'  => -1,
          'fields'     => 'todo_id'
        ));
        
        $this->todoId = $record['TodoItem']['todo_id'];
        
        $this->AclFilter->controllerName = 'Todos'; 
        $this->AclFilter->modelId = $this->todoId;
      }
    
      parent::beforeFilter();
    }
    
    
    /**
     * Before render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      if(isset($this->todoId))
      {
        $this->set('todoId',$this->todoId);
      }
    
      parent::beforeRender();
    }
    
    

    /**
     * Add todo item
     *
     * @access public
     * @return void
     */
    public function project_add($id)
    {
      if(!empty($this->data))
      {
        $this->TodoItem->disableLog();
      
        $this->data['TodoItem']['todo_id'] = $id;
        $this->data['TodoItem']['project_id'] = $this->Authorization->read('Project.id');
        $this->data['TodoItem']['person_id'] = $this->Authorization->read('Person.id');
        
        $this->TodoItem->set($this->data);

        if($this->TodoItem->validates())
        {
          $this->TodoItem->save();
          
          $todoItemId = $this->TodoItem->id;
        
          $item = $this->TodoItem->find('first',array(
            'conditions' => array('TodoItem.id'=>$todoItemId),
            'contain' => array('Todo','Responsible')
          ));
          
          //Save log
          $this->TodoItem->customLog('assigned',$todoItemId,array(
            'extra1'  => $item['Todo']['name'],
            'extra2'  => $item['Todo']['id'],
            'extra3'  => isset($item['Responsible']['name']) ? $item['Responsible']['name'] : null,
            'private' => $item['Todo']['private']
          ));
          
          //
          if($this->RequestHandler->isAjax())
          {          
            $this->set(compact('id','item'));
            return $this->render();
          }
          
          $this->Session->setFlash(__('Todo item added',true),'default',array('class'=>'success'));
          $this->redirect(array('controller'=>'todos','action'=>'index'));
        }
        else
        {
          //Error validating todo item
          if($this->RequestHandler->isAjax())
          {
            $this->set(compact('id'));
            return $this->render('project_add_error');
          }
          else
          {
            $this->Session->setFlash(__('Failed to save the record, please check the form',true),'default',array('class'=>'error'));
          }
        }
      }
      
      $record = $this->TodoItem->Todo->find('first',array(
        'conditions' => array(
          'Todo.id' => $id
        ),
        'contain' => false
      ));
      
      $this->set(compact('id','record'));
    }
    
    
    /**
     * Project delete todo item
     * 
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      $this->Todo->TodoItem->delete($id);

      if($this->RequestHandler->isAjax())
      {
        //Total count
        $this->Todo->id = $this->todoId;
        $this->Todo->recursive = -1;
        $item = $this->Todo->read('todo_items_completed_count');
      
        $this->set(compact('id','item'));
        return $this->render();
      }
      
      $this->Session->setFlash(__('Todo item deleted',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Edit todo item
     *
     * @access public
     * @return void
     */
    public function project_edit($id)
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
            $item = $this->Todo->TodoItem->find('first',array(
              'conditions' => array('TodoItem.id'=>$id),
              'contain' => array('Todo','Responsible')
            ));
          
            $this->set(compact('id','item'));
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
        $this->data = $this->TodoItem->find('first',array(
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
    public function project_update($id,$completed = false)
    {    
      //Check current status
      $this->TodoItem->id;
      
      //if((int)$this->TodoItem->field('completed') !== ($completed == 'false' ? 0 : 1))
      //{
        //Make update
        $this->TodoItem->disableLog();
      
        if($completed == 'true')
        {
          $this->TodoItem->updateAll(
            array(
              'completed' => '1',
              'completed_date' => '"'.date('Y-m-d').'"',
              'completed_person_id' => $this->Authorization->read('Person.id')
            ),
            array('TodoItem.id'=>$id)
          );
          
          //@todo Read record once
          $record = $this->TodoItem->find('first',array(
            'conditions' => array('TodoItem.id'=>$id),
            'contain' => array(
              'Todo' => array('Person'),
              'Responsible'
            )
          ));
          
          $this->TodoItem->customLog('completed',$id,array(
            'extra1' => $record['Todo']['name'],
            'extra2' => $record['Todo']['id'],
            'extra3' => isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null
          ));
        }
        else
        {
          $this->TodoItem->updateAll(
            array('completed' => '0'),
            array('TodoItem.id'=>$id)
          );
        }
      //}
      
      //Load item back in
      $record = $this->TodoItem->find('first',array(
        'conditions' => array('TodoItem.id'=>$id),
        'contain' => array(
          'Todo' => array(
            'Person'
          )
        )
      ));
    
      $this->set(compact('id','record','completed'));
    }
    
    
    /**
     * Project update todo item positions
     * 
     * @access public
     * @return void
     */
    public function project_update_positions()
    {
      if(!empty($this->params['form']))
      {
        //Todo
        $todos = array();
      
        //
        foreach($this->params['form'] as $key => $data)
        {
          $datasplit = explode('-',$data);
          
          $todoId = $datasplit[0];
          $position = $datasplit[1];
          
          $todoItemId = str_replace('TodoItem','',$key);
          
          $save = array(
            'id' => $todoItemId,
            'todo_id' => $todoId,
            'position' => $position
          );
          $this->TodoItem->save($save,false);
          
          /*$this->Todo->TodoItem->updateAll(
            array(
              'TodoItem.todo_id' => $todoId,
              'TodoItem.position' => $position
            ),
            array('TodoItem.id'=>$todoItemId)
          );*/
          
          $todos[] = $todoId;
        }
        
        //Update all todo lists counts
        /*$todos = array_unique($todos);
        
        $this->Todo->updateCounterCache();
        
        debug($todos);
        exit;*/
      }  
      
    }
    
  }
  
  
?>
