<?php

  /**
   * Settings Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class SettingsController extends AppController
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
    public $uses = array('Account');
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $actionMap = array(
      'appearance' => '_update'
    );
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      $record = $this->Account->find('first',array(
        'conditions' => array(
          'Account.id' => $this->Authorization->read('Account.id')
        ),
        'contain' => false
      ));
      
      if(!empty($this->data))
      {
        $this->data['Account']['id'] = $this->Authorization->read('Account.id');
        
        $this->Account->set($this->data);
        
        if($this->Account->validates())
        {
          if($this->Account->save($this->data,array('fields'=>'name')))
          {
            $this->Session->setFlash(__('Account settings updated',true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'settings','action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('Failed to save changes',true), 'default', array('class'=>'error'));
          }
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true), 'default', array('class'=>'error'));
        }
      }
      
      if(empty($this->data))
      {
        $this->data = $record;
      }
      
      $this->set(compact('record'));
    }
    
    
    /**
     * Appearance
     *
     * @access public
     * @return void
     */
    public function account_appearance()
    {
    }
  
  }
  
  
?>
