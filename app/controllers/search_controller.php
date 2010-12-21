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
     * @var array
     */
    public $components = array();
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array();
    
    /**
     * Models that can be searched
     *
     * @access public
     * @var array
     */
    public $modelScopes = array();
    
    /**
     * Search history limit
     *
     * @access public
     * @var int
     */
    public $searchHistoryLimit = 10;
    
    /**
     * ACL Mapping
     *
     * @access public
     * @var array
     */
    public $actionMap = array(
      'forget' => '_read'
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
        
        $this->_searchSave('global',$terms,$scope);
        
        $results = array();
        $this->set(compact('results'));
      }
      
      $recentSearches = $this->_searchRecent('global');
      
      $this->set(compact('terms','scope','search','recentSearches'));
    }
    
    
    /**
     * Forget history
     *
     * @access public
     * @return void
     */
    public function account_forget()
    {
      $this->_searchForget('global');
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
           
    }
    
    
    /**
     * Save search
     *
     * @access private
     * @return boolean
     */
    private function _searchSave($type, $terms, $scope)
    {
      $current = $this->Session->read('Searches.'.$type);
      
      if(!empty($current))
      {
        foreach($current as $key => $check)
        {
          if($check['terms'] == $terms) { unset($current[$key]); }
        }
      }
      
      $current[] = array('terms'=>$terms,'scope'=>$scope);
      
      if(count($current) > $this->searchHistoryLimit)
      {
        $current = array_slice($current,count($current)-$this->searchHistoryLimit);
      }
      
      return $this->Session->write('Searches.'.$type,$current);
    }
    
    
    /**
     * Get recent searches
     *
     * @access private
     * @return boolean
     */
    private function _searchRecent($type)
    {
      $results = $this->Session->read('Searches.'.$type);
      if(!empty($results)) { $results = array_reverse($results); }
      return $results;
    }
    
    
    /**
     * Search forget
     *
     * @access private
     * @return boolean
     */
    private function _searchForget($type)
    {
      return $this->Session->delete('Searches.'.$type);
    }
    
  
  }
  
  
?>
