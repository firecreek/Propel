<?php

  /**
   * Comments Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CommentsController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Listable','Textile');
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Message');
    
    /**
     * Models
     *
     * @access public
     * @var array
     */
    public $uses = array('Comment');
    
    public $authPrefix = 'project';
    
    
    /**
     * Before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {    
      //
      $this->associatedController = Inflector::pluralize(Inflector::classify($this->params['associatedController']));
      
      //Associated controller model
      $this->modelAlias = str_replace('s_','_',$this->params['associatedController']);
      $this->modelAlias = Inflector::classify($this->modelAlias);
      $this->loadModel($this->modelAlias);
      
      //ACL options for checking
      //$this->AclFilter->authController  = Inflector::pluralize(Inflector::classify($this->params['associatedController']));
      //$this->AclFilter->authAction      = 'project_'.$this->action;
      //$this->AclFilter->authModel       = $this->modelAlias;
      
      //
      $this->Comment->associatedAlias = $this->modelAlias;
      
      $this->params['prefix'] = 'project';
      
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
      $this->set('associatedController',$this->associatedController);
      $this->set('modelAlias',$this->modelAlias);
      
      parent::beforeRender();
    }
    
    
    /**
     * Comments list
     * 
     * @todo Clean this function up?
     * @param int $id Associated controller model ID
     * @access public
     * @return void
     */
    public function index($id)
    {
      //Build statement
      $contain = array();
      $conditions = array();
      
      //Responsible
      if($this->{$this->modelAlias}->Behaviors->attached('Responsible'))
      {
        $contain[] = 'Responsible';
      }
      
      //Person
      if(isset($this->{$this->modelAlias}->belongsTo['Person']))
      {
        $contain[] = 'Person';
      }
      
      //Other binds
      if(isset($this->{$this->modelAlias}->commentBinds))
      {
        $contain = array_merge($contain,$this->{$this->modelAlias}->commentBinds);
      }
      
      //Load record
      $record = $this->{$this->modelAlias}->find('first',array(
        'conditions' => array_merge(array(
          $this->modelAlias.'.id'=>$id
        ),$conditions),
        'contain' => array_merge(array(
          'Comment' => array(
            'order' => 'Comment.id ASC',
            'Person'
          ),
          'CommentPerson' => array(
            'Person' => array(
              'fields' => array('id','full_name','email','user_id','company_id'),
              'Company' => array('id','name')
            )
          )
        ),$contain)
      ));
      
      if(empty($record))
      {
        $this->cakeError('recordNotFound');
      }
      
      //Title
      $title = $record[$this->modelAlias][$this->{$this->modelAlias}->displayField];
      
      $this->Comment->searchSetting('title',array(
        'model'           => $this->modelAlias,
        'associatedKey'   => 'model_id',
        'field'           => $this->{$this->modelAlias}->displayField
      ));
      
      //Edit record
      if(isset($this->params['named']['edit']) && is_numeric($this->params['named']['edit']))
      {
        $commentId = $this->params['named']['edit'];
        
        //Load this record
        $comment = $this->Comment->find('first',array(
          'conditions' => array('Comment.id'=>$commentId),
          'contain' => false
        ));
        
        //Check valid
        if($comment['Comment']['person_id'] != $this->Authorization->read('Person.id'))
        {
          //Not owner
          $this->Session->setFlash(__('You do not have permission to edit this comment',true),'default',array('class'=>'error'));
        }
        elseif(strtotime($comment['Comment']['created']) < strtotime('-'.Configure::read('Comments.edit_expiry').' minutes'))
        {
          //Expired
          $this->Session->setFlash(__('You can no longer edit this record',true),'default',array('class'=>'error'));
        }
        else
        {
          //OK
          $this->data = $comment;
          $this->set('edit',$commentId);
        }
      }
      
      
      //Set as read for person
      $this->Comment->setRead($id,$this->Authorization->read('Person.id'));
      
      //
      $this->set('activeMenu',Inflector::underscore($this->associatedController));
      $this->set(compact('id','record'));
    }
    
    
    
    /**
     * Comment add
     *
     * @access public
     * @return void
     */
    public function add($id)
    {
      $this->data['Comment']['model'] = $this->modelAlias;
      $this->data['Comment']['model_id'] = $id;
      $this->data['Comment']['person_id'] = $this->Authorization->read('Person.id');
      $this->data['Comment']['account_id'] = $this->Authorization->read('Account.id');
      $this->data['Comment']['project_id'] = $this->Authorization->read('Project.id');
      
      $this->Comment->set($this->data);
      
      if($this->Comment->validates())
      {
        $this->Comment->save();
      
        //Comment id
        $commentId = $this->Comment->id;
        
        //Add self to subscribers
        $this->Comment->addCommentPerson($id,$this->Authorization->read('Person.id'));
        
        //Add checked
        if(isset($data['CommentPeople']['person_id']) && !empty($data['CommentPeople']['person_id']))
        {
          foreach($data['CommentPeople']['person_id'] as $personId)
          {
            $this->Comment->addCommentPerson($id,$personId);
          }
        }
        
        //Add custom log
        //@todo use displayField
        if(isset($record[$this->modelAlias]['title']))
        {
          $description = $record[$this->modelAlias]['title'];
        }
        elseif(isset($record[$this->modelAlias]['description']))
        {
          $description = $record[$this->modelAlias]['description'];
        }
        
        $this->Comment->customLog('add',$id,array(
          'description' => $description,
          'extra1'      => $this->params['associatedController'],
          'extra2'      => $commentId
        ));
        
        //Send message
        $data = array_merge($record,$this->data,array(
          'Extra' => array(
            'description' => $description,
            'id' => $id,
            'commentId' => $commentId,
            'alias' => $this->modelAlias
          ),
          'Person' => $this->Authorization->read('Person')
        ));
        
        /*$this->Message->send('comment',array(
          'subject' => 'Re: '.$description,
          'to' => 'darren.m@firecreek.co.uk'
        ),$data);*/
        
        //Update count
        $this->Comment->updateCommentCount($id);
        
        $this->redirect(array('action'=>'index',$id,'#Comment'.$commentId));
      }
      else
      {
        $this->Session->setFlash(__('Please check the fields',true),'default',array('class'=>'error'));
        $this->redirect(array('action'=>'index',$id));
      }
      
    }
    
    
    
    /**
     * Comment edit
     *
     * @access public
     * @return void
     */
    public function edit($id)
    {
      //Check
      $error = null;
    
      //Load this record
      $comment = $this->Comment->find('first',array(
        'conditions' => array('Comment.id'=>$this->data['Comment']['id']),
        'fields' => array('id','person_id','created'),
        'contain' => false
      ));
      
      //Check valid
      if($comment['Comment']['person_id'] != $this->Authorization->read('Person.id'))
      {
        //Not owner
        $error = __('You do not have permission to edit this comment',true);
      }
      elseif(strtotime($comment['Comment']['created']) < strtotime('-'.Configure::read('Comments.edit_expiry').' minutes'))
      {
        //Expired
        $error = __('You can no longer edit this record',true);
      }
    
      $this->Comment->set($this->data);
      
      if(empty($errors) && $this->Comment->validates())
      {
        $this->Comment->save();
        $this->redirect(array('action'=>'index',$id,'#Comment'.$this->data['Comment']['id']));
      }
      elseif(!empty($errors))
      {
        $this->Session->setFlash($error,'default',array('class'=>'error'));
      }
      else
      {
        $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
      }
      
      $this->redirect(array('action'=>'index',$id));
    }
    
    

    /**
     * Delete comment
     * 
     * @param int $id Associated controller model ID
     * @param int $commentId Comment to delete
     * @access public
     * @return void
     */
    public function delete($id,$commentId)
    {
      $this->Comment->id = $commentId;
    
      if($this->Comment->isOwner())
      {
        $this->Comment->recursive = -1;
        $this->Comment->delete($commentId);
        
        if($this->RequestHandler->isAjax())
        {
          $this->set(compact('commentId'));
          return $this->render();
        }
        
        $this->Session->setFlash(__('Comment deleted',true),'default',array('class'=>'success'));
      }
      else
      {
        $this->Session->setFlash(__('Failed to delete Comment record',true),'default',array('class'=>'error'));
      }
      
      $this->redirect($this->referer());
    }
    
    
    /**
     * Subscribe
     * 
     * @param int $id Associated controller model ID
     * @access public
     * @return void
     */
    public function subscribe($id)
    {
      $this->Comment->addCommentPerson($id,$this->Authorization->read('Person.id'));
    
      $this->Session->setFlash(__('You will now receive comments on this message by email',true),'default',array('class'=>'success'));
      
      $this->redirect($this->referer());
    }
    

    /**
     * Unsubscribe
     * 
     * @param int $id Associated controller model ID
     * @access public
     * @return void
     */
    public function unsubscribe($id)
    {
      $this->Comment->removeCommentPerson($id,$this->Authorization->read('Person.id'));
      
      $this->Session->setFlash(__('You will no longer receive comments on this message by email',true),'default',array('class'=>'success'));
      
      $this->redirect($this->referer());
    }
    
  
  }
  
  
?>
