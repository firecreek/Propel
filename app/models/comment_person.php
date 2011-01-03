<?php

  /**
   * Comment Person
   *
   * People subscribed to the main comment thread
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CommentPerson extends AppModel
  {
    /**
     * Name
     *
     * @access public
     * @var string
     */
    public $name = 'CommentPerson';
    
    /**
     * Use table
     *
     * @access public
     * @var string
     */
    public $useTable = 'comments_people';
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array();
    
    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Person' => array(
      )
    );
    
  }

?>
