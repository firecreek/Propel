<?php
  
  /**
   * Search Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
    public $components = array('Authorization');
    
    /**
     * Search
     *
     * @access public
     * @var array
     */
    public $search = array(
      'terms'   => null,
      'scope'   => null,
      'global'  => false
    );
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array('SearchIndex');
    
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
     * Before Filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      //Models that can be searched
      $this->modelScopes = array(
        'messages'    => array('name'=>__('Messages',true),'models'=>array('Post')),
        'comments'    => array('name'=>__('Comments',true),'models'=>array('Comment')),
        'todos'       => array('name'=>__('To-Dos',true),'models'=>array('Todo','TodoItem')),
        'milestones'  => array('name'=>__('Milestones',true),'models'=>array('Milestone')),
      );
      
      //Parameters
      foreach($this->search as $key => $default)
      {
        if(isset($this->params['url'][$key]))
        {
          $this->search[$key] = $this->params['url'][$key];
        }
      }
      
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
      $this->set('modelScopes',$this->modelScopes);
      
      foreach($this->search as $key => $val)
      {
        $this->set($key,$val);
      }
      
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
      if(!empty($this->search['terms']))
      {
        $this->_searchSave('global');
        
        //Search across all my accounts
        $conditions = array('SearchIndex.account_id' => Set::extract($this->Authorization->read('Accounts'),'{n}.Account.id'));
        $this->set('records',$this->_search($conditions));
      }
      
      $this->set('recentSearches',$this->_searchRecent('global'));
    }
    
    
    /**
     * Project Index
     *
     * @access public
     * @return void
     */
    public function project_index()
    {
      $key = 'project_'.$this->Authorization->read('Project.id');
    
      if(!empty($this->search['terms']))
      {
        $this->_searchSave($key);
        
        //Search
        if($this->search['global'])
        {
          $conditions = array('SearchIndex.account_id' => Set::extract($this->Authorization->read('Accounts'),'{n}.Account.id'));
        }
        else
        {
          $conditions = array('SearchIndex.project_id' => $this->Authorization->read('Project.id'));
        }
        
        $this->set('records',$this->_search($conditions));
      }
      
      $this->set('recentSearches',$this->_searchRecent($key));
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
     * Forget history
     *
     * @access public
     * @return void
     */
    public function project_forget()
    {
      $this->_searchForget('project_'.$this->Authorization->read('Project.id'));
      $this->redirect(array('action'=>'index'));
    }
    
    
    /**
     * Do the search
     *
     * @access private
     * @return void
     */
    private function _search($conditions,$options = array())
    {
      $this->paginate['conditions'] = array_merge(array(
        'SearchIndex.keywords LIKE' => '%'.$this->search['terms'].'%'
      ),$conditions);
      
      $this->paginate['contain'] = array(
        'Person' => array('id','first_name','last_name','full_name'),
        'Account' => array('id','name','slug'),
        'Project' => array('id','name')
      );
      
      $this->paginate['order'] = 'SearchIndex.model_created DESC';
      
      //Scope
      if(!empty($this->search['scope']) && $this->search['scope'] !== 'all')
      {
        $this->paginate['conditions']['model'] = $this->modelScopes[$this->search['scope']]['models'];
      }
      
      return $this->paginate();
    }
    
    
    /**
     * Save search
     *
     * @access private
     * @return boolean
     */
    private function _searchSave($type)
    {
      $current = $this->Session->read('Searches.'.$type);
      
      $terms  = $this->search['terms'];
      $scope  = $this->search['scope'];
      $global = $this->search['global'];
      
      if(!empty($current))
      {
        foreach($current as $key => $check)
        {
          if($check['terms'] == $terms) { unset($current[$key]); }
        }
      }
      
      $current[] = array('terms'=>$terms,'scope'=>$scope,'global'=>$global);
      
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
