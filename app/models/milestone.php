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
      'Responsible',
      'Containable',
      'Completable',
      'Commentable',
      'Auth',
      'Loggable' => array(
        'titleField' => 'title'
      ),
      'Searchable' => array(
        'title' => 'title',
        'description' => false
      ),
      'Cached' => array(
        'prefix' => array(
          'milestone'
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
      'Account' => array(
        'className' => 'Account',
        'foreignKey' => 'account_id'
      ),
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
    
    
    
    /**
     * Get project list
     *
     * @access public
     * @return array
     */
    public function findProjectList($projectId)
    {
      $output = array();
    
      $records = $this->find('all',array(
        'conditions' => array($this->alias.'.project_id'=>$projectId),
        'order' => $this->alias.'.title ASC',
        'recursive' => -1,
        'fields' => array('id','title','completed','deadline')
      ));
      
      foreach($records as $record)
      {
        $prefix = null;
        
        if($record[$this->alias]['completed'])
        { 
          $prefix = __('COMPLETED',true).' - ';
        }
      
        $output[$record[$this->alias]['id']] = $prefix.$record[$this->alias]['title'];
      }
      
      return $output;
    }

  }
  
?>
