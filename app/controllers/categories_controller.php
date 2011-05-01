<?php

  /**
   * Categories Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CategoriesController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Listable');
    
    /**
     * Components
     *
     * @access public
     * @var array
     */
    public $components = array('Authorization');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('Category');
    
    
    /**
     * Add category
     *
     * @param string $type Type of category
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
     * @param int $id Category pk
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
     * @param int $id Category pk
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
