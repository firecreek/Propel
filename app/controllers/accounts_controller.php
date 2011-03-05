<?php

  /**
   * Accounts Controller
   *
   * @category Controller
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AccountsController extends AppController
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
    public $components = array('Authorization');
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $uses = array('Project','Log');
    
    
    /**
     * Register new user, account and person
     *
     * @access public
     * @return void
     */
    public function account_index()
    {      
      //
      $projects = $this->Authorization->read('Projects');
      
      //Active projects
      if(!empty($projects))
      {
        $activeProjectCount = $this->Project->find('count',array(
          'conditions' => array(
            'Project.id' => Set::extract('{n}.Project.id',$projects),
            'OR' => array(
              'Project.todo_count >'      => '0',
              'Project.milestone_count >' => '0',
              'Project.post_count >'      => '0',
            )
          ),
          'recursive' => -1
        ));
      }
      
      //New account, create project
      if(
        (!$this->Authorization->read('Person.company_owner') && empty($projects)) ||
        (!$this->Authorization->read('Person.company_owner') && isset($activeProjectCount) && $activeProjectCount == 0)
      )
      {
        //Shared user
        return $this->render('account_index_shared');
      }
      elseif(empty($projects))
      {
        return $this->render('account_index_new');
      }
      
      //Logs      
      $logs = array();
      
      foreach($projects as $project)
      {
        $logRecords = $this->Log->find('all',array(
          'conditions' => array(
            'Log.project_id' => $project['Project']['id'],
            'Log.created >'  => date('Y-m-d',strtotime('-30 days'))
          ),
          'order' => 'Log.created DESC',
          'limit' => 25,
          'contain' => array(
            'Person' => array('first_name','last_name')
          )
        ));
      
        $logs[] = array_merge(array(
          'Log' => $logRecords
        ),$project);
      }
      
      $this->set(compact('activeProjectCount','logs'));
    }
  
  }
  
  
?>
