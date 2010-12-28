<?php

  /**
   * People Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PeopleController extends AppController
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
    public $uses = array('Person','Company');
    
    
    /**
     * Add
     *
     * @access public
     * @return void
     */
    public function account_add($companyId)
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
        $this->Session->setFlash(__('You do not have permission to add a person to this company',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true); 
      }
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Person']['company_id'] = $companyId;
        $this->data['Person']['account_id'] = $this->Authorization->read('Account.id');
        
        $this->Person->set($this->data);
        
        if($this->Person->validates())
        {
          $this->Person->save();
          
          //Give this person permission for this account
          $this->AclManager->allow($this->Person, 'accounts', $this->Authorization->read('Account.id'), array('set' => 'shared'));
          
          //Message and redirect
          $this->Session->setFlash(__('Person added to company',true));
          $this->redirect(array('controller'=>'companies','action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      
      $this->set(compact('companyId', 'record'));
    }
    
    
    /**
     * Edit
     *
     * @access public
     * @return void
     */
    public function account_edit($personId)
    {
      $record = $this->Person->find('first',array(
        'conditions' => array(
          'Person.id' => $personId,
          'Company.account_id' => $this->Authorization->read('Account.id')
        ),
        'contain' => array(
          'Company' => array('id'),
          'User' => array()
        )
      ));
      
      if(empty($record))
      {
        $this->Session->setFlash(__('You do not have permission to edit this person',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true); 
      }
      
      $companies = $this->Company->find('list',array(
        'conditions' => array(
          'Company.account_id' => $this->Authorization->read('Account.id')
        )
      ));
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Person']['id'] = $personId;
        
        $this->Person->set($this->data);
        
        if($this->Person->validates() && isset($companies[$this->data['Person']['company_id']]))
        {
          $this->Person->save();
          $this->Session->setFlash(__('Persons details have been updated',true));
          $this->redirect(array('controller'=>'companies','action'=>'index'));
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      
      if(empty($this->data))
      {
        $this->data = $record;
      }
      
      $this->set(compact('personId', 'record', 'companies'));
    }
    
    
    /**
     * Project add
     *
     * @access public
     * @return void
     */
    public function project_add($companyId)
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
        $this->Session->setFlash(__('You do not have permission to add a person to this company',true),'default',array('class'=>'error'));
        $this->redirect($this->referer(), null, true); 
      }
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Person']['company_id'] = $companyId;
        $this->data['Person']['account_id'] = $this->Authorization->read('Account.id');
        
        $this->Person->set($this->data);
        
        if($this->Person->validates())
        {
          $this->Person->save();
          
          //Give this person permission for this account
          $this->AclManager->allow($this->Person, 'accounts', $this->Authorization->read('Account.id'), array('set' => 'shared'));
          
          //Give this person permission for this project
          $this->AclManager->allow($this->Person, 'projects', $this->Authorization->read('Project.id'), array('set' => 'shared'));
          
          //Message and redirect
          $this->Session->setFlash(__('Person added to company',true));
          $this->redirect(array('controller'=>'companies','action'=>'permissions'));
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      
      $this->set(compact('companyId', 'record'));
      
      $this->render('account_add');
    }
  
  }
  
  
?>
