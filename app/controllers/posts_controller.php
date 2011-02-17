<?php

  /**
   * Posts Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PostsController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array('Time');
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array('Cookie');
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('Post');
    
    
    /**
     * Project list posts
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      //Type of view
      $viewType = 'expanded';
      
      //Set
      if(isset($this->params['url']['view']))
      {
        $viewType = $this->params['url']['view'];
        $this->Cookie->write('Posts.viewType',$viewType);
      }
      elseif($cookieViewType = $this->Cookie->read('Posts.viewType'))
      {
        $viewType = $cookieViewType;
      }
      
      //Load records
      $records = $this->Post->find('all',array(
        'conditions' => array(
          'Post.project_id' => $this->Authorization->read('Project.id'),
          'OR' => array(
            array('Post.private' => 0),
            array(
              'AND' => array(
                'Post.private' => 1,
                'Person.company_id' => $this->Authorization->read('Company.id')
              )
            ),
          )
        ),
        'contain' => array(
          'Person',
          'CommentLast' => array(
            'order' => 'CommentLast.id DESC',
            'Person'
          ),
          'CommentUnread'
        ),
        'group' => 'Post.id',
        'order' => 'Post.id DESC'
      ));
    
      //Empty
      if(empty($records))
      {
        return $this->render('project_index_new');
      }
      
      //
      $this->set(compact('records','viewType'));
    }
    
    
    /**
     * Project add post
     *
     * @access public
     * @return void
     */
    public function project_add()
    {
      if(!empty($this->data))
      {
        //Fill in missing data
        $this->data['Post']['project_id'] = $this->Authorization->read('Project.id');
        $this->data['Post']['person_id'] = $this->Authorization->read('Person.id');

        //
        $this->Post->set($this->data);
        
        if($this->Post->validates())
        {
          $this->Post->save();
          
          //$this->Session->setFlash(__('Post added',true),'default',array('class'=>'success'));
          
          $this->redirect(array('controller'=>'comments','action'=>'index','associatedController'=>'posts',$this->Post->id));
        }
      }
      
      //Milestone list
      $this->loadModel('Milestone');
      $milestoneOptions = $this->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      $this->set(compact('milestoneOptions'));
    }
    
    
    /**
     * Project edit post
     * 
     * @access public
     * @return void
     */
    public function project_edit($id)
    {
      $this->Post->id = $id;
      
      if(!empty($this->data))
      {
        $this->data['Post']['id'] = $id;
        $this->Post->set($this->data);
        
        if($this->Post->validates())
        {
          $this->Post->save();
          
          if($this->RequestHandler->isAjax())
          {
            return $this->render();
          }
          
          $this->Session->setFlash(__('Message updated',true),'default',array('class'=>'success'));
          $this->redirect(array('controller'=>'comments','action'=>'index','associatedController'=>'posts',$id));
        }
        else
        {
          $this->Session->setFlash(__('Check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      else
      {
        $this->data = $this->Post->find('first',array(
          'conditions' => array(
            'Post.id' => $id
          ),
          'contain' => array(
          )
        ));
      }
      
      //Milestone list
      $this->loadModel('Milestone');
      $milestoneOptions = $this->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      $this->set(compact('id','milestoneOptions'));
    }
    
    
    /**
     * Project delete milestone
     * 
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      $this->Post->recursive = -1;
    
      if($this->Post->delete($id))
      {
        $this->Session->setFlash(__('Message record deleted',true),'default',array('class'=>'success'));
      }
      else
      {
        $this->Session->setFlash(__('Failed to delete Message record',true),'default',array('class'=>'error'));
      }
      
      $this->redirect(array('action'=>'index'));
    }
    
  
  }
  
  
?>
