<?php

  /**
   * Commentable Behavior
   *
   * @category Behavior
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CommentableBehavior extends ModelBehavior
  {
    /**
     * Settings
     */
    public $settings = array(
    );
  

    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
      $this->settings = $config;
    
      $model->bindModel(array(
        'hasMany' => array(
          'Comment' => array(
            'conditions'    => array('Comment.model' => $model->alias),
            'foreignKey'    => 'model_id',
            'dependent'     => true,
            'order'         => 'Comment.created DESC'
          ),
          'CommentPerson' => array(
            'conditions'  => array('CommentPerson.model' => $model->alias),
            'foreignKey'  => 'model_id',
            'dependent'   => true
          )
        ),
        'belongsTo' => array(
          'CommentUnread' => array(
            'className'   => 'CommentRead',
            'conditions'  => array(
              'CommentUnread.model_id = '.$model->alias.'.id',
              'CommentUnread.model'       => $model->alias
            ),
            'foreignKey'  => false,
            'dependent'   => true
          ),
          'CommentLast' => array(
            'className'   => 'Comment',
            'conditions'  => array(
              'CommentLast.id =
                (
                  SELECT MAX(id)
                    FROM comments as CommentLastJoin
                    WHERE
                      CommentLastJoin.model_id = '.$model->alias.'.id AND
                      CommentLastJoin.model = "'.$model->alias.'"
                )
              '
            ),
            'foreignKey'  => false
          )
        ),
      ),false);
      
      //@todo Fix this, it doesn't work because there is only one instance of this behavior, not one for each model
      //      So the associatedAlias gets set only for the first model that uses this behavior.
      $model->Comment->associatedAlias = $model->alias;
    }
    
    
    /**
     * After find
     *
     * @access public
     * @return array
     */
    public function afterFind(&$model,$results, $primary)
    {
      //Total unread messages
      if(!empty($results) && isset($results[0]['CommentUnread']))
      {
        foreach($results as $key => $record)
        {
          if(isset($record['CommentUnread']))
          {
            $unread = 0;
            
            if($record['CommentUnread']['last_count'] != $record[$model->alias]['comment_count'])
            {
              $unread = $record[$model->alias]['comment_count'] - $record['CommentUnread']['last_count'];
            }
          
            $results[$key][$model->alias]['comment_unread'] = $unread;
          }
        }
      }
      
      
      //CommentLast person name
      //@todo A containable join within CommentLast breaks, can this be fixed?
      if(!empty($results) && isset($results[0]['CommentLast']))
      {
        foreach($results as $key => $record)
        {
          if(empty($record['CommentLast']['person_id'])) { continue; }
        
          $lastPerson = $model->CommentLast->Person->find('first',array(
            'conditions' => array('Person.id'=>$record['CommentLast']['person_id']),
            'fields' => array('id','full_name','email','user_id'),
            'recursive' => -1,
            'cache' => array(
              'name' => 'person_last_'.$record['CommentLast']['person_id'],
              'config' => 'system'
            )
          ));
          
          $results[$key]['CommentLast']['Person'] = $lastPerson['Person'];
        }
      }      
      
      return $results;
    }
    
    
    /**
     * After save
     *
     * @access public
     * @return array
     */
    public function afterSave(&$model)
    {
      //Subscribe this person automatically to the record
      if(isset($model->id) && $model->authRead('Person.id'))
      {
        $model->Comment->associatedAlias = $model->alias;
        $model->Comment->addCommentPerson($model->id,$model->authRead('Person.id'));
      }
    }
    
    
    /**
     * Comment settings return
     *
     * @access public
     * @return array
     */
    public function commentSettings(&$model)
    {
      return $this->settings;
    }
    
  }

?>
