<?php

  /**
   * Assets Controller
   *
   * @category Controller
   * @package  Opencamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class AssetsController extends AppController
  {
    /**
     * Models
     *
     * @access public
     * @var array
     */
    public $uses = array();
    
    /**
     * Uses
     *
     * @access public
     * @access public
     */
    public $actionMap = array(
      'image'  => '_read'
    );
    
    
    /**
     * Resize image in an account
     *
     * @access public
     * @return void
     */
    public function account_image()
    {
      $this->image(array(
        'dir' => ASSETS_DIR.DS.'accounts'.DS.$this->Authorization->read('Account.id')
      ));
      
      $this->render('image');
    }
    
    
    /**
     * Resize the image by url
     *
     * @todo Clean up this function
     * @access private
     * @return boolean
     */
    public function image($options = array())
    {
      //Options
      $_options = array(
        'cType' => 'resize',
        'id' => '',
        'dir' => null,
        'newWidth' => false,
        'newHeight' => false,
        'quality' => 75,
        'bgcolor' => false
      );
      $options = array_merge($_options,$options);
      
      //Setup
      $this->layout = false;
      
      $types = array(
        'resize',
        'resizeCrop',
        'crop'
      );
      
      //Sub directory and filename
      $passed = $this->params['pass'];
      
      $options['id'] = array_pop($passed);
      $options['id'] = preg_replace('/\.\.|\//s','',$options['id']);
      
      //Sub directories
      if(!empty($passed))
      {
        $options['dir'] .= DS.implode(DS,$passed);
      }
      
      //Type
      if(isset($this->params['named']['type']) && in_array($this->params['named']['type'],$types))
      {
        $options['cType'] = $this->params['named']['type'];
      }
      
      //Size
      if(isset($this->params['named']['size']) && preg_match('/(^[0-9]{1,4}|^)x([0-9]{1,4}$|$)/',$this->params['named']['size']))
      {
        $sizeArr = explode('x',$this->params['named']['size']);
        if(!empty($sizeArr[0])) { $options['newWidth'] = $sizeArr[0]; }
        if(!empty($sizeArr[1])) { $options['newHeight'] = $sizeArr[1]; }
      }
      
      //Quality
      if(isset($this->params['named']['quality']) && is_numeric($this->params['named']['quality']) && strlen($this->params['named']['quality']) <= 100)
      {
        $options['quality'] = $this->params['named']['quality'];
      }
      
      extract($options);
    
      //
      if(substr($dir,0,-1) != DS) { $dir .= DS; }
      
      $img = $dir . $id;
      list($oldWidth, $oldHeight, $type) = getimagesize($img); 
      $ext = $this->_imageTypeToExtension($type);
      
      //No sizes?
      if(!$options['newWidth'] && !$options['newHeight'])
      {
        $newWidth = $oldWidth;
        $newHeight = $oldHeight;
      }
      
      //Destination
      $dest = $dir . substr($id,0,strrpos($id,'.')) . '-' . md5(implode('::',$options)).'.'.$ext;
      
      //check to make sure that the file is writeable, if so, create destination image (temp image)
      if(!is_writeable($dir))
      {
        die("You must allow proper permissions for image processing. And the folder has to be writable.");
      }
      
      $this->set('filename',$id);
      $this->set('dest',$dest);
      $this->set('type',$type);
      $this->set('ext',$ext);
      
      //File exists, use cache
      if(file_exists($dest))
      {
        return;
      }
      
  
      switch ($cType){
        default:
        case 'resize':
          # Maintains the aspect ration of the image and makes sure that it fits
          # within the maxW(newWidth) and maxH(newHeight) (thus some side will be smaller)
          $widthScale = 2;
          $heightScale = 2;
          
          if($newWidth) $widthScale = 	$newWidth / $oldWidth;
          if($newHeight) $heightScale = $newHeight / $oldHeight;
          //debug("W: $widthScale  H: $heightScale<br>");
          if($widthScale < $heightScale) {
            $maxWidth = $newWidth;
            $maxHeight = false;							
          } elseif ($widthScale > $heightScale ) {
            $maxHeight = $newHeight;
            $maxWidth = false;
          } else {
            $maxHeight = $newHeight;
            $maxWidth = $newWidth;
          }
          
          if($maxWidth > $maxHeight){
            $applyWidth = $maxWidth;
            $applyHeight = ($oldHeight*$applyWidth)/$oldWidth;
          } elseif ($maxHeight > $maxWidth) {
            $applyHeight = $maxHeight;
            $applyWidth = ($applyHeight*$oldWidth)/$oldHeight;
          } else {
            $applyWidth = $maxWidth; 
              $applyHeight = $maxHeight;
          }
          //debug("mW: $maxWidth mH: $maxHeight<br>");
          //debug("aW: $applyWidth aH: $applyHeight<br>");
          $startX = 0;
          $startY = 0;
          //exit();
          break;
        case 'resizeCrop':
          // -- resize to max, then crop to center
          $ratioX = $newWidth / $oldWidth;
          $ratioY = $newHeight / $oldHeight;

          if ($ratioX < $ratioY) { 
            $startX = round(($oldWidth - ($newWidth / $ratioY))/2);
            $startY = 0;
            $oldWidth = round($newWidth / $ratioY);
            $oldHeight = $oldHeight;
          } else { 
            $startX = 0;
            $startY = round(($oldHeight - ($newHeight / $ratioX))/2);
            $oldWidth = $oldWidth;
            $oldHeight = round($newHeight / $ratioX);
          }
          $applyWidth = $newWidth;
          $applyHeight = $newHeight;
          break;
        case 'crop':
          // -- a straight centered crop
          $startY = ($oldHeight - $newHeight)/2;
          $startX = ($oldWidth - $newWidth)/2;
          $oldHeight = $newHeight;
          $applyHeight = $newHeight;
          $oldWidth = $newWidth; 
          $applyWidth = $newWidth;
          break;
      }
      
      
      switch($ext)
      {
        case 'gif' :
          $oldImage = imagecreatefromgif($img);
          break;
        case 'png' :
          $oldImage = imagecreatefrompng($img);
          break;
        case 'jpg' :
        case 'jpeg' :
          $oldImage = imagecreatefromjpeg($img);
          break;
        default :
          //image type is not a possible option
          return false;
          break;
      }
      
      //create new image
      $newImage = imagecreatetruecolor($applyWidth, $applyHeight);
      
      if($bgcolor):
      //set up background color for new image
        sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
        $newColor = ImageColorAllocate($newImage, $red, $green, $blue); 
        imagefill($newImage,0,0,$newColor);
      endif;
      
      //put old image on top of new image
      imagecopyresampled($newImage, $oldImage, 0,0 , $startX, $startY, $applyWidth, $applyHeight, $oldWidth, $oldHeight);
      
        switch($ext)
        {
          case 'gif' :
            imagegif($newImage, $dest, $quality);
            break;
          case 'png' :
            if($quality > 9) { $quality = $quality / 10; }
            imagepng($newImage, $dest, $quality);
            break;
          case 'jpg' :
          case 'jpeg' :
            imagejpeg($newImage, $dest, $quality);
            break;
          default :
            return false;
            break;
        }
      
      imagedestroy($newImage);
      imagedestroy($oldImage);
      
    }
    

    /**
     * Image type to extension
     * 
     * @param string $imagetype file extension
     * @access private
     * @return string
     */
    private function _imageTypeToExtension($imagetype)
    {
      if(empty($imagetype)) return false;
      
      switch($imagetype)
      {
        case IMAGETYPE_GIF    : return 'gif';
        case IMAGETYPE_JPEG    : return 'jpg';
        case IMAGETYPE_PNG    : return 'png';
        case IMAGETYPE_SWF    : return 'swf';
        case IMAGETYPE_PSD    : return 'psd';
        case IMAGETYPE_BMP    : return 'bmp';
        case IMAGETYPE_TIFF_II : return 'tiff';
        case IMAGETYPE_TIFF_MM : return 'tiff';
        case IMAGETYPE_JPC    : return 'jpc';
        case IMAGETYPE_JP2    : return 'jp2';
        case IMAGETYPE_JPX    : return 'jpf';
        case IMAGETYPE_JB2    : return 'jb2';
        case IMAGETYPE_SWC    : return 'swc';
        case IMAGETYPE_IFF    : return 'aiff';
        case IMAGETYPE_WBMP    : return 'wbmp';
        case IMAGETYPE_XBM    : return 'xbm';
        default                : return false;
      }
    }
    
    
    
  }
  

?>
