<?php

  /**
   * Commentable Behavior
   *
   * @category Behavior
   * @package  Opencamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CommentableBehavior extends ModelBehavior
  {

    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
      $model->bindModel(array(
        'hasMany' => array(
          'Comment' => array(
            'conditions'    => array('Comment.model' => $model->alias),
            'foreignKey'    => 'foreign_id',
            'dependent'     => true,
            'order'         => 'Comment.created DESC'
          ),
          'CommentPerson' => array(
            'conditions'  => array('CommentPerson.model' => $model->alias),
            'foreignKey'  => 'foreign_id',
            'dependent'   => true
          )
        )
      ),false);
    }
    
    
    /**
     * Add comment
     *
     * @access public
     * @return void
     */
    public function addComment(&$model, $data)
    {
      //Populate
      $data['Comment']['model'] = $model->alias;
      $data['Comment']['foreign_id'] = $model->id;
      $data['Comment']['person_id'] = $model->personId;
    
      $model->Comment->set($data);
      
      if($model->Comment->validates())
      {
        if($model->Comment->save())
        {
          //Add self to subscribers
          $this->addCommentPerson($model, $model->personId);
          
          //Add checked
          if(isset($data['CommentPeople']['person_id']) && !empty($data['CommentPeople']['person_id']))
          {
            foreach($data['CommentPeople']['person_id'] as $personId)
            {
              $this->addCommentPerson($model, $personId);
            }
          }
          
          //Update count
          $this->updateCommentCount($model);
          
          return true;
        }
      }
      
      return false;
    }
    
    
    /**
     * Delete comment
     *
     * @access public
     * @return void
     */
    public function deleteComment(&$model, $id)
    {
    }
    
    
    /**
     * Add a subscriber to this comment if not already
     *
     * @access public
     * @return void
     */
    public function addCommentPerson(&$model, $personId)
    {
      if(!$model->Comment->CommentPerson->find('count',array(
        'conditions' => array(
          'model'       => $model->alias,
          'foreign_id'  => $model->id,
          'person_id'   => $personId
        ),
        'recursive' => -1
      )))
      {
        //add
        $model->Comment->CommentPerson->save(array(
          'model'       => $model->alias,
          'foreign_id'  => $model->id,
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
    public function findCommentPeople(&$model, $id)
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
     * Update comment_count field
     *
     * @access public
     * @return boolean
     */
    public function updateCommentCount(&$model)
    {
      $count = $model->Comment->find('count',array(
        'conditions' => array(
          'Comment.model' => $model->alias,
          'Comment.foreign_id' => $model->id
        ),
        'recursive' => -1
      ));
      
      return $model->saveField('comment_count',$count);
    }
    
    
  }

?>
