<?php

  /**
   * Posts Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PostsController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Time','Textile','Listable');
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Cookie','Message');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Post','Comment','Category');
    
    
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
      if(isset($this->params['named']['view']))
      {
        $viewType = $this->params['named']['view'];
        $this->Cookie->write('Posts.viewType',$viewType);
      }
      elseif($cookieViewType = $this->Cookie->read('Posts.viewType'))
      {
        $viewType = $cookieViewType;
      }
      
      //
      $conditions = array(
        'Post.project_id' => $this->Authorization->read('Project.id')
      );
      
      //Category
      $category = array();
      $categoryId = null;
      if(isset($this->params['named']['category']) && is_numeric($this->params['named']['category']))
      {
        $categoryId = $this->params['named']['category'];
      
        //@todo Move to model
        $category = $this->Post->Category->find('first',array(
          'conditions' => array(
            'id'          => $categoryId,
            'type'        => 'post',
            'account_id'  => $this->Authorization->read('Account.id'),
            'project_id'  => $this->Authorization->read('Project.id'),
          ),
          'contain' => false,
          'cache' => array(
            'config' => 'system',
            'name'   => 'category'
          )
        ));
        
        if(empty($category))
        {
          $this->cakeError('badUrl');
        }
        
        $conditions['Post.category_id'] = $this->params['named']['category'];
      }
      
      
      //Load records
      $records = $this->Post->find('all',array(
        'conditions' => $conditions,
        'contain' => array(
          'Person',
          'CommentLast',
          'CommentUnread'
        ),
        'group' => 'Post.id',
        'order' => 'Post.id DESC',
        'cache' => array(
          'name' => 'post_'.$categoryId,
          'config' => 'system'
        )
      ));
      
      
      //Most active records
      $activeRecords = array();
      if(count($records) > 2)
      {
        $activeRecords = $this->Post->find('all',array(
          'conditions' => array_merge(array(
            'Post.comment_count >=' => 2
          ),$conditions),
          'contain' => array(
            'Person',
            'CommentLast' => array(
              'order' => 'CommentLast.id DESC',
              'Person'
            ),
            'CommentUnread'
          ),
          'cache' => array(
            'name' => 'post_recent_'.$categoryId,
            'config' => 'system'
          ),
          'private' => true,
          'group' => 'Post.id',
          'order' => 'Post.comment_count DESC',
          'limit' => 2
        ));
      }
    
      //Empty
      if(empty($records) && !$categoryId)
      {
        return $this->render('project_index_new');
      }
      
      //Categories
      $categories = $this->Post->Category->findByType('post');
      
      //
      $this->set(compact('records','viewType','activeRecords','categories','category','categoryId'));
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
          $this->data['Post']['id'] = $this->Post->id;
          $this->_sendEmails('add',$this->data);
          
          $this->redirect(array('controller'=>'comments','action'=>'index','associatedController'=>'posts',$this->Post->id));
        }
      }
      
      //Milestone list
      $this->loadModel('Milestone');
      $milestoneOptions = $this->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      //Category
      $categories = $this->Category->findByType('post');
      
      $this->set(compact('milestoneOptions','categories'));
    }
    
    
    /**
     * Project edit post
     * 
     * @param int $id Post pk
     * @access public
     * @return void
     */
    public function project_edit($id)
    {
      $this->Post->id = $id;
      
      $record = $this->Post->find('first',array(
        'conditions' => array(
          'Post.id' => $id
        ),
        'contain' => array(
          'CommentPerson' => array( 
            'Person'
          ),
          'Milestone'
        )
      ));
      
      //
      if(!empty($this->data))
      {
        $this->data['Post']['id'] = $id;
        $this->Post->set($this->data);
        
        if($this->Post->validates())
        {
          $this->Post->save();
        
          if(isset($this->data['Post']['_notify_changes']) && $this->data['Post']['_notify_changes'])
          {
            $this->_sendEmails('edit',$this->data);
          }
          
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
        $this->data = $record;
      }
      
      //Categories
      $categories = $this->Post->Category->findByType('post');
      
      //Milestone list
      $this->loadModel('Milestone');
      $milestoneOptions = $this->Milestone->findProjectList($this->Authorization->read('Project.id'));
      
      $this->set(compact('id','milestoneOptions','categories','record'));
    }
    
    
    /**
     * Email
     *
     * @param string $type New or edit
     * @param array $data Form data
     * @access private
     * @return boolean
     */
    private function _sendEmails($type,$data)
    {
      //Email checked
      if(isset($data['CommentPeople']) && !empty($data['CommentPeople']))
      {
        $subscribers = array_filter($data['CommentPeople']);
        $people = array_keys($subscribers);
        
        //Read in post
        $post = $this->Post->find('first',array(
          'conditions' => array('Post.id'=>$data['Post']['id']),
          'contain' => array(
            'Person' => array(
              'Company'
            ),
            'Project' => array(
              'Account'
            ),
            'Category',
            'Milestone'
          )
        ));
        $post['Action']['type'] = $type;
      
        $this->Message->send('post',array(
          'subject' => $post['Post']['title'],
          'to'      => $people
        ),$post);
      }
    }
    
    
    /**
     * Project delete milestone
     * 
     * @param int $id Post pk
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
