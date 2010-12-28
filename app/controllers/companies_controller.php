<?php

  /**
   * Companies Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class CompaniesController extends AppController
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
    public $uses = array('Company');
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      $records = $this->_people();
      $this->set(compact('records'));
    }
    
    
    /**
     * Add
     *
     * @access public
     * @return void
     */
    public function account_add()
    {
      if(!empty($this->data))
      {
        $this->data['Company']['account_id'] = $this->Authorization->read('Account.id');
        $this->data['Company']['person_id'] = $this->Authorization->read('Person.id');
      
        $this->Company->set($this->data);
        
        if($this->Company->validates())
        {
          if($this->Company->save($this->data))
          {
            //Grant permission for company
            $this->AclManager->allow($this->Company, 'accounts', $this->Authorization->read('Account.id'), array('set' => 'company'));
            
            //Redirect
            $this->Session->setFlash(__('Company created',true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'companies','action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('Failed to save project',true), 'default', array('class'=>'error'));
          }
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true), 'default', array('class'=>'error'));
        }
        
      }
    }
    
    
    /**
     * Edit
     *
     * @access public
     * @return void
     */
    public function account_edit($companyId)
    {
      $record = $this->Company->find('first',array(
        'conditions' => array(
          'Company.id' => $companyId,
          'Company.account_id' => $this->Authorization->read('Account.id')
        ),
        'contain' => array()
      ));
      
      if(empty($record))
      {
        $this->Session->setFlash(__('You do not have permission to access this company',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true); 
      }
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Company']['id'] = $companyId;
        
        $this->Company->set($this->data);
        
        if($this->Company->validates())
        {
          $this->Company->save();
          $this->Session->setFlash(__('Company details updated',true));
          $this->redirect(array('action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      
      $countries = $this->Company->Country->find('list');
      
      if(empty($this->data))
      {
        $this->data = $record;
      }
      
      $this->set(compact('companyId','countries','record'));
    }
    
    
    /**
     * Delete
     *
     * @access public
     * @return void
     */
    public function account_delete($companyId)
    {
      $record = $this->Company->find('first',array(
        'conditions' => array(
          'Company.id' => $companyId,
          'Company.account_id' => $this->Authorization->read('Account.id'),
          'Company.account_owner' => false
        ),
        'contain' => false
      ));
      
      if(empty($record))
      {
        $this->Session->setFlash(__('You do not have permission to delete this company',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true); 
      }
      

      //Delete permission for company
      $this->Company->id = $companyId;
      $this->AclManager->delete($this->Company, 'accounts', $this->Authorization->read('Account.id'), array('set' => 'company'));
      
      //Delete company
      $this->Company->delete($companyId);
      
      //Redirect
      $this->Session->setFlash(__('This company has been deleted',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      $records = $this->_people();
      $this->set(compact('records'));
    }
    
    
    /**
     * Map companies and people
     *
     * @access public
     * @return void
     */
    private function _people()
    {
      $companies = $this->Authorization->read('Companies');
      $people = $this->Authorization->read('People');
      
      $mapped = array();
  
      foreach($companies as $key => $company)
      {
        $companies[$key]['People'] = array();
        
        foreach($people as $person)
        {
          if($person['Company']['id'] == $company['Company']['id'])
          {
            $companies[$key]['People'][] = $person['Person'];
            continue;
          }
        }
      }
      
      return $companies;
    }
    
  
  }
  
  
?>
