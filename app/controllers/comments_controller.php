<?php

  /**
   * Comments Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CommentsController extends AppController
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
    public $uses = array('Comment');
    
    
    /**
     * Before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->associatedController = Inflector::pluralize(Inflector::classify($this->params['associatedController']));
      
      //Model
      //@todo Create inflector for model names
      $modelAlias = $this->params['associatedController'];
      $modelAlias = str_replace('s_','_',$modelAlias);
      $this->modelAlias = Inflector::classify($modelAlias);
      $this->loadModel($this->modelAlias);
      
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
     * @access public
     * @return void
     */
    public function index($id)
    {
      //Add comment
      if(!empty($this->data))
      {
        $this->data['Comment']['model'] = $this->modelAlias;
        $this->data['Comment']['foreign_id'] = $id;
        $this->data['Comment']['person_id'] = $this->Authorization->read('Person.id');
        
        $this->Comment->set($this->data);
        
        if($this->Comment->validates())
        {
          if($this->Comment->save())
          {
            //Add self to subscribers
            $this->Comment->addCommentPerson($this->Authorization->read('Person.id'));
            
            //Add checked
            /*if(isset($data['CommentPeople']['person_id']) && !empty($data['CommentPeople']['person_id']))
            {
              foreach($data['CommentPeople']['person_id'] as $personId)
              {
                $this->addCommentPerson($model, $personId);
              }
            }*/
            
            //Update count
            $this->Comment->updateCommentCount($id);
            
            $this->data = null;
          }
        }
        
      }
      
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
      
      //Private
      if($this->{$this->modelAlias}->hasField('private'))
      {
        $conditions[] = array(
          'OR' => array(
            array('Post.private' => 0),
            array(
              'AND' => array(
                $this->modelAlias.'.private' => 1,
                'Person.company_id' => $this->Authorization->read('Company.id')
              )
            ),
          )
        );
      }
      
      //Load record
      $record = $this->{$this->modelAlias}->find('first',array(
        'conditions' => array_merge(array(
          $this->modelAlias.'.id'=>$id
        ),$conditions),
        'contain' => array_merge(array(
          'Comment' => array('Person'),
          'CommentPerson' => array(
            'Person' => array(
              'fields' => array('id','full_name','email','user_id','company_id'),
              'Company' => array('id','name')
            )
          )
        ),$contain)
      ));
      
      //No record found
      if(empty($record))
      {
        $this->cakeError('error404');
      }
      
      //Set as read for person
      $this->Comment->setRead($id,$this->Authorization->read('Person.id'));
      
      //
      $this->set('activeMenu',Inflector::underscore($this->associatedController));
      $this->set(compact('id','record'));
    }
    
    

    /**
     * Delete comment
     * 
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
        $this->Session->setFlash(__('Comment deleted',true),'default',array('class'=>'success'));
      }
      else
      {
        $this->Session->setFlash(__('Failed to delete Comment record',true),'default',array('class'=>'error'));
      }
      
      $this->redirect($this->referer());
    }
    
  
  }
  
  
?>
