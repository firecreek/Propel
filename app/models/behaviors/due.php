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

    

    public function setup(&$model, $config = array())
    {
      $this->dueOptions = array(
        ''            => __('Anytime',true),
        '_0'          => '----------------',
        'today'       => __('Today',true),
        'tomorrow'    => __('Tomorrow',true),
        'this-week'   => __('This week',true),
        'next-week'   => __('Next week',true),
        'later'       => __('Later',true),
        '_1'          => '----------------',
        'past'        => __('In the past',true),
        'no-date'     => __('(No date set)',true),
      );
    }
    
    
    public function dueOptions(&$model)
    {
      return $this->dueOptions;
    }
    
    
    

  }

?>
