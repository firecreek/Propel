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
     * Permissions required
     *
     * @access public
     * @access public
     */
    public $permissions = array(
      'account_index' => 'create',
      'account_add' => 'create',
    );
    
    
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
        )
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
  
  }
  
  
?>
