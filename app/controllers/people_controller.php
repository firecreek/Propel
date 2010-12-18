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
     * Index
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
        
        $this->Person->set($this->data);
        
        if($this->Person->validates())
        {
          $this->Person->save();
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
  
  }
  
  
?>
