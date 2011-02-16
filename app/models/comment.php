<?php

  /**
   * Comment Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Comment extends AppModel
  {
    /**
     * Name
     *
     * @access public
     * @var string
     */
    public $name = 'Comment';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array('Auth');
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'body' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          'required' => true,
        ),
      )
    );
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Person' => array(
        'className' => 'Person',
        'fields' => array('id','full_name','email','user_id'),
      )
    );
    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'CommentPerson' => array(
        'className'   => 'CommentPerson',
        'foreignKey'  => false,
        'conditions'  => array(
          'CommentPerson.model = Comment.model',
          'CommentPerson.foreign_id = Comment.foreign_id'
        )
      ),
      'CommentRead' => array(
        'className'   => 'CommentRead',
        'foreignKey'  => false,
        'conditions'  => array(
          'CommentRead.model = Comment.model',
          'CommentRead.foreign_id = Comment.foreign_id'
        )
      )
    );
    

    /**
     * Add a subscriber to this comment if not already
     *
     * @access public
     * @return void
     */
    public function addCommentPerson($personId)
    {
      if(!$this->CommentPerson->find('count',array(
        'conditions' => array(
          'model'       => $this->associatedAlias,
          'foreign_id'  => $this->id,
          'person_id'   => $personId
        ),
        'recursive' => -1
      )))
      {
        //add
        $this->CommentPerson->save(array(
          'model'       => $this->associatedAlias,
          'foreign_id'  => $this->id,
          'person_id'   => $personId
        ));
      }    
    }
    
    
    /**
     * Find people who are subscribed
     *
     * @access public
     * @return void
     */
    public function findCommentPeople($id)
    {
      return $model->Comment->CommentPerson->find('count',array(
        'conditions' => array(
          'model'       => $model->alias,
          'foreign_id'  => $id
        ),
        'recursive' => -1
      ));
    }
    
    
    /**
     * Set as read
     *
     * @access public
     * @return boolean
     */
    public function setRead($id,$personId)
    {
      //Find out how many
      $count = $this->find('count',array(
        'conditions' => array(
          'model' => $this->associatedAlias,
          'foreign_id' => $id
        ),
        'recursive' => -1
      ));
      
      //Find last update
      $record = $this->CommentRead->find('first',array(
        'conditions' => array(
          'model' => $this->associatedAlias,
          'foreign_id' => $id,
          'person_id'  => $personId
        ),
        'recursive' => -1
      ));
      
      $check = false;
      
      if(!empty($record))
      {
        //Update
        $this->CommentRead->id = $record['CommentRead']['id'];
        $check = $this->CommentRead->saveField('last_count',$count);
      }
      else
      {
        //Add
        $this->CommentRead->create();
        $check = $this->CommentRead->save(array(
          'model'       => $this->associatedAlias,
          'foreign_id'  => $id,
          'person_id'   => $personId,
          'last_count'  => $count
        ));
      }
      
      return $check;
    }
    
    
    /**
     * Update comment_count field
     *
     * @access public
     * @return boolean
     */
    public function updateCommentCount($id)
    {
      $count = $this->find('count',array(
        'conditions' => array(
          'Comment.model' => $this->associatedAlias,
          'Comment.foreign_id' => $id
        ),
        'recursive' => -1
      ));
      
      $model = ClassRegistry::init($this->associatedAlias);
      $model->recursive = -1;
      $model->id = $id;
      
      return $model->saveField('comment_count',$count);
    }
    
    
    
  }

?>
