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
      
      $companies = $this->Company->find('list',array(
        'conditions' => array(
          'Company.account_id' => $this->Authorization->read('Account.id')
        )
      ));
      
      //Save
      if(!empty($this->data))
      {
        if(isset($this->data['Permission']))
        {
          //Update permissions
          $updated = 0;
          foreach($this->data['Permission'] as $projectId => $checked)
          {
            if($checked != $this->Person->hasPermission($personId,'Projects',$projectId))
            {
              $updated++;
              if(!$checked)
              {
                //Remove access to this project
                $this->Person->id = $personId;
                $this->AclManager->delete($this->Person, 'projects', $projectId, null, array('all'=>true));
              }
              else
              {
                //Add default access to this project
                $this->Person->id = $personId;
                $this->AclManager->allow($this->Person, 'projects', $projectId, array('set'=>'shared','delete'=>true));
              }
            }
          }
          
          //Flash
          if($updated)
          {
            $this->Session->setFlash(__('Permissions for user updated',true),'default',array('class'=>'success'));
          }
          else
          {
            $this->Session->setFlash(__('No permissions to update',true),'default',array('class'=>'error'));
          }
          
        }
        else
        {
          //Update person details
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
      }
      
      if(empty($this->data))
      {
        $this->data = $record;
      }
      
      //Projects and users permissions to them
      $projects = $this->Project->find('all',array(
        'conditions' => array(
          'Project.account_id' => $this->Authorization->read('Account.id')
        ),
        'fields' => array('id','name','status'),
        'contain' => false
      ));
      $projectPermissions = $this->Person->projectPermissions($personId);
      $projectPermissions = Set::combine($projectPermissions,'{n}.Project.id','{n}.Project.name');
      
      $this->set(compact('personId', 'record', 'companies','projects','projectPermissions'));
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
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Person']['company_id']       = $companyId;
        $this->data['Person']['account_id']       = $this->Authorization->read('Account.id');
        $this->data['Person']['status']           = 'invited';
        $this->data['Person']['invitation_date']  = date('Y-m-d H:i:s');
        
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
    
    
    /**
     * Edit
     *
     * @access public
     * @return void
     */
    public function project_edit($personId)
    {
      $this->account_edit($personId);
      return $this->render('account_edit');
    }
    
  
  }
  
  
?>
