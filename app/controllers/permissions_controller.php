<?php

  /**
   * Permissions Controller
   *
   * @category Controller
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class PermissionsController extends AppController
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
    public $components = array('Authorization','AclManager');
    
    /**
     * Uses
     *
     * @access public
     * @var array
     */
    public $uses = array();
    
    
    /**
     * Admin dashboard
     *
     * @access public
     * @return void
     */
    public function admin_index()
    {
      
    }
    
    
    /**
     * Actions
     *
     * @access public
     * @return void
     */
    public function admin_actions($root = 'controllers')
    {
      //Get all roots
      $roots = $this->Acl->Aco->find('list',array(
        'conditions' => array(
          'parent_id' => 1,
          'alias !='  => $root
        ),
        'contain' => false,
        'fields' => array('alias')
      ));
    
      $conditions = array(
        'parent_id !='  => null,
        'foreign_key'   => null,
        'NOT' => array(
          'alias' => $roots
        )
      );
      
      $acos = $this->Acl->Aco->generatetreelist($conditions, '{n}.Aco.id', '{n}.Aco.alias');
      
      array_shift($acos);
      
      $this->set(compact('acos'));
    }
    
    
    /**
     * Actions update ACO
     *
     * @credit Croogo www.croogo.com
     * @access public
     * @return void
     */
    public function admin_actions_update()
    {
      $aco =& $this->Acl->Aco;
      $root = $aco->node('controllers');
      if (!$root) {
          $aco->create(array(
              'parent_id' => null,
              'model' => null,
              'alias' => 'controllers',
          ));
          $root = $aco->save();
          $root['Aco']['id'] = $aco->id;
      } else {
          $root = $root[0];
      }

      $controllerPaths = $this->AclManager->listControllers();
      
      foreach ($controllerPaths AS $controllerName => $controllerPath) {
          $controllerNode = $aco->node('controllers/'.$controllerName);
          if (!$controllerNode) {
              $aco->create(array(
                  'parent_id' => $root['Aco']['id'],
                  'model' => null,
                  'alias' => $controllerName,
              ));
              $controllerNode = $aco->save();
              $controllerNode['Aco']['id'] = $aco->id;
              $log[] = 'Created Aco node for '.$controllerName;
          } else {
              $controllerNode = $controllerNode[0];
          }

          $methods = $this->AclManager->listActions($controllerName, $controllerPath);
          foreach ($methods AS $method) {
              $methodNode = $aco->node('controllers/'.$controllerName.'/'.$method);
              if (!$methodNode) {
                  $aco->create(array(
                      'parent_id' => $controllerNode['Aco']['id'],
                      'model' => null,
                      'alias' => $method,
                  ));
                  $methodNode = $aco->save();
              }
          }
      }

      $this->redirect(array('action' => 'actions'));
    }
  
  }
  
  
?>
