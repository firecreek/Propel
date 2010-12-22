<?php

  /**
   * Todos Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class TodosController extends AppController
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
    public $uses = array('Todo','Company');
    
    /**
     * Due options
     *
     * @access public
     * @var array
     */
    public $dueOptions = array();
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->dueOptions = array(
        'anytime'     => __('Anytime',true),
        '_0'          => '----------------',
        'today'       => __('Today',true),
        'tomorrow'    => __('Tomorrow',true),
        'this-week'   => __('This week',true),
        'next-week'   => __('Next week',true),
        'later'       => __('Later',true),
        '_1'          => '----------------',
        'past'        => __('In the past',true),
        'no-date'     => __('(No date set)',true),
      );
      
      parent::beforeFilter();
    }
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      $this->set('dueOptions',$this->dueOptions);
      parent::beforeRender();
    }
    
    
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function account_index()
    {
      //Params
      $this->data['Todo']['responsible']  = isset($this->params['url']['responsible']) ? $this->params['url']['responsible'] : 'all';
      $this->data['Todo']['due']          = isset($this->params['url']['due']) ? $this->params['url']['due'] : 'anytime';
      
      $responsible = $this->Opencamp->findResponsible();
      
      $this->set(compact('responsible','records'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      //Params
      $this->data['Todo']['responsible']  = isset($this->params['url']['responsible']) ? $this->params['url']['responsible'] : 'all';
      $this->data['Todo']['due']          = isset($this->params['url']['due']) ? $this->params['url']['due'] : 'anytime';
      
      $responsible = $this->Opencamp->findResponsible();
      
      $this->set(compact('responsible','records'));
    }
  
  
  }
  
  
?>
