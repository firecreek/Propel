<?php

  /**
   * Projects Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class ProjectsController extends AppController
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
    public $uses = array('Project');
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_add()
    {
      if(!empty($this->data))
      {
        $this->data['Project']['account_id'] = $this->Authorization->read('Account.id');
        $this->data['Project']['person_id'] = $this->Authorization->read('Person.id');
        $this->data['People'] = array('id' => $this->Authorization->read('Person.id'));
      
        $this->Project->set($this->data);
        
        if($this->Project->validates())
        {
          if($this->Project->saveAll($this->data))
          {
            //Give this user permission
            $root = $this->Acl->Aco->node('projects');
            $root = $root[0];
            
            $this->Acl->Aco->create(array(
              'parent_id'       => $root['Aco']['id'],
              'model'           => 'Project',
              'foreign_key'     => $this->Project->id,
              'alias'           => $this->Project->id
            ));
            $this->Acl->Aco->save();
            
            //Give this person permission for this account
            $this->User->Person->id = $this->Authorization->read('Person.id');
            $this->Acl->allow($this->User->Person, 'projects/'.$this->Project->id);
          
            //
            $this->Session->setFlash(__('Project created',true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'accounts','action'=>'index'));
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
