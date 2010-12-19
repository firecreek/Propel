<?php

  /**
   * GrantSet Model
   *
   * @category Model
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class GrantSet extends AppModel
  {
    public $name = 'GrantSet';
    
    public $useTable = 'grants_sets';
    
    public $actsAs = array('Containable');
    
    public $belongsTo = array(
      'Grant'
    );
    
  }
  
?>
