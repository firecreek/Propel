<?php

  /**
   * Permissions Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PermissionsController extends AppController
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
    public $components = array('Authorization','AclManager');
    
    /**
     * Uses
     *
     * @access public
     * @access public
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
