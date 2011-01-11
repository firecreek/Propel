<?php

  /**
   * Account Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Account extends AppModel
  {
    /**
     * Name of model
     *
     * @access public
     * @var string
     */
    public $name = 'Account';
    
    /**
     * Behaviors
     *
     * @access public
     * @var array
     */
    public $actsAs = array(
      'Containable',
      'Cached' => array(
        'prefix' => array(
          'account_',
          'project_'
        ),
      ),
    );
    
    /**
     * Validation rules
     *
     * @access public
     * @var array
     */
    public $validate = array(
      'slug' => array(
        'notempty' => array(
          'rule' => array('notempty')
        ),
      ),
      'project_count' => array(
        'numeric' => array(
          'rule' => array('numeric')
        ),
      ),
      'user_id' => array(
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
      'CompanyOwner' => array(
        'className' => 'Company',
        'foreignKey' => false,
        'type' => 'INNER',
        'conditions' => array(
          'CompanyOwner.account_id = Account.id',
          'CompanyOwner.account_owner = 1'
        ),
      ),
      'UserOwner' => array(
        'className' => 'User',
        'foreignKey' => false,
        'conditions' => array(
          'UserOwner.account_id = Account.id'
        ),
      ),
      'Scheme' => array(
        'className' => 'Scheme'
      )
    );

    /**
     * Has many
     *
     * @access public
     * @var array
     */
    public $hasMany = array(
      'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'account_id',
        'dependent' => false
      ),
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'account_id',
        'dependent' => false
      )
    );
    
    
    /**
     * Create slug for account
     *
     * @param string $slug Slug
     * @access public
     * @return string
     */
    public function makeSlug($slug)
    {
      $slug = Inflector::slug($slug);
      $slug = str_replace('_','-',$slug);
      $slug = strtolower($slug);
      
      //Find records with same slug
      $conditions = array();
      $conditions[$this->alias.'.slug LIKE'] = $slug.'%';
      
      if(!empty($this->id))
      {
        $conditions[$this->alias.'.'.$this->primaryKey] = '!= '.$this->id;
      }
      
      $records = $this->find('all',array(
        'conditions'  => $conditions,
        'fields'      => array('slug'),
        'recursive'   => -1
      ));
      
      //Slug doesn't exist
      if(empty($records))
      {
        return $slug;
      }
      
      //Find highest number
      $existingSlugs = Set::extract($records,'{n}.'.$this->alias.'.slug');
      $highest = 0;
      
      foreach($existingSlugs as $existingSlug)
      {
        $slugNumber = str_replace($slug,'',$existingSlug);
        if(is_numeric($slugNumber) && $slugNumber > $highest)
        {
          $highest = $slugNumber;
        }
      }
      
      //Set
      $highest++;
      return $slug.$highest;
    }
    
    
    /**
     * Has Access
     *
     * If this User.id is listed in People, which is associated with the main company of the account
     *
     * @param string $slug Account slug
     * @param array $user User data array
     * @access public
     * @return boolean
     */
    public function hasAccess($id,$user)
    {
      return $this->Person->find('count',array(
        'conditions' => array(
          'Person.user_id' => $user['User']['id'],
          'Company.account_id' => $id
        ),
        'contain' => array(
          'Company' => array('id')
        )
      ));
    }

  }
  
?>
