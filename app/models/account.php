<?php

  class Account extends AppModel {
    var $name = 'Account';
    var $validate = array(
      'slug' => array(
        'notempty' => array(
          'rule' => array('notempty'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'project_count' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'person_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
      'user_id' => array(
        'numeric' => array(
          'rule' => array('numeric'),
          //'message' => 'Your custom message here',
          //'allowEmpty' => false,
          //'required' => false,
          //'last' => false, // Stop validation after this rule
          //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
      ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
      'Person' => array(
        'className' => 'Person',
        'foreignKey' => 'person_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      ),
      'User' => array(
        'className' => 'User',
        'foreignKey' => 'user_id',
        'conditions' => '',
        'fields' => '',
        'order' => ''
      )
    );

    var $hasMany = array(
      'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'account_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'Project' => array(
        'className' => 'Project',
        'foreignKey' => 'account_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      ),
      'User' => array(
        'className' => 'User',
        'foreignKey' => 'account_id',
        'dependent' => false,
        'conditions' => '',
        'fields' => '',
        'order' => '',
        'limit' => '',
        'offset' => '',
        'exclusive' => '',
        'finderQuery' => '',
        'counterQuery' => ''
      )
    );
    
    
    public function beforeSave($data)
    {
      if(!isset($this->data[$this->alias]['slug'])) { return true; }
    
      $slug = $this->data[$this->alias]['slug'];
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
        $this->data[$this->alias]['slug'] = $slug;
        return true;
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
      $this->data[$this->alias]['slug'] = $slug.$highest;
      
      return true;
    }

  }
  
?>
