<?php

  /**
   * Todos Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class TodosController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Company');
    
    /**
     * Due options
     *
     * @access public
     * @var array
     */
    public $dueOptions = array();
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->dueOptions = array(
        'anytime'     => __('Anytime',true),
        '_0'          => '----------------',
        'today'       => __('Today',true),
        'tomorrow'    => __('Tomorrow',true),
        'this-week'   => __('This week',true),
        'next-week'   => __('Next week',true),
        'later'       => __('Later',true),
        '_1'          => '----------------',
        'past'        => __('In the past',true),
        'no-date'     => __('(No date set)',true),
      );
      
      parent::beforeFilter();
    }
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      $this->set('dueOptions',$this->dueOptions);
      parent::beforeRender();
    }
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      //Params
      $responsible  = isset($this->params['url']['responsible']) ? $this->params['url']['responsible'] : 'all';
      $due          = isset($this->params['url']['due']) ? $this->params['url']['due'] : 'anytime';
    
      //Responsible options
      $responsibleOptions = array();
      $responsibleOptions['all'] = __('Anyone (unassigned)',true);
      $responsibleOptions['person_'.$this->Authorization->read('Person.id')] = __('Me',true).' ('.$this->Authorization->read('Person.full_name').')';
    
      //Build a list of companies + people that can be assigned todos in this account
      $records = $this->Company->find('all',array(
        'conditions' => array(
          'Company.account_id' => $this->Authorization->read('Account.id'),
        ),
        'fields' => array('id','name'),
        'contain' => array(
          'PersonOwner' => array('id','user_id'),
          'People' => array('id','full_name','email','title','company_owner')
        ),
        'order' => 'Company.created DESC'
      ));
      
      //Add to list
      $responsibleMap = array();
      foreach($records as $company)
      {
        $responsibleOptions['company_'.$company['Company']['id']] = strtoupper($company['Company']['name']);
        $responsibleMap['company_'.$company['Company']['id']] = $company['Company']['name'];
        
        foreach($company['People'] as $person)
        {
          $responsibleMap['person_'.$person['id']] = $person['full_name'];
          if(!isset($responsibleOptions['person_'.$person['id']]))
          {
            $responsibleOptions['person_'.$person['id']] = $person['full_name'];
          }
        }
      }
      
      //Validate responsible
      $responsibleSelf = false; 
      if($responsible != null && !isset($responsibleOptions[$responsible]))
      {
        $this->Session->setFlash(__('Invalid responsible party',true),'default',array('class'=>'error'));
        $responsible = 'all';
      }
      
      if($responsible == 'all')
      {
        $name = 'Unassigned';
      }
      else
      {
        $name = $responsibleMap[$responsible];
        if($responsible == 'person_'.$this->Authorization->read('Person.id'))
        {
          $responsibleSelf = true;
        }
      }
      
      //Validate due
      if(substr($due,0,1) == '_')
      {
        $due = 'anytime';
      }
      elseif(!isset($this->dueOptions[$due]))
      {
        $this->Session->setFlash(__('Invalid due range',true),'default',array('class'=>'error'));
        $due = 'anytime';
      }
      
      $this->set(compact('name','responsible','due','responsibleOptions','responsibleSelf'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
    
    }
    
  
  }
  
  
?>
