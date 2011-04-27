<?php

  /**
   * Comment Read Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
