<?php

  App::import('Sanitize');

  /**
   * Searchable Behavior
   *
   * @category Behavior
   * @package  Opencamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class SearchableBehavior extends ModelBehavior
  {
    public $defaults = array(
      'title' => 'title',
      'description' => 'description',
      'keywords' => array(),
      'extra' => array()
    );
    
    public $settings = array();

    
    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $settings = array())
    {
      if(!isset($this->settings[$model->alias]))
      {
        $this->settings[$model->alias] = $this->defaults;
      }
      $this->settings[$model->alias] = array_merge($this->settings[$model->alias], (array)$settings);

      $this->SearchIndex = ClassRegistry::init('SearchIndex');
    }
    
    
    /**
     * Set search setting
     *
     * @access public
     * @return boolean
     */
    public function searchSetting(&$model,$field,$value)
    {
      $this->settings[$model->alias][$field] = $value;
      
      return true;
    }
    
    
    /**
     * After save
     *
     * @access public
     * @return void
     */
    public function afterSave(&$model,$created)
    {
      $this->searchIndex($model);
    }
    
    
    /**
     * Before delete
     *
     * @access public
     * @return boolean
     */
    public function beforeDelete(&$model)
    {
      if(isset($model->id) && is_numeric($model->id))
      {
        $model->data[$model->alias]['id'] = $model->id;
      }
      
      return true;
    }
    
    
    /**
     * After delete
     *
     * @access public
     * @return void
     */
    public function afterDelete(&$model)
    {
      if(isset($model->data[$model->alias]['id']))
      {
        $this->SearchIndex->delete($model->data[$model->alias]['id']);
      }
    }
    
    
    /**
     * Index a model record
     *
     * @access public
     * @return boolean
     */
    public function searchIndex(&$model)
    {
      //Load this record
      $record = $model->find('first',array(
        'conditions' => array($model->alias.'.id'=>$model->id),
        'contain' => false
      ));
      
      if(empty($record))
      {
        return false;
      }
    
      //Record exists in search index already
      if($searchRecord = $this->SearchIndex->find('first',array(
        'conditions' => array(
          'model' => $model->alias,
          'model_id' => $model->id
        ),
        'fields' => array('id')
      )))
      {
        $id = $searchRecord['SearchIndex']['id'];
      }
      
      //Build data
      $title = $this->_buildField($model,$this->settings[$model->alias]['title'],$record);
      $description = $this->_buildField($model,$this->settings[$model->alias]['description'],$record);
      
      //Extra fields
      $extra = array();
      if(!empty($this->settings[$model->alias]['extra']))
      {
        foreach($this->settings[$model->alias]['extra'] as $key => $val)
        {
          $extra[$key+1] = $this->_buildField($model,$val,$record);
        }
      }
      
      //Keywords
      $keywordsArr = array();
      $keywordsArr[] = $title;
      $keywordsArr[] = $description;
      
      if(!empty($this->settings[$model->alias]['keywords']))
      {
        foreach($this->settings[$model->alias]['description'] as $field)
        {
          $keywordsArr[] = $record[$model->alias][$field];
        }
      }
      
      $keywords = implode(' ',$keywordsArr);
      
      //Account ID
      $projectId = isset($record[$model->alias]['project_id']) ? $record[$model->alias]['project_id'] : null;
      $accountId = isset($record[$model->alias]['account_id']) ? $record[$model->alias]['account_id'] : null;
      
      if(empty($accountId) && !empty($projectId) && $model->Project->hasField('account_id'))
      {
        $model->Project->id = $projectId;
        $accountId = $model->Project->field('account_id');
      }
      
      //Save
      $data = array(
        'id'            => isset($id) ? $id : 0,
        'model'         => $model->alias,
        'model_id'      => $model->id,
        'title'         => $title,
        'description'   => $description,
        'keywords'      => $keywords,
        'extra1'        => isset($extra[1]) ? $extra[1] : null,
        'extra2'        => isset($extra[2]) ? $extra[2] : null,
        'extra3'        => isset($extra[3]) ? $extra[3] : null,
        'model_created' => $record[$model->alias]['created'],
        'private'       => isset($record[$model->alias]['private']) ? $record[$model->alias]['private'] : false,
        'person_id'     => isset($record[$model->alias]['person_id']) ? $record[$model->alias]['person_id'] : null,
        'account_id'    => $accountId,
        'project_id'    => $projectId,
      );
      
      return $this->SearchIndex->save($data);
    }
    
    
    /**
     * Build field
     *
     * @access private
     * @return boolean
     */
    private function _buildField(&$model,$data,$record)
    {
      if(!$data)
      {
        return null;
      }
      
      if(is_string($data))
      {
        $value = $record[$model->alias][$data];
      }
      else
      {
        $associatedModel = ClassRegistry::init($data['model']);
      
        $associatedModel->recursive = -1;
        $associatedModel->id = $record[$model->alias][$data['associatedKey']];
        $value = $associatedModel->field($data['field']);
      }
      
      //Strip HTML
      $value = Sanitize::html($value,array('remove'=>true));
      
      return $value;
    }
    
    

  }

?>
