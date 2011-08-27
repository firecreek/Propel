<?php

  /**
   * Accounts Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AccountsController extends AppController
  {
    /**
     * Helpers
     *
     * @access public
     * @var array
     */
    public $helpers = array('Image');
    
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
      $accounts = $this->Authorization->read('Accounts');
      
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
      if(!$this->Authorization->read('Person.company_owner') && empty($projects))
      {
        //Shared user
        return $this->render('account_index_shared');
      }
      elseif(empty($projects))
      {
        return $this->render('account_index_new');
      }
      
      //Logs
      $this->paginate['Log'] = array(
        'conditions' => array(
          'OR' => array(
            'Log.project_id' => Set::extract($projects,'{n}.Project.id'),
            'Log.account_id' => Set::extract($accounts,'{n}.Account.id'),
          )
        ),
        'order' => 'Log.created DESC',
        'limit' => 100,
        'contain' => array(
          'Person' => array('id','company_id','first_name','last_name'),
          'Project' => array('id','name'),
          'Account' => array('id','name','slug'),
        )
      );
      $logs = $this->paginate('Log');      
      
      $this->set(compact('activeProjectCount','logs'));
    }
  
  }
  
  
?>
