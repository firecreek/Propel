<?php

  /**
   * Private Behavior
   *
   * @category Behavior
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class PrivateBehavior extends ModelBehavior
  {
    
    public function setup(&$model, $config = array())
    {
    }
    
    
    
    /**
     * Before find
     * 
     * @access public
     * @return string
     */
    public function beforeFind(&$model, $query)
    {      
    
      if($query['fields'] == 'COUNT(*) AS `count`')
      {
        return $query;
      }
      
      //Only do if Person is in contain
      if(isset($query['contain']['Person']))
      {      
        $query['conditions'] = array(
          'AND' => array(
            $query['conditions'],
            array(
              'OR' => array(
                array($model->alias.'.private' => 0),
                array(
                  'AND' => array(
                    $model->alias.'.private' => 1,
                    'Person.company_id' => $model->authRead('Person.Company.id')
                  )
                ),
                array(
                  'AND' => array(
                    $model->alias.'.private' => 1,
                    $model->authRead('Person.Company.can_see_private').' = 1'
                  )
                )
              )
            )
          )
        );
      }
      
      return $query;
    }
    

  }

?>
