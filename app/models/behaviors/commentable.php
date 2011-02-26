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
      if(isset($model->id) && is_numeric($model->id) && isset($model->personId))
      {
        $model->Comment->addCommentPerson($model->id,$model->personId);
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
