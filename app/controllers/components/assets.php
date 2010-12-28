<?php

  /**
   * Assets Component
   *
   * @category Component
   * @package  Opencamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AssetsComponent extends Object
  {
    /**
     * Controller object
     *
     * @access public
     * @var object
     */
    public $controller = null;
    
    /**
     * Errors
     *
     * @access public
     * @var array
     */
    public $errors = array();
    
    /**
     * Components used
     *
     * @access public
     * @var object
     */
    public $components = array('Authorization');
    

    /**
     * Initialize component
     *
     * @param object $controller
     * @param array $settings
     * @access public
     * @return void
     */
    public function initialize(&$controller, $settings = array())
    {
      $this->controller =& $controller;
    }
    

    /**
     * Save file
     *
     * @access public
     * @return void
     */
    public function save($type,$data,$options = array())
    {
      //Settings
      $_options = array(
        'convert' => false
      );
      $options = array_merge($_options,$options);
      
      //Validation
      if(empty($data['tmp_name']))
      {
        $this->errors[] = __('File could not be uploaded, please try again',true);
        return false;
      }
      
      //Account id?
      if(!isset($options['accountId']))
      {
        $options['accountId'] = $this->Authorization->read('Account.id');
      }
      
      //Filename
      $ext = substr($data['name'],strrpos($data['name'],'.')+1);
      $name = substr($data['name'],0,strrpos($data['name'],'.'));
      $newfilename = md5(microtime()).rand(1000,9999).'.'.$ext;
      
      if(!isset($options['filename']))
      {
        $options['filename'] = $newfilename;
      }
      else
      {
        $newExt = substr($options['filename'],strrpos($options['filename'],'.')+1);
        if($newExt != $ext) { $options['convert'] = true; }
        
        $options['convertFilename'] = $options['filename'];
        $options['filename'] = $newfilename;
      }
      
      //Create directory name
      $dir = ASSETS_DIR;
      
      if(isset($options['accountId']) && is_numeric($options['accountId']))
      {
        $dir .= DS.'accounts'.DS.$options['accountId'];
      }
      
      $dir .= DS.$type;
      
      //Check dir
      $this->checkDir($dir);
      
      //Save
      move_uploaded_file($data['tmp_name'],$dir.DS.$options['filename']);
      
      //Convert this file?
      if($options['convert'])
      {
        if(!$this->convert($dir.DS.$options['filename'],$dir.DS.$options['convertFilename']))
        {
          //Error
          $this->errors[] = __('Failed to convert file',true);
          return false;
        }
        else
        {
          unlink($dir.DS.$options['filename']);
          $options['filename'] = $options['convertFilename'];
        }
      }
      
      //Checks
      if(!file_exists($dir.DS.$options['filename']))
      {
        $this->errors[] = __('Failed to create image, try again',true);
        return false;
      }
    
      return true;
    }
    
    
    /**
     * Check / create directory
     *
     * @access public
     * @return boolean
     */
    public function checkDir($dir)
    {
      if(!is_dir($dir))
      {
        return mkdir($dir,0700);
      }
      
      return true;
    }
    
    
    /**
     * Convert image
     *
     * @access public
     * @return boolean
     */
    public function convert($source, $destination)
    {
      $oldExt = substr($source,strrpos($source,'.')+1);
      $newExt = substr($destination,strrpos($destination,'.')+1);
      
      list($width, $height, $type) = getimagesize($source); 
      
      switch($oldExt)
      {
        case 'gif' :
          $oldImage = imagecreatefromgif($source);
          break;
        case 'png' :
          $oldImage = imagecreatefrompng($source);
          break;
        case 'jpg' :
        case 'jpeg' :
          $oldImage = imagecreatefromjpeg($source);
          break;
      }
      
      //Create new
      $newImage = imagecreatetruecolor($width, $height);
      imagecopy($newImage, $oldImage, 0, 0, 0, 0, $width, $height);
    
      switch($newExt)
      {
        case 'gif' :
          imagegif($newImage, $destination, 100);
          break;
        case 'png' :
          imagepng($newImage, $destination, 9);
          break;
        case 'jpg':
        case 'jpeg':
          imagejpeg($newImage, $destination, 100);
          break;
        default :
          return false;
          break;
      }
      
      imagedestroy($newImage);
      imagedestroy($oldImage);
      
      return file_exists($destination) ? true : false;
    }
    
    
    
  }
  
?>
