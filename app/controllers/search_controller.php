<?php

  App::import('Sanitize');
  
  /**
   * Search Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class SearchController extends AppController
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
    public $uses = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $modelScopes = array(
    );
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeRender()
    {
      $this->modelScopes = array(
        'messages'    => __('Messages',true),
        'comments'    => __('Comments',true),
        'todos'       => __('To-Dos',true),
        /*'files'     => __('Files',true),*/
        'milestones'  => __('Milestones',true)
      );
    
      $this->set('modelScopes',$this->modelScopes);
      
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
      $search = false;
      $terms  = isset($this->params['url']['terms']) ? $this->params['url']['terms'] : null;
      $scope  = isset($this->params['url']['scope']) ? $this->params['url']['scope'] : 'all';
      
      if(!empty($terms))
      {
        $search = true;
        
        $results = array();
        
        $this->set(compact('results'));
      }
      
      $this->set(compact('terms','scope','search'));
    }
  
  }
  
  
?>
