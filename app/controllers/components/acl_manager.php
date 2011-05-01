<?php

  /**
   * Acl Manager Component
   *
   * @category Component
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AclManagerComponent extends Object
  {
    /**
     * Controller object
     *
     * @access public
     * @var object
     */
    public $controller = null;
    
    /**
     * Components used
     *
     * @access public
     * @var object
     */
    public $components = array('Acl');
    

    /**
     * Initialize component
     *
     * @param object $controller
     * @param array $settings
     * @access public
     * @return void
     */
    public function initialize(&$controller, $settings = array())
    {
      $this->controller =& $controller;
      
      App::import('Core', 'File');
      $this->folder = new Folder;
    }
    

    /**
     * Account ACL
     *
     * @access public
     * @return void
     */
    public function create($alias, $foreignId)
    {
      //Get account ACO root
      $root = $this->root($alias);
      
      //Check if company has a root
      if(!$acoRoot = $this->root($alias.'/'.$foreignId))
      {
        $this->Acl->Aco->create(array(
          'parent_id'       => $root,
          'model'           => Inflector::camelize($alias),
          'foreign_key'     => $foreignId,
          'alias'           => $foreignId
        ));
        $this->Acl->Aco->save();
        $acoRoot = $this->Acl->Aco->id;
      }
      
      //Get all controllers
      $controllers = $this->listControllers();
      
      foreach($controllers as $name => $file)
      {
        if(!$this->root($alias.'/'.$foreignId.'/'.$name))
        {
          $this->Acl->Aco->create(array(
            'parent_id'       => $acoRoot,
            'model'           => null,
            'foreign_key'     => null,
            'alias'           => $name
          ));
          $this->Acl->Aco->save();
        }
      }
      
    }
    
    
    /**
     * Root ACO
     *
     * @param string $path
     * @access public
     * @return int
     */
    public function root($path)
    {
      $root = $this->Acl->Aco->node('opencamp/'.$path);
      return !empty($root) ? Set::extract($root,'0.Aco.id') : false;
    }
    
    
    /**
     * Allow access to an ACO based on a set
     *
     * @param string $path
     * @access public
     * @return int
     */
    public function allow(&$model, $path, $foreignId, $options = array())
    {
      $_options = array(
        'delete' => false
      );
      $options = array_merge($_options,$options);
      
      //Delete previous if exists
      if($options['delete'])
      {
        $this->delete($model, $path, $foreignId, null, array('all'=>true));
      }
    
      //Access to main ACO
      $this->Acl->allow($model, 'opencamp/'.$path.'/'.$foreignId);
    
      //Access to controllers via sets
      if(isset($options['set']))
      {
        $Grant = ClassRegistry::init('Grant','model');
          
        $record = $Grant->find('first',array(
          'conditions' => array(
            'Grant.alias' => $options['set'],
            'Grant.aco_alias' => $path
          )
        ));
        
        foreach($record['GrantSet'] as $grant)
        {
          $actions = array();
          foreach($grant as $key => $val)
          {
            if(substr($key,0,1) == '_' && $val == true)
            {
              $actions[] = $key;
            }
          }
          $this->Acl->allow($model, 'opencamp/'.$path.'/'.$foreignId.'/'.$grant['acos_alias'],$actions);
        }
      }
      
      $this->clearAuthCache($model);
    }
    
    
    /**
     * Remove ACO based on a set
     *
     * @param string $path
     * @access public
     * @return boolean
     */
    public function delete(&$model, $path, $foreignId, $action = null, $options = array())
    {
      $_options = array(
        'all' => false
      );
      $options = array_merge($_options,$options);
      
      //Get aco
      $fullpath = 'opencamp/'.$path.'/'.$foreignId;
      if(!empty($action)) { $fullpath .= '/'.$action; }
      
      $root = $this->Acl->Aco->node($fullpath);
      $acoId = $root[0]['Aco']['id'];
      
      //Root permission
      $root = $this->Acl->Aco->Permission->find('first',array(
        'conditions' => array(
          'Aro.model'         => $model->alias,
          'Aro.foreign_key'   => $model->id,
          'Permission.aco_id' => $acoId
        )
      ));
      
      //Delete children
      if($options['all'])
      {
        $this->Acl->Aco->Permission->deleteAll(array(
          'Aro.model'         => $model->alias,
          'Aro.foreign_key'   => $model->id,
          'Aco.parent_id'     => $root['Aco']['id']
        ));
      }
      
      $this->clearAuthCache($model);
    
      return $this->Acl->Aco->Permission->delete($root['Permission']['id']);
    }
    
    
    /**
     * Check permission
     *
     * @param string $path
     * @access public
     * @return int
     */
    public function check(&$model, $path, $foreignId, $action = null, $options = array())
    {
      //Get aco
      if($action)
      {
        $root = $this->Acl->Aco->node('opencamp/'.$path.'/'.$foreignId.'/'.$action);
      }
      else
      {
        $root = $this->Acl->Aco->node('opencamp/'.$path.'/'.$foreignId);
      }
      $acoId = $root[0]['Aco']['id'];
      
      //Permissions
      $conditions = array();
      if(isset($options['permission']))
      {
        foreach($options['permission'] as $key)
        {
          $conditions['_'.$key] = true;
        }
      }
      
      return $this->Acl->Aco->Permission->find('count',array(
        'conditions' => array_merge(array(
          'Aro.model' => $model->alias,
          'Aro.foreign_key' => $model->id,
          'Permission.aco_id' => $acoId
        ),$conditions)
      ));
    }
    
    
    /**
     * Cache Aco Node
     *
     * @access public
     * @return array
     */
    public function acoNode($path)
    {
      $cacheKey = 'node_aco_'.md5($path);
    
      if(!$node = Cache::read($cacheKey,'acl'))
      {
        $node = $this->Acl->Aco->node($path);
        Cache::write($cacheKey,$node,'acl');
      }
      
      return $node;
    }
    

    /**
     * List all controllers (including plugin controllers)
     *
     * From Croogo AclGenerator
     *
     * @access public
     * @return array
     */
    public function clearAuthCache(&$model)
    {
      $model->deleteCachedFiles(array(
        'prefix' => array('permission','people','person','company','companies')
      ));
    }
    

    /**
     * List all controllers (including plugin controllers)
     *
     * From Croogo AclGenerator
     *
     * @access public
     * @return array
     */
    public function listControllers()
    {
      $controllerPaths = array();

      // app/controllers
      $this->folder->path = APP.'controllers'.DS;
      $controllers = $this->folder->read();
      foreach ($controllers['1'] AS $c) {
          if (strstr($c, '.php')) {
              $cName = Inflector::camelize(str_replace('_controller.php', '', $c));
              $controllerPaths[$cName] = APP.'controllers'.DS.$c;
          }
      }

      // plugins/*/controllers/
      /*$this->folder->path = APP.'plugins'.DS;
      $plugins = $this->folder->read();
      foreach ($plugins['0'] AS $p) {
          if ($p != 'install') {
              $this->folder->path = APP.'plugins'.DS.$p.DS.'controllers'.DS;
              $pluginControllers = $this->folder->read();
              foreach ($pluginControllers['1'] AS $pc) {
                  if (strstr($pc, '.php')) {
                      $pcName = Inflector::camelize(str_replace('_controller.php', '', $pc));
                      $controllerPaths[$pcName] = APP.'plugins'.DS.$p.DS.'controllers'.DS.$pc;
                  }
              }
          }
      }*/

      return $controllerPaths;
    }
    
    
    
  }
  
?>
