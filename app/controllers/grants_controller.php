<?php

  /**
   * Grants Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class GrantsController extends AppController
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
    public $uses = array('Grant','ArosAco');
    
    
    /**
     * Admin index
     *
     * @access public
     * @return void
     */
    public function admin_index()
    {
      $records = $this->Grant->find('all');
      $this->set(compact('records'));
    }
    
    
    /**
     * Admin add
     *
     * @access public
     * @return void
     */
    public function admin_add()
    {
      if(!empty($this->data))
      {
        $this->Grant->set($this->data);

        if($this->Grant->validates())
        {
          $this->Grant->save();
          $this->Session->setFlash(__('Grant added',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
      }
    }
    
    
    /**
     * Admin edit
     *
     * @access public
     * @return void
     */
    public function admin_edit($id)
    {
      if(!empty($this->data))
      {
        $this->Grant->set($this->data);

        if($this->Grant->validates())
        {
          $this->Grant->save();
          $this->Session->setFlash(__('Grant updated',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'index'));
        }
      }
      else
      {    
        $this->data = $this->Grant->find('first',array(
          'conditions' => array('id'=>$id),
          'contain' => false
        ));
      }
      
      $this->set(compact('id'));
    }
    
    
    /**
     * Admin view
     *
     * @access public
     * @return void
     */
    public function admin_view($id)
    {
      $record = $this->Grant->find('first',array(
        'conditions' => array('id'=>$id),
        'contain' => false
      ));
      
      //Aro for Grant
      $aro = $this->Acl->Aro->find('first',array(
        'conditions' => array(
          'model' => 'Grant',
          'foreign_key' => $id
        ),
        'contain' => false
      ));
      
      //Get all roots
      $roots = $this->Acl->Aco->find('list',array(
        'conditions' => array(
          'parent_id' => 1,
          'alias !='  => 'controllers'
        ),
        'contain' => false,
        'fields' => array('alias')
      ));
    
      $conditions = array(
        'parent_id !='  => null,
        'foreign_key'   => null,
        'NOT' => array(
          'alias' => $roots
        )
      );
      
      $acos = $this->Acl->Aco->generatetreelist($conditions, '{n}.Aco.id', '{n}.Aco.alias');
      $acos = array_slice($acos,1,null,true);
      
      //Permissions
      $permissions = array(); // acoId => roleId => bool
      foreach ($acos AS $acoId => $acoAlias)
      {
        $permissions[$acoId] = 0;
        
        if (substr_count($acoAlias, '__') != 0)
        {
          $permission = array();
          $hasAny = array(
              'aco_id'  => $acoId,
              'aro_id'  => $aro['Aro']['id'],
              '_create' => 1,
              '_read'   => 1,
              '_update' => 1,
              '_delete' => 1,
          );
          if($this->ArosAco->hasAny($hasAny)) {
            $permissions[$acoId] = 1;
          }
        }
      }
      
      $this->set(compact('id','record','acos','permissions'));
    }
    
    
    /**
     * Admin toggle permission
     *
     * @access public
     * @return void
     */
    public function admin_update($id,$switch = false)
    {
      //Aro
      $aro = $this->Acl->Aro->find('first',array(
        'conditions' => array(
          'model' => 'Grant',
          'foreign_key' => $id
        ),
        'contain' => false
      ));
      
      $aco = $this->Acl->Aco->find('first',array(
        'conditions' => array(
          'Aco.id' => $this->params['named']['aco']
        ),
        'contain' => false
      ));
      

      // see if acoId and aroId combination exists
      $conditions = array(
          'ArosAco.aco_id' => $aco['Aco']['id'],
          'ArosAco.aro_id' => $aro['Aro']['id'],
      );
      
      if ($this->ArosAco->hasAny($conditions))
      {
        $data = $this->ArosAco->find('first', array('conditions' => $conditions));
        
        $data['ArosAco']['_create'] = $switch;
        $data['ArosAco']['_read'] = $switch;
        $data['ArosAco']['_update'] = $switch;
        $data['ArosAco']['_delete'] = $switch;
      }
      else
      {
        $data['ArosAco']['aco_id'] = $aco['Aco']['id'];
        $data['ArosAco']['aro_id'] = $aro['Aro']['id'];
        $data['ArosAco']['_create'] = $switch;
        $data['ArosAco']['_read'] = $switch;
        $data['ArosAco']['_update'] = $switch;
        $data['ArosAco']['_delete'] = $switch;
      }

      // save
      $success = 0;
      if($this->ArosAco->save($data))
      {
        $success = 1;
      }
      
      $acoId = $aco['Aco']['id'];
      
      $this->set(compact('success','switch','id','acoId'));
    }
    
    
  }
  
  
?>
