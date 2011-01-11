<?php

  /**
   * Comment Read Model
   *
   * Used to check which comments have been read by a Person
   * If record exists then comments have been read already
   * When viewing a comment a new record is added to this model with the comment id
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CommentRead extends AppModel
  {
    /**
     * Name
     *
     * @access public
     * @var string
     */
    public $name = 'CommentRead';
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array();
    
    /**
     * Use table
     *
     * @access public
     * @var string
     */
    public $useTable = 'comments_reads';
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Comment' => array(
        'className' => 'Comment',
      ),
      'Person' => array(
        'className' => 'Person',
      )
    );
    
  }

?>
