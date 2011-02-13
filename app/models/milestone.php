<?php

  /**
   * Milestone Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Milestone extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Milestone';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Responsible',
      'Containable',
      'Completable',
      'Commentable',
      'Auth'
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
          'rule' => array('numeric')
        ),
      ),
      'title' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          'required' => true
        ),
      ),
      'comments_count' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'completed' => array(
        'boolean' => array(
          'rule' => array('boolean')
        ),
      ),
      'person_id' => array(
        'numeric' => array(
          'rule' => array('numeric')
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
        'counterCache' => true
      ),
      'CompletedPerson' => array(
        'className' => 'Person',
        'foreignKey' => 'completed_person_id'
      ),
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id'
      )
    );
    
    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Post' => array(
        'className' => 'Post',
        'foreignKey' => 'milestone_id',
        'dependent' => false
      ),
      'Todo' => array(
        'className' => 'Todo',
        'foreignKey' => 'milestone_id',
        'dependent' => false
      )
    );
    
    
    

  }
  
?>
