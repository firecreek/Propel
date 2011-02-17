<?php

  /**
   * Due Behavior
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class DueBehavior extends ModelBehavior
  {
    public $dueOptions = array();
    public $spacer = '----------------';

    
    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
      $this->dueOptions = array(
        array('name'=>__('Anytime',true),       'key'=>''),
        '',
        array('name'=>__('Today',true),         'key'=>'today'),
        array('name'=>__('Tomorrow',true),      'key'=>'tomorrow'),
        array('name'=>__('This week',true),     'key'=>'this-week'),
        array('name'=>__('Next week',true),     'key'=>'next-week'),
        array('name'=>__('Later',true),         'key'=>'later'),
        '',
        array('name'=>__('In the past',true),   'key'=>'past'),
        array('name'=>__('(No date set)',true), 'key'=>'no-date'),
      );
    }
    
    
    /**
     * Return due options
     *
     * @access public
     * @return void
     */
    public function dueOptions(&$model)
    {
      $output = array();
      
      foreach($this->dueOptions as $key => $due)
      {
        if(is_array($due))
        {
          $output[$due['key']] = $due['name'];
        }
        else
        {
          $output['_'.$key] = $this->spacer;
        }
      }
      
      return $output;
    }
    
    
    
    /**
     * Before find
     * 
     * @access public
     * @return string
     */
    public function beforeFind(&$model, $query)
    {
      if(isset($query['filter']['Due']) && !empty($query['filter']['Due']))
      {
        $query['recursive'] = 1;
        
        $conditions = array();
        $responsibleModel = $query['filter']['Due']['model'];
        $format = 'Y-m-d';
      
        switch($query['filter']['Due']['value'])
        {
          case 'today':
            $conditions[] = array(
              '{m}.deadline' => date($format),
            );
            break;
          case 'tomorrow':
            $conditions[] = array(
              '{m}.deadline' => date($format,strtotime('+1 day')),
            );
            break;
          case 'this-week':
            if(date('D') == 'Mon') { $startDate = mktime(0,0,0,date('m'),date('d'),date('Y')); }
            else { $startDate = strtotime('last Monday'); }
            $conditions[] = array(
              '{m}.deadline >= ' => date($format,$startDate),
              '{m}.deadline <= ' => date($format,strtotime('next Sunday')),
            );
            break;
          case 'next-week':
            $nextMonday = strtotime('next Monday');
            $conditions[] = array(
              '{m}.deadline >= ' => date($format,$nextMonday),
              '{m}.deadline <= ' => date($format,strtotime('next Sunday',$nextMonday)),
            );
            break;
          case 'later':
            $conditions[] = array(
              '{m}.deadline >= ' => date($format),
            );
            break;
          case 'past':
            $conditions[] = array(
              '{m}.deadline <= ' => date($format),
            );
            break;
          case 'no-date':
            $conditions[] = array(
              '{m}.deadline' => null
            );
            break;
        }
        
        //Sets
        foreach($this->dueOptions as $dueArr)
        {
          if(is_array($dueArr) && $dueArr['key'] == $query['filter']['Due']['value'])
          {
            $model->dueName = $dueArr['name'];
            break;
          }
        }
        
        //Exception for loading items
        if(isset($query['items']))
        {
          $iConditions = $this->_replaceModelAlias($conditions,$responsibleModel);
          $query['items']['conditions'] = array_merge($iConditions,$query['items']['conditions']);
        }
        
        //INNER joins
        $joinAlias = $responsibleModel.'Due';
        $query['contain'][$joinAlias] = array();
        $bConditions = $this->_replaceModelAlias($conditions,$joinAlias);
        
        $model->bindModel(array(
          'belongsTo' => array(
            $joinAlias => array(
              'className'   => $responsibleModel,
              'type'        => 'INNER',
              'foreignKey'  => false,
              'conditions'  => array_merge(array(
                $joinAlias.'.'.strtolower($model->alias).'_id = '.$model->alias.'.id',
              ),$bConditions)
            )
          )
        ),true);
      }
      
      return $query;
    }
    
    
    private function _replaceModelAlias($conditions,$alias)
    {
      $output = array();
    
      foreach($conditions as $condition)
      {
        foreach($condition as $key => $val)
        {
          $output[str_replace('{m}',$alias,$key)] = $val;
        }
      }
      
      return $output;
    }
    
    

  }

?>
