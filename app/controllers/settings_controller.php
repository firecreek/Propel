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
    public $helpers = array('Image');
    
    /**
     * Components
     *
     * @access public
     * @access public
     */
    public $components = array('Assets');
    
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
      'appearance'  => '_update',
      'logo'        => '_update',
    );
    
    /**
     * Scheme keys
     *
     * @access public
     * @access public
     */
    public $schemeKeys = array();
    
    
    /**
     * Before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->schemeKeys = array(
        'backgroundColor'       => array('name'=>'Header Background'),
        'projectTextColour'     => array('name'=>'Project Name'),
        'clientTextColour'      => array('name'=>'Client Name'),
        'tabBackground'         => array('name'=>'Tab Background'),
        'tabBackgroundHover'    => array('name'=>'Tab Hover Background'),
        'tabTextColour'         => array('name'=>'Tab Text'),
        'tabTextColourHover'    => array('name'=>'Tab Hover Text'),
        'tabTextColourActive'   => array('name'=>'Current Tab Text'),
        'linkTextColour'        => array('name'=>'Links')
      );
      
      parent::beforeFilter();
    }
    
    
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
            $this->Session->setFlash(__('Account settings updated',true), 'default', array('class'=>'success'));
      
      $this->set(compact('record'));
    }
    
    
    
    /**
     * Logo
     *
     * @todo More checks on incoming files, use assets component
     * @access public
     * @return void
     */
    public function account_logo($type)
    {
      //Delete
      if(isset($this->params['url']['delete']))
      {
        $file = ASSETS_DIR.DS.'accounts'.DS.$this->Authorization->read('Account.id').DS.'logo'.DS.$type.'.png';
        
        if(file_exists($file))
        {
          if(unlink($file))
          {
            $this->Session->setFlash(__('Logo image has been deleted',true),'default',array('class'=>'success'));
            $this->redirect(array('action'=>'index'));
          }
          else
          {
            $this->Session->setFlash(__('Failed to delete logo image',true),'default',array('class'=>'error'));
            $this->redirect(array('action'=>'logo',$type));
          }
        }
        else
        {
          $this->Session->setFlash(__('Logo image could not be deleted, not found',true),'default',array('class'=>'error'));
        }
        
      }
    
      //Save
      if(!empty($this->data))
      {
        $ext = 'png';
        
        if($type == 'shortcut') { $ext = 'ico'; }
      
        $options = array(
          'filename' => $type.'.'.$ext
        );
      
        if(!$this->Assets->save('logo',$this->data['Account']['image'],$options))
        {
          $error = __('Failed to save image, please try again',true);
          
          if(!empty($this->Assets->errors))
          {
            $error = implode('<br />',$this->Assets->errors);
          }
          
          $this->Session->setFlash($error,'default',array('class'=>'error'));
        }
        else
        {
          $this->Session->setFlash(__('Image has been updated successfully',true),'default',array('class'=>'success'));
          
          $this->redirect(array('action'=>'index'));
        }
      }
      
      $this->set(compact('type'));
    }
    
    
    
    /**
     * Appearance
     *
     * @access public
     * @return void
     */
    public function account_appearance()
    {
      $this->loadModel('Scheme');
      
      if(!empty($this->data))
      {
        //Custom or predefined
        if($this->data['Scheme']['scheme_id'] == 'custom')
        {
          //Delete old
          $this->Scheme->deleteAll(array(
            'Scheme.account_id' => $this->Authorization->read('Account.id')
          ));
          
          $schemeStyles = array();
          foreach($this->data['SchemeStyle'] as $key => $val)
          {
            if(substr($val,0,1) != '#') { $val = '#'.$val; }
            $schemeStyles[] = array(
              'key' => $key,
              'value' => $val
            );
          }
          
          //Create new
          $this->Scheme->saveAll(array(
            'Scheme' => array(
              'name' => 'Custom',
              'account_id' => $this->Authorization->read('Account.id')
            ),
            'SchemeStyle' => $schemeStyles
          ));
          
          $schemeId = $this->Scheme->id;
        }
        else
        {
          $schemeId = $this->data['Scheme']['scheme_id'];
        }
        
        //
        $this->data['Account']['id'] = $this->Authorization->read('Account.id');
        $this->data['Account']['scheme_id'] = $schemeId;
        
        $this->Account->set($this->data);
        
        
        if($this->Account->validates())
        {
          $this->Account->save();
          $this->Session->setFlash(__('Colour scheme updated',true),'default',array('class'=>'success'));
          $this->redirect(array('action'=>'appearance'));
        }
        else
        {
          $this->Session->setFlash(__('Please check the form and try again',true),'default',array('class'=>'error'));
        }
      }
      
      $records = $this->Scheme->find('all',array(
        'conditions' => array(
          '
            Scheme.account_id IS NULL OR
            Scheme.account_id = '.$this->Authorization->read('Account.id').'
          '
        ),
        'order' => 'Scheme.account_id ASC, Scheme.position ASC',
      ));
      
      $this->set('schemeKeys',$this->schemeKeys);
      $this->set(compact('records'));
    }
  
  }
  
  
?>
