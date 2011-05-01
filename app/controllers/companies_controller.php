<?php

  /**
   * Companies Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CompaniesController extends AppController
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
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Company');
    
    /**
     * Action map
     *
     * @access public
     * @var array
     */
    public $actionMap = array(
      'permissions'  => '_update'
    );
    
    
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
            $this->AclManager->allow($this->Company, 'accounts', $this->Authorization->read('Account.id'));
            
            //Redirect
            $this->Session->setFlash(__('Company created',true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'companies','action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('Failed to save company',true), 'default', array('class'=>'error'));
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
     * @param int $companyId Company pk
     * @access public
     * @return void
     */
    public function account_edit($companyId)
    {
      $record = $this->Company->find('first',array(
        'conditions' => array(
          'Company.id' => $companyId
        ),
        'contain' => array()
      ));
      
      //Save
      if(!empty($this->data))
      {
        $this->data['Company']['id'] = $companyId;
        
        $this->Company->set($this->data);
        
        if($this->Company->validates())
        {
          $this->Company->save();
          $this->Session->setFlash(__('Company details updated',true),'default',array('class'=>'success'));
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
     * @param int $companyId Company pk
     * @access public
     * @return void
     */
    public function account_delete($companyId)
    {
      $record = $this->Company->find('first',array(
        'conditions' => array(
          'Company.id' => $companyId,
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
      $this->AclManager->delete($this->Company, 'accounts', $this->Authorization->read('Account.id'), null, array('all' => true));
      
      //Delete company
      $this->Company->delete($companyId);
      
      //Redirect
      $this->Session->setFlash(__('This company has been deleted',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Project index
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
     * Project permissions
     *
     * @access public
     * @return void
     */
    public function project_permissions()
    {
      $this->loadModel('Person');
    
      $grantMap = array(
        1 => 'shared msg-file',
        2 => 'shared msg-file-todo',
        3 => 'shared'
      );
      
      //Form post
      if(!empty($this->data))
      {
        //
        $deleted  = array();
        $granted  = array();
      
        //People who need to be removed
        if(isset($this->data['Person']) && !empty($this->data['Person']))
        {
          foreach($this->data['Person'] as $personId => $checked)
          {
            if(!$checked && $personId != $this->Authorization->read('Person.id'))
            {
              //Remove person from accessing this project
              $this->Person->id = $personId;
              $this->AclManager->delete($this->Person, 'projects', $this->Authorization->read('Project.id'), null, array('all'=>true));
              $deleted[] = $personId;
            }
          }
        }
        
    
        //Grants
        if(isset($this->data['Grant']) && !empty($this->data['Grant']))
        {
          foreach($this->data['Grant'] as $personId => $grantKey)
          {
            if(
              $personId != $this->Authorization->read('Person.id') &&
              !in_array($personId,$deleted) &&
              (
                !isset($this->data['GrantOriginal'][$personId]) ||
                (isset($this->data['GrantOriginal'][$personId]) && $this->data['GrantOriginal'][$personId] != $grantKey)
              )
            )
            {
              $set = $grantMap[$grantKey];
              $this->Person->id = $personId;
          
              //Add back in with new set
              $this->AclManager->allow($this->Person, 'projects', $this->Authorization->read('Project.id'), array('set'=>$set,'delete'=>true));
              $granted[] = $personId;
            }
          }
        }
        
        //Ajax response
        if($this->RequestHandler->isAjax())
        {
          $this->set(compact('deleted','granted'));
          return $this->render();
        }
        
        //Return
        if(count($deleted) > 0 || count($granted) > 0)
        {
          $this->Session->setFlash(__('Permissions updated',true),'default',array('class'=>'success'));
        }
        else
        {
          $this->Session->setFlash(__('No changes made to permissions',true));
        }
        
        $this->redirect(array('action'=>'permissions'));
      }
    
    
      //Get list of all people in this companies
      $projectId = $this->Authorization->read('Project.id');
      $companies = $this->Authorization->read('Companies');
      
      foreach($companies as $key => $company)
      {
        $people = $this->Company->Person->find('all',array(
          'conditions' => array('Person.company_id'=>$company['Company']['id']),
          'fields' => array('id','first_name','last_name','full_name','status','email','company_owner'),
          'contain' => false,
          'order' => 'Person.first_name ASC'
        ));
        
        foreach($people as $personKey => $person)
        {
          $this->Person->id = $person['Person']['id'];
          $grantKey = null;
          
          if($this->AclManager->check($this->Person, 'projects', $projectId, 'Milestones'))
          {
            $grantKey = 3;
          }
          elseif($this->AclManager->check($this->Person, 'projects', $projectId, 'Todos'))
          {
            $grantKey = 2;
          }
          elseif($this->AclManager->check($this->Person, 'projects', $projectId, 'Posts'))
          {
            $grantKey = 1;
          }
          
          $people[$personKey]['Person']['_grantKey'] = $grantKey;
        }
        
        $companies[$key]['People'] = Set::extract($people,'{n}.Person');
      }
      
      $this->set('records',$companies);
    }
    
    
    /**
     * Project add company
     *
     * @access public
     * @return void
     */
    public function project_add()
    {
      //Get list of companies that can be added
      //@todo Could Auth Companies be read?
      $modelRootNode = $this->Acl->Aco->node('opencamp/accounts/'.$this->Authorization->read('Account.id'));
      $records = $this->Acl->Aco->Permission->find('all', array(
        'conditions' => array(
          'Aro.model' => 'Company',
          'Permission.aco_id' => $modelRootNode[0]['Aco']['id'],
          'Permission._read' => true
        ),
        'fields' => array('Aro.foreign_key')
      ));
      $records = Set::extract($records,'{n}.Aro.foreign_key');
      
      //Remove records that have already been added
      $existingCompanies = $this->Authorization->read('Companies');
      
      foreach($existingCompanies as $existingCompany)
      {
        $find = array_search($existingCompany['Company']['id'],$records);
        
        if($find !== false)
        {
          unset($records[$find]);
        }
      }
      
      //List of companies that can be added
      $companies = $this->User->Company->find('list',array(
        'conditions' => array(
          'Company.id' => $records
        ),
        'fields' => array('id','name'),
        'contain' => false
      ));
    
      //Post
      if(!empty($this->data))
      {
        if($this->data['Permission']['option'] == 'select')
        {
          //Checks
          if(isset($companies[$this->data['Company']['id']]))
          {
            //Grant permission for company to project
            $this->Company->id = $this->data['Company']['id'];
            $this->AclManager->allow($this->Company, 'projects', $this->Authorization->read('Project.id'));
            
            //Add people to this project
            if($this->data['Permission']['add_people'])
            {
              $people = $this->Company->Person->find('all',array(
                'conditions' => array('Person.company_id'=>$this->data['Company']['id']),
                'fields' => array('id'),
                'contain' => false
              ));
              
              if(!empty($people))
              {
                foreach($people as $person)
                {
                  $this->Person->id = $person['Person']['id'];
                  $this->AclManager->allow($this->Person, 'projects', $this->Authorization->read('Project.id'), array('set' => 'shared'));
                }
              }
            }
            
            $this->Session->setFlash(__('Company added to project',true), 'default', array('class'=>'success'));
          }
          else
          {
            $this->Session->setFlash(__('You do not have permission to add the company',true), 'default', array('class'=>'error'));
          }
          
          $this->redirect(array('controller'=>'companies','action'=>'permissions'));
        }
        else
        {
          //Normal save
          $this->data['Company']['account_id'] = $this->Authorization->read('Account.id');
          $this->data['Company']['person_id'] = $this->Authorization->read('Person.id');
        
          $this->Company->set($this->data);
          
          if($this->Company->validates())
          {
            if($this->Company->save($this->data))
            {
              //Grant permission for company to account
              $this->AclManager->allow($this->Company, 'accounts', $this->Authorization->read('Account.id'));
              
              //Grant permission for company to project
              $this->AclManager->allow($this->Company, 'projects', $this->Authorization->read('Project.id'));
              
              //Redirect
              $this->Session->setFlash(__('Company created',true), 'default', array('class'=>'success'));
              $this->redirect(array('controller'=>'companies','action'=>'permissions'));
            }
            else
            {
              $this->Session->setFlash(__('Failed to save company',true), 'default', array('class'=>'error'));
            }
          }
          else
          {
            $this->Session->setFlash(__('Please check the form and try again',true), 'default', array('class'=>'error'));
          }
        }
      }
      
      $this->set(compact('companies'));
    }
    
    
    /**
     * Project delete company
     *
     * This function does not delete companies, it will only remove the
     * company and people from permissions
     * 
     * @param int $companyId Company pk
     * @access public
     * @return void
     */
    public function project_delete($id)
    {
      //Delete permission for company
      $this->Company->id = $id;
      $this->AclManager->delete($this->Company, 'projects', $this->Authorization->read('Project.id'), null, array('all'=>true));
      
      //Remove all people associated with this company
      $people = $this->Company->Person->find('all',array(
        'conditions' => array('Person.company_id'=>$id),
        'fields' => array('id'),
        'contain' => false
      ));
      
      if(!empty($people))
      {
        foreach($people as $person)
        {
          $this->Person->id = $person['Person']['id'];
          $this->AclManager->delete($this->Person, 'projects', $this->Authorization->read('Project.id'), null, array('all'=>true));
        }
      }
      
      $this->Session->setFlash(__('Company has been removed from this project',true),'default',array('class'=>'success'));
      $this->redirect(array('action'=>'permissions'));
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
