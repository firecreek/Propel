<?php

  /**
   * Grant Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class Grant extends AppModel
  {
    public $name = 'Grant';
    
    public $actsAs = array('Containable');
    
    public $hasMany = array(
      'GrantSet'
    );
    
  }
  
?>
