<?php

  /**
   * Permissions Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PermissionsController extends AppController
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
    public $components = array('Authorization','AclManager');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Grant');
    
    
    /**
     * Admin dashboard
     *
     * @access public
     * @return void
     */
    public function admin_index()
    {
      
    }
    
    
    /**
     * Grants
     *
     * @access public
     * @return void
     */
    public function admin_grants()
    {
      if(isset($this->params['url']['update']))
      {
        $grants = $this->Grant->find('all',array(
          'contain' => array('GrantSet'=>array('acos_alias'))
        ));
        
        $controllers = $this->AclManager->listControllers();
        
        foreach($grants as $grant)
        {
          $grantSets = Set::extract($grant['GrantSet'],'{n}.acos_alias');

          foreach($controllers as $name => $file)
          {
            if(!in_array($name,$grantSets))
            {
              $this->Grant->GrantSet->create();
              $this->Grant->GrantSet->save(array(
                'grant_id' => $grant['Grant']['id'],
                'acos_alias' => $name
              ));
            }
          }
        }
      }
      
      $records = $this->Grant->find('all',array(
        'contain' => false,
        'order' => 'name ASC'
      ));
      $this->set(compact('records'));
    }
    
    
    /**
     * Aro updater
     *
     * @access public
     * @return void
     */
    public function admin_aro()
    {
      if(!empty($this->data))
      {
        //Update user account permissions
        $this->loadModel('People');
        
        $people = $this->People->find('list',array(
          'conditions' => array('user_id'=>$this->data['User']['id']),
          'fields' => array('id'),
          'contain' => false
        ));
        
        //For each of their records get the accounts they have access to
        foreach($people as $personId)
        {
          //User ARO
          $aro = $this->Acl->Aro->find('first', array(
            'conditions' => array(
              'Aro.model' => 'Person',
              'Aro.foreign_key' => $personId,
            ),
            'recursive' => -1
          ));
          $personAro = $aro['Aro']['id'];
          
          //Get ACO
          $aco = $this->Acl->Aco->Permission->find('all',array(
            'conditions' => array(
              'Permission.aro_id' => $personAro,
              'Permission._create' => true,
              'Aco.model' => 'Accounts',
            ),
            'fields' => array('Aco.foreign_key','Permission.*')
          ));
          
          $accountIds = Set::extract($aco,'{n}.Aco.foreign_key');
            
          foreach($accountIds as $accountId)
          {
            //Recreate ACO
            $this->AclManager->create('accounts',$accountId);
          
            //Recreate ARO
            $this->User->Person->id = $personId;
            $this->AclManager->allow($this->User->Person, 'accounts', $accountId, array('set' => 'owner'));
          }
          
          $this->Session->setFlash('Updated');
        }
        
        /*

        $records = $this->controller->Acl->Aco->Permission->find('all',array(
          'conditions' => array(
            'Permission.aro_id' => $aroId,
            'Permission._read' => true,
            'Aco.model' => 'Accounts',
          ),
          'fields' => array('Aco.foreign_key','Permission.*'),
          'cache' => array(
            'name' => 'accounts_aro_'.$aroId,
            'config' => 'auth',
          )
        ));
        
        if(!empty($records))
        {
          $accounts = $this->controller->Account->find('all',array(
            'conditions' => array(
              'Account.id'      => Set::extract($records,'{n}.Aco.foreign_key')
            ),
            'contain' => false,
            'fields' => array('id','name','slug'),
            'cache' => array(
              'name' => 'accounts_'.$aroId,
              'config' => 'auth',
            )
          ));
        }*/
      }
    }
    
    
    /**
     * Grant Edit
     *
     * @access public
     * @return void
     */
    public function admin_grant_edit($id)
    {
    
      $grant = $this->Grant->find('first',array(
        'conditions' => array('id'=>$id),
        'contain' => false
      ));
      
      $records = $this->Grant->GrantSet->find('all',array(
        'conditions' => array('GrantSet.grant_id'=>$id),
        'contain' => false,
        'order' => 'acos_alias ASC'
      ));
      
      $this->set(compact('grant','records','id'));
    }
    
    
    public function admin_grant_set_toggle($id,$type)
    {
      $record = $this->Grant->GrantSet->find('first',array(
        'conditions' => array('id'=>$id),
        'contain' => false
      ));
      
      $newValue = $record['GrantSet']['_'.$type] ? 0 : 1;
      
      $this->Grant->GrantSet->id = $id;
      $this->Grant->GrantSet->saveField('_'.$type,$newValue);
      
      $this->set(compact('id','newValue'));
    }
  
  }
  
  
?>
