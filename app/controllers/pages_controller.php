<?php

  /**
   * Pages Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PagesController extends AppController
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
    public $uses = array();
    
    
    /**
     * Before Render
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
      $this->Authorization->allow('display');
    
      parent::beforeFilter();
    }
    
    
    /**
     * Display
     *
     * @access public
     * @return void
     */
    public function display()
    {
      $path = func_get_args();

      $count = count($path);
      if (!$count) {
        $this->redirect('/');
      }
      $page = $subpage = $title_for_layout = null;

      if (!empty($path[0])) {
        $page = $path[0];
      }
      if (!empty($path[1])) {
        $subpage = $path[1];
      }
      if (!empty($path[$count - 1])) {
        $title_for_layout = Inflector::humanize($path[$count - 1]);
      }
      $this->set(compact('page', 'subpage', 'title_for_layout'));
      $this->render(implode('/', $path));
    }
  
  
  }
  
  
?>
