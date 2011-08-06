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
     * Allow access
     *
     * @access public
     * @return boolean
     */
    public function allow(&$model,&$aco,$data = array())
    {
      if(method_exists($model,'aro'))
      {
        $aro = $model->aro($aco,true,$data);
      }
      else
      {
        $aro = $model;
      }
      
      if($this->Acl->allow($aro, $aco))
      {
        $this->clearAuthCache($model);
        return true;
      }
      return false;
    }
    
    
    
    /**
     * Deny access
     *
     * @access public
     * @return boolean
     */
    public function deny(&$model,&$aco)
    {
      if(method_exists($model,'aro'))
      {
        $aro = $model->aro($aco);
      }
      else
      {
        $aro = $model;
      }
      
      if($this->Acl->deny($aro, $aco))
      {
        $this->clearAuthCache($model);
        return true;
      }
      return false;
    }
    

    /**
     * Remove ACO based on a set
     *
     * @param string $path
     * @access public
     * @return boolean
     */
    public function delete(&$model,&$aco)
    {
      if(method_exists($model,'aro'))
      {
        $aro = $model->aro($aco);
      }
      else
      {
        $aro = $model;
      }
      
      //Aco
      $acoNode = $this->Acl->Aco->node($aco);
      $acoId = $acoNode[0]['Aco']['id'];
      
      //Aro
      $acoNode = $this->Acl->Aro->node($aro);
      $aroId = $acoNode[0]['Aro']['id'];
      
      $deleted = $this->Acl->Aco->Permission->deleteAll(array(
        'Permission.aco_id' => $acoId,
        'Permission.aro_id' => $aroId
      ));
      
      if($deleted)
      {
        if(method_exists($model,'afterAroDelete'))
        {
          $model->afterAroDelete($model,$aco);
        }
        
        $this->clearAuthCache($model);
        return true;
      }
      
      return false;
    }
    
    
    /**
     * Check access
     *
     * @access public
     * @return boolean
     */
    public function check(&$model,&$aco)
    {
      if(method_exists($model,'aro'))
      {
        $aro = $model->aro($aco);
      }
      else
      {
        $aro = $model;
      }
      
      return $this->Acl->check($aro, $aco);
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
     * @credit Croogo www.croogo.com
     * @return array
     */
    public function listControllers()
    {
      $controllerPaths = array();

      // app/controllers
      $this->folder->path = APP.'controllers'.DS;
      $controllers = $this->folder->read();
      foreach ($controllers['1'] AS $c) {
          if (substr($c, strlen($c) - 4, 4) == '.php') {
              $cName = Inflector::camelize(str_replace('_controller.php', '', $c));
              $controllerPaths[$cName] = APP.'controllers'.DS.$c;
          }
      }

      // plugins/*/controllers/
      $this->folder->path = APP.'plugins'.DS;
      $plugins = $this->folder->read();
      foreach ($plugins['0'] AS $p) {
          if ($p != 'install') {
              $this->folder->path = APP.'plugins'.DS.$p.DS.'controllers'.DS;
              $pluginControllers = $this->folder->read();
              foreach ($pluginControllers['1'] AS $pc) {
                  if (substr($pc, strlen($pc) - 4, 4) == '.php') {
                      $pcName = Inflector::camelize(str_replace('_controller.php', '', $pc));
                      $controllerPaths[$pcName] = APP.'plugins'.DS.$p.DS.'controllers'.DS.$pc;
                  }
              }
          }
      }

      return $controllerPaths;
    }


    /**
     * List actions of a particular Controller.
     *
     * @credit Croogo www.croogo.com
     * @param string  $name Controller name (the name only, without having Controller at the end)
     * @param string  $path full path to the controller file including file extension
     * @param boolean $all  default is false. it true, private actions will be returned too.
     * @return array
     */
    public function listActions($name, $path)
    {
      // base methods
      if (strpos($path, APP.'plugins') !== false) {
          $plugin = $this->getPluginFromPath($path);
          $pacName = Inflector::camelize($plugin) . 'AppController'; // pac - PluginAppController
          $pacPath = APP.'plugins'.DS.$plugin.DS.$plugin.'_app_controller.php';
          App::import('Controller', $pacName, null, null, $pacPath);
          $baseMethods = get_class_methods($pacName);
      } else {
          $baseMethods = get_class_methods('AppController');
      }

      $controllerName = $name.'Controller';
      App::import('Controller', $controllerName, null, null, $path);
      $methods = get_class_methods($controllerName);

      // filter out methods
      foreach ($methods AS $k => $method) {
          if (strpos($method, '_', 0) === 0) {
              unset($methods[$k]);
              continue;
          }
          if (in_array($method, $baseMethods)) {
              unset($methods[$k]);
              continue;
          }
      }

      return $methods;
    }


    /**
     * Get plugin name from path
     *
     * @credit Croogo www.croogo.com
     * @param string $path file path
     * @return string
     */
    public function getPluginFromPath($path)
    {
      $pathE = explode(DS, $path);
      $pluginsK = array_search('plugins', $pathE);
      $pluginNameK = $pluginsK + 1;
      $plugin = $pathE[$pluginNameK];

      return $plugin;
    }
    
    
    
  }
  
?>
