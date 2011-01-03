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
      )
    );
    
  }

?>
