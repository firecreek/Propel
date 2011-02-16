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
    
  }

?>
