<?php

  /**
   * Categories Controller
   *
   * @category Controller
   * @package  Propel
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   */
  class CategoriesController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @access public
     */
    public $helpers = array('Listable');
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array('Authorization');
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('Category');
    
    
    /**
     * Add category
     *
     * @access public
     * @return void
     */
    public function add($type)
    {
      if(!empty($this->data))
      {
        $this->data['Category']['type'] = $type;
        $this->data['Category']['account_id'] = $this->Authorization->read('Account.id');
        
        if($this->Authorization->read('Project.id'))
        {
          $this->data['Category']['project_id'] = $this->Authorization->read('Project.id');
        }
        
        $this->Category->set($this->data);
        
        if($this->Category->validates())
        {
          $this->Category->save();
          
          $id = $this->Category->id;
        
          $record = $this->Category->find('first',array(
            'conditions' => array('Category.id'=>$id),
            'contain' => false
          ));
          
          $this->set(compact('id','record','type'));
        }
      }
    }
    
    
    /**
     * Edit category
     *
     * @access public
     * @return void
     */
    public function edit($id)
    {
      //Post
      if(!empty($this->data))
      {
        $this->Category->id = $id;
        $this->Category->saveField('name',$this->data['Category']['name']);
      }
    
      //Load
      $record = $this->Category->find('first',array(
        'conditions' => array(
          'id' => $id
        ),
        'contain' => false
      ));
      
      $this->data = $record;
      $this->set(compact('record','id'));
    }
    
    
    /**
     * Delete category
     *
     * @access public
     * @return void
     */
    public function delete($id)
    {
      $this->Category->delete($id);
      $this->set(compact('id'));
    }
  
  }
  
  
?>
