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
      
      if($this->Company->delete($companyId))
      {
        $this->Session->setFlash(__('This company has been deleted',true),'default',array('class'=>'success'));
        $this->redirect(array('action'=>'index'));
      }
      else
      {
        $this->Session->setFlash(__('There was a problem deleting this company',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true);
      }
    }
  
  }
  
  
?>
