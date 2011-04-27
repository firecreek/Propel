<?php

  App::import('Lib', 'Textile');

  /**
   * Textile Helper
   *
   * @category Helper
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class TextileHelper extends AppHelper
  {
    
    /**
     * Check permission
     *
     * @param string $alias ACO alias
     * @param string $type create, update, permission type
     * @access public
     * @return boolean
     */
    public function parse($text)
    {
      $textile = new Textile(); 
      return $textile->TextileThis($text); 
    }
    
  }
  
?>
