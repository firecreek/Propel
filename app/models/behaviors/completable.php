<?php

  /**
   * Completeable Behavior
   *
   * @category Behavior
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class CompletableBehavior extends ModelBehavior
  {

    
    /**
     * Setup
     *
     * @access public
     * @return void
     */
    public function setup(&$model, $config = array())
    {
    
    }
    
    
    /**
     * Set to completed
     *
     * @access public
     * @return void
     */
    public function complete(&$model)
    {
      return $model->updateAll(
        array(
          'completed' => 1,
          'completed_date' => '"'.date('Y-m-d').'"',
          'completed_person_id' => $model->personId
        ),
        array($model->alias.'.id'=>$model->id)
      );
    }
    
    
    /**
     * Set to pending
     *
     * @access public
     * @return void
     */
    public function pending(&$model)
    {
      return $model->updateAll(
        array(
          'completed' => 0,
          'completed_date' => null,
          'completed_person_id' => null
        ),
        array($model->alias.'.id'=>$model->id)
      );
    }
    
    

  }

?>
