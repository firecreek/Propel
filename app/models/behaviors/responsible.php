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

    public function beforeSave(&$model)
    {
      if(isset($model->data['Milestone']['responsible']))
      {
        if($model->data['Milestone']['responsible'] == 'self')
        {
          $model->data['Milestone']['responsible_model'] = 'Person';
          $model->data['Milestone']['responsible_id'] = $model->Authorization->read('Person.id');
        }
        elseif(substr($model->data['Milestone']['responsible'],0,6) == 'person')
        {
          $model->data['Milestone']['responsible_model'] = 'Person';
          $model->data['Milestone']['responsible_id'] = substr($model->data['Milestone']['responsible'],7);
        }
        elseif(substr($model->data['Milestone']['responsible'],0,7) == 'company')
        {
          $model->data['Milestone']['responsible_model'] = 'Company';
          $model->data['Milestone']['responsible_id'] = substr($model->data['Milestone']['responsible'],8);
        }
      }
      
      return true;
    }

  }

?>
