<?php

  /**
   * Responsible Behavior
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class ResponsibleBehavior extends ModelBehavior
  {

    

    public function setup(&$model, $config = array())
    {
    }
    
    
    
    public function getResponsibleName(&$model,$modelAlias,$modelId)
    {
      $record = ClassRegistry::init($modelAlias)->find('first',array(
        'conditions' => array('id'=>$modelId),
        'recursive'  => -1
      ));
      
      $name = false;
      
      switch($modelAlias)
      {
        case 'Company':
          $name = $record[$modelAlias]['name'];
          break;
        case 'Person':
          $name = $record[$modelAlias]['full_name'];
          break;
      }
      
      return $name;
    }
    
    
    /**
     * Before find
     * 
     * @access public
     * @return string
     */
    public function beforeFind(&$model, $query)
    {
      //Contain as array
      if(!isset($query['contain']))
      {
        $query['contain'] = array();
      }
      elseif(is_string($query['contain']))
      {
        $query['contain'] = array($query['contain']);
      }
      elseif(!is_array($query['contain']))
      {
        $query['contain'] = array();
      }
    
      //Responsible Filter
      if(isset($query['filter']['Responsible']) && !empty($query['filter']['Responsible']))
      {
        $query['recursive'] = 1;
        
        //Model to use
        $responsibleValue = $query['filter']['Responsible']['value'];
        $responsibleModel = $query['filter']['Responsible']['model'];
      
        //Whom
        if($responsibleValue == 'nobody')
        {
          //Nobody
          $modelAlias       = null;
          $modelId          = null;
          $responsibleName  = 'Nobody';
        }
        elseif($responsibleValue == 'self')
        {
          //Self
          $modelAlias       = 'Person';
          $modelId          = $model->authRead('Person.id');
          $responsibleName  = $model->{$responsibleModel}->getResponsibleName($modelAlias,$modelId);
        }
        else
        {
          //Specified
          $split            = explode('_',$this->data['Todo']['responsible']);
          $modelAlias       = Inflector::classify($split[0]);
          $modelId          = $split[1];
          $responsibleName  = $model->{$responsibleModel}->getResponsibleName($modelAlias,$modelId);
        }
        
        //Exception for loading items
        if(isset($query['items']))
        {
          $query['items']['conditions'][$responsibleModel.'.responsible_model'] = $modelAlias;
          $query['items']['conditions'][$responsibleModel.'.responsible_id'] = $modelId;
        }
        
        //Sets
        $this->responsibleName = $responsibleName;
        
        //Contain join
        $joinAlias = $responsibleModel.'Responsible';
        $query['contain'][] = $joinAlias;
        
        //INNER joins
        $model->bindModel(array(
          'belongsTo' => array(
            $joinAlias => array(
              'className'   => $responsibleModel,
              'type'        => 'INNER',
              'foreignKey'  => false,
              'conditions'  => array(
                $joinAlias.'.'.strtolower($this->alias).'_id = '.$this->alias.'.id',
                $joinAlias.'.responsible_model' => $modelAlias,
                $joinAlias.'.responsible_id' => $modelId,
              )
            )
          )
        ),true);
        
      }
      
      
    
      //Responsible Alias
      if(isset($query['contain']))
      {
        $find = array_search('Responsible',$query['contain']);
        
        if(!$find && isset($query['contain']['Responsible']))
        {
          $find = 'Responsible';
        }
      }
      
      if(isset($find) && $find !== false)
      {
        $model->bindModel(array(
          'belongsTo' => array(
            'ResponsiblePerson' => array(
              'className' => 'Person',
              'foreignKey' => false,
              'fields' => array('id','full_name'),
              'conditions' => array(
                $model->alias.'.responsible_id = ResponsiblePerson.id',
                $model->alias.'.responsible_model = "Person"',
              )
            ),
            'ResponsibleCompany' => array(
              'className' => 'Company',
              'foreignKey' => false,
              'fields' => array('id','name'),
              'conditions' => array(
                $model->alias.'.responsible_id = ResponsibleCompany.id',
                $model->alias.'.responsible_model = "Company"',
              )
            )
          )
        ),true);
        
        unset($query['contain'][$find]);
        
        $query['contain'][] = 'ResponsiblePerson';
        $query['contain'][] = 'ResponsibleCompany';
      }
      
      return $query;
    }
    
    
    
    public function afterFind(&$model, $results, $primary)
    {
      //Map data back to a 'virtual table'
      if(!empty($results) && isset($results[0]['ResponsiblePerson']))
      {
        foreach($results as $key => $val)
        {
          $modelAlias = null;
          $foreignId = null;
          $name = null;
          
          if(!empty($val['ResponsiblePerson']['id']))
          {
            $modelAlias = 'Person';
            $foreignId = $val['ResponsiblePerson']['id'];
            $name = $val['ResponsiblePerson']['full_name'];
          }
          elseif(!empty($val['ResponsibleCompany']['id']))
          {
            $modelAlias = 'Company';
            $foreignId = $val['ResponsibleCompany']['id'];
            $name = $val['ResponsibleCompany']['name'];
          }
          
          if(isset($foreignId))
          {
            $results[$key][$model->alias]['responsible'] = strtolower($modelAlias).'_'.$foreignId;
          
            $results[$key]['Responsible'] = array(
              'model'       => $modelAlias,
              'foreign_id'  => $foreignId,
              'name'        => $name,
            );
            
            unset($results[$key]['ResponsiblePerson']);
            unset($results[$key]['ResponsibleCompany']);
          }
          else
          {
            $results[$key][$model->alias]['responsible'] = null;
          }
          
        }
      }
      
      return $results;
    }
    
    

    public function beforeSave(&$model)
    {
    
      if(isset($model->data[$model->alias]['responsible']))
      {
        if($model->data[$model->alias]['responsible'] == 'self')
        {
          $personId = Set::extract($_SESSION, 'AuthAccount.Person.id');
          
          $model->data[$model->alias]['responsible_model'] = 'Person';
          $model->data[$model->alias]['responsible_id'] = $personId;
        }
        elseif(substr($model->data[$model->alias]['responsible'],0,6) == 'person')
        {
          $model->data[$model->alias]['responsible_model'] = 'Person';
          $model->data[$model->alias]['responsible_id'] = substr($model->data[$model->alias]['responsible'],7);
        }
        elseif(substr($model->data[$model->alias]['responsible'],0,7) == 'company')
        {
          $model->data[$model->alias]['responsible_model'] = 'Company';
          $model->data[$model->alias]['responsible_id'] = substr($model->data[$model->alias]['responsible'],8);
        }
      }
      
      return true;
    }

  }

?>
