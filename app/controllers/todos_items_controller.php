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
     * @var array
     */
    public $helpers = array('Listable');
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Message');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('TodoItem','Todo');
    
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
     * Todo item index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
    }
    
    
    /**
     * Add todo item
     *
     * @param int $id Todo pk
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
          $this->data['TodoItem']['id'] = $this->TodoItem->id;
        
          //Email responsible
          $this->_sendEmails('add',$this->data);
          
          //Build log data
          $item = $this->TodoItem->find('first',array(
            'conditions' => array('TodoItem.id'=>$this->data['TodoItem']['id']),
            'contain' => array(
              'Todo' => array('id','name','private'),
              'Responsible' => array('name')
            )
          ));
          
          //Save log
          $this->TodoItem->customLog('assigned',$this->data['TodoItem']['id'],array(
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
     * Edit todo item
     *
     * @param int $id Todo pk
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
          
          //Email responsible
          $this->_sendEmails('edit',$this->data);
          
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
          $this->redirect(array('controller'=>'todos','action'=>'index'));
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
     * Email
     *
     * @param string $type Type, new or edit
     * @param array $data Form data
     * @access private
     * @return boolean
     */
    private function _sendEmails($type,$data)
    {
      //Email checked
      if(!empty($data['TodoItem']['responsible']) && $data['TodoItem']['notify'])
      {
        //Read in record
        $record = $this->TodoItem->find('first',array(
          'conditions' => array('TodoItem.id'=>$this->data['TodoItem']['id']),
          'contain' => array(
            'Todo' => array('id','name'),
            'Person' => array('id','first_name','last_name','full_name','email'),
            'Responsible',
            'Person' => array(
              'Company'
            ),
            'Project' => array(
              'Account'
            ),
          )
        ));
      
        $this->Message->send('todo_assigned',array(
          'subject' => __('To-do item assigned to you',true),
          'to'      => $record['Person']['id']
        ),$record);
      }
    }
    
    
    /**
     * Project delete todo item
     * 
     * @param int $id Todo item pk
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
     * Project update todo item completed
     * 
     * @param int $id Todo item pk
     * @param boolean $completed
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
          
          $todos[] = $todoId;
        }
      }  
      
    }
    
  }
  
  
?>
