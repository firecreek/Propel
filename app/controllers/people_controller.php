<?php

  /**
   * People Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PeopleController extends AppController
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
    public $components = array('Message');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Person','Company');
    
    
    /**
     * Add
     *
     * @param int $companyId Company pk to add person to
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
          $this->Account->id = $this->Authorization->read('Account.id');
          $this->AclManager->allow($this->Person, $this->Account, array('alias'=>'shared'));
          
          //
          $data = $this->data;
          $data['PersonInvitee'] = $this->Authorization->read('Person');
          
          $this->Message->send('invite',array(
            'subject' => __('You\'re invited to join our project management system',true),
            'to' => $this->data['Person']['email']
          ),$data);
          
          //Message and redirect
          $this->Session->setFlash(__('Person added to company',true),'default',array('class'=>'success'));
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
     * @param int $personId Person pk
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
          'PersonAccess' => array(),
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
            $this->Person->id = $personId;
            $this->Project->id = $projectId;
            
            if($checked != $this->AclManager->check($this->Person,$this->Project))
            {
              //Company id
              $this->Project->id = $projectId;
            
              $updated++;
              if(!$checked)
              {
                //Remove access to this project
                $this->AclManager->delete($this->Person,$this->Project);
              }
              else
              {
                //Add default access to this project
                $this->AclManager->allow($this->Person,$this->Project,array('alias'=>'shared'));
                
                //Company access
                $this->User->Company->id = $this->Person->field('company_id');
                $this->AclManager->allow($this->User->Company,$this->Project);
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
          
          $this->redirect(array('action'=>'edit',$personId));
          
        }
        else
        {
          //Update person details
          $this->data['Person']['id'] = $personId;
          
          $this->Person->set($this->data);
          
          if($this->Person->validates() && isset($companies[$this->data['Person']['company_id']]))
          {
            $this->Person->save();
            $this->Session->setFlash(__('Persons details have been updated',true),'default',array('class'=>'success'));
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
      
      //Projects for this account
      $projects = $this->Project->find('all',array(
        'conditions' => array(
          'Project.account_id' => $this->Authorization->read('Account.id')
        ),
        'fields' => array('id','name','status'),
        'contain' => false
      ));
      
      //Access person has to projects
      foreach($projects as $key => $project)
      {
        $access = false;
        if(Set::extract($record,'/PersonAccess[model=Project][model_id='.$project['Project']['id'].']'))
        {
          $access = true;
        }
        
        $projects[$key]['Project']['_access'] = $access;
      }
      
      $this->set(compact('personId', 'record', 'companies','projects'));
    }
    
    
    /**
     * Delete person from account
     *
     * @param int $personId Person pk
     * @access public
     * @return void
     */
    public function account_delete($personId)
    {
      if($this->Person->delete($personId,true))
      {
        $this->Session->setFlash(__('Person removed from account',true),'default',array('class'=>'success'));
      }
      else
      {
        $this->Session->setFlash(__('There was a problem deleting this person, please try again',true),'default',array('class'=>'error'));
      }
      
      $this->redirect(array('controller'=>'companies','action'=>'index'));
    }
    
    
    /**
     * Resend invitation
     *
     * @param int $personId Person pk
     * @access public
     * @return void
     */
    public function account_invite_resend($personId)
    {
      $this->layout = 'plain';
      
      $record = $this->Person->find('first',array(
        'conditions' => array('Person.id'=>$personId),
        'contain' => false
      ));
      
      if(!empty($this->data))
      {
        $this->data['Person']['id'] = $personId;
        $this->data['Person']['email'] = $this->data['Person']['email'];
        $this->data['Person']['invitation_date']  = date('Y-m-d H:i:s');
        $this->data['Person']['invitation_person_id'] = $this->Authorization->read('Person.id'); 
        
        $this->Person->set($this->data);
        
        if($this->Person->validates())
        {
          $this->Person->save();
        
          $data = array_merge($this->data);
          $data['Person']['first_name'] = $record['Person']['first_name'];
          $data['Person']['invitation_code'] = $record['Person']['invitation_code'];
          $data['PersonInvitee'] = $this->Authorization->read('Person');
          
          $this->Message->send('invite',array(
            'subject' => __('You\'re invited to join our project management system',true),
            'to' => $this->data['Person']['email']
          ),$data);
          
          $this->Session->setFlash(sprintf(__('Instructions have been sent to %s',true),$this->data['Person']['email']),'default',array('class'=>'success'));
          
          $this->redirect(array('action'=>'edit',$personId));
        }
      }
      else
      {
        $this->data['Person']['email'] = $record['Person']['email'];
      }
      
      $this->set(compact('personId','record'));
    }
    
    
    /**
     * Project add
     *
     * @param int $companyId Company pk to add person to
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
        $this->cakeError('recordWrongAccount');
      }
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Person']['company_id']       = $companyId;
        $this->data['Person']['account_id']       = $this->Authorization->read('Account.id');
        $this->data['Person']['status']           = 'invited';
        $this->data['Person']['invitation_date']  = date('Y-m-d H:i:s');
        $this->data['Person']['invitation_person_id'] = $this->Authorization->read('Person.id');
        $this->data['Person']['invitation_code'] = md5(time());    
        
        $this->Person->set($this->data);
        
        if($this->Person->validates())
        {
          $this->Person->save();
          
          $this->Account->id = $this->Authorization->read('Account.id');
          $this->Project->id = $this->Authorization->read('Project.id');
          
          //Add permissions
          $this->AclManager->allow($this->Person, $this->Account, array('alias'=>'shared'));
          $this->AclManager->allow($this->Person, $this->Project, array('alias'=>'shared'));
          
          //
          $data = $this->data;
          $data['PersonInvitee'] = $this->Authorization->read('Person');
          
          $this->Message->send('invite',array(
            'subject' => __('You\'re invited to join our project management system',true),
            'to' => $this->data['Person']['email']
          ),$data);
          
          //Message and redirect
          $this->Session->setFlash(__('Person added to company',true),'default',array('class'=>'success'));
          $this->redirect(array('controller'=>'companies','action'=>'index'));
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
     * @param int $personId Person pk
     * @access public
     * @return void
     */
    public function project_edit($personId)
    {
      $this->account_edit($personId);
      
      //Reload companies in which only have access to this project otherwise people might go missing
      foreach($this->viewVars['companies'] as $companyId => $companyName)
      {
        if(!$this->Company->hasPermission($companyId,'Projects',$this->Authorization->read('Project.id')))
        {
          unset($this->viewVars['companies'][$companyId]);
        }
      }
      
      return $this->render('account_edit');
    }
    
  
  }
  
  
?>
