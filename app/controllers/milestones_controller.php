<?php

  /**
   * Milestones Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class MilestonesController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array();
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('Milestone','Company');
    
    
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
      
      //Responsible options
      $responsibleOptions = array();
      $responsibleOptions['all'] = __('Anyone',true);
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
        $name = 'Everyone';
      }
      else
      {
        $name = $responsibleMap[$responsible];
        if($responsible == 'person_'.$this->Authorization->read('Person.id'))
        {
          $responsibleSelf = true;
        }
      }
      
      $this->set(compact('name','responsible','responsibleOptions','responsibleSelf'));
    }
  
  }
  
  
?>
