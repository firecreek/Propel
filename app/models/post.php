<?php

  /**
   * Post Model
   *
   * @category Model
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class Post extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Post';
    
    /**
     * Display field
     *
     * @access public
     * @var string
     */
    public $displayField = 'title';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Containable',
      'Auth',
      'Private',
      'Commentable',
      'Loggable' => array(
        'titleField' => 'title'
      ),
      'Searchable' => array(
        'title' => 'title',
        'description' => 'body'
      ),
      'Cached' => array(
        'prefix' => array(
          'post'
        ),
      )
    );
    
    /**
     * Validation
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'project_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'person_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'milestone_complete' => array(
        'boolean' => array(
          'rule' => array('boolean'),
        ),
      ),
      'comments_count' => array(
        'numeric' => array(
          'rule' => array('numeric'),
        ),
      ),
      'private' => array(
        'boolean' => array(
          'rule' => array('boolean'),
        ),
      ),
      'title' => array(
        'notempty' => array(
          'rule' => array('notempty'),
        ),
      ),
    );
    

    /**
     * Belongs to
     *
     * @access public
     * @var array
     */
    public $belongsTo = array(
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'project_id',
        'counterCache' => true,
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id',
        'type' => 'INNER'
      ),
      'Category' => array(
        'className' => 'Category',
        'foreignKey' => 'category_id',
      ),
      'Milestone' => array(
        'className' => 'Milestone',
        'foreignKey' => 'milestone_id',
      )
    );

    
    /**
     * Has and belongs to many
     *
     * @access public
     * @var array
     */
    public $hasAndBelongsToMany = array(
    );

    
    /**
     * Comment binds
     *
     * @access public
     * @var array
     */
    public $commentBinds = array(
      'Category',
      'Milestone'
    );

  }
  
?>
