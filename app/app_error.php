<?php

  /**
   * App Error
   *
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
   */
  class AppError extends ErrorHandler
  {
    /**
     * Bad URL
     *
     * Something in the URL is incorrect
     *
     * @access public
     * @return void
     */
    public function badUrl($params)
    {
      $this->_outputMessage('bad_url');
    }
    
    
    /**
     * Not Logged in
     *
     * @access public
     * @return void
     */
    public function notLoggedIn($params)
    {
      $this->_outputMessage('not_logged_in');
    }


    /**
     * Account not found
     *
     * Accessing URL: /unknown-account-name/*
     *
     * @access public
     * @return void
     */
    public function accountNotFound($params)
    {
      $this->_outputMessage('account_not_found');
    }


    /**
     * Prefix not found
     *
     * Prefix for loading permissions not found, e.g. account_, project_
     *
     * @access public
     * @return void
     */
    public function prefixNotFound($params)
    {
      $this->_outputMessage('prefix_not_found');
    }


    /**
     * Person not found
     *
     * Authorization can't find the current person
     * Person may have been deleted on the account while logged in
     *
     * @access public
     * @return void
     */
    public function personNotFound($params)
    {
      $this->_outputMessage('person_not_found');
    }


    /**
     * Person no ARO
     *
     * Person has no ARO records. The access was probably removed
     *
     * @access public
     * @return void
     */
    public function personNoAro($params)
    {
      $this->_outputMessage('person_no_aro');
    }


    /**
     * Bad CRUD access
     *
     * User has no access to this area as specified by CRUD
     * The user might not have delete access but called the delete URL
     *
     * @access public
     * @return void
     */
    public function badCrudAccess($params)
    {
      $this->_outputMessage('bad_crud_access');
    }
    

    /**
     * Record not found
     *
     * Record passed to the method was not found
     *
     * @access public
     * @return void
     */
    public function recordNotFound($params)
    {
      $this->_outputMessage('record_not_found');
    }


    /**
     * Record wrong prefix
     *
     * Current prefix (e.g. project) does not match in fields assocated key (e.g. project_id)
     *
     * @access public
     * @return void
     */
    public function recordWrongPrefix($params)
    {
      $this->_outputMessage('record_wrong_prefix');
    }


    /**
     * Record is private
     *
     * Person is attempting to access a private record and they are not
     * in the same company of the owner
     *
     * @access public
     * @return void
     */
    public function recordIsPrivate($params)
    {
      $this->_outputMessage('record_is_private');
    }


    /**
     * Record wrong account
     *
     * The accessed record belongs to a different account
     *
     * @access public
     * @return void
     */
    public function recordWrongAccount($params)
    {
      $this->_outputMessage('record_wrong_account');
    }


    /**
     * Permission denied
     *
     * Generic error when permission was denied
     *
     * @access public
     * @return void
     */
    public function permissionDenied($params)
    {
      $this->_outputMessage('permission_denied');
    }


    /**
     * Unknown ACL error
     *
     * Problem somewhere in acl_filter
     *
     * @access public
     * @return void
     */
    public function unknownAclError($params)
    {
      $this->_outputMessage('unknown_acl_error');
    }


    /**
     * Black Hole
     *
     * User accessed something they are refused
     *
     * @access public
     * @return void
     */
    public function blackHole($params)
    {
      $this->_outputMessage('black_hole');
    }


    /**
     * Invalid inviation token
     *
     * @access public
     * @return void
     */
    public function invitationInvalid($params)
    {
      $this->_outputMessage('invitation_invalid');
    }
    
    
    /**
     * Output message
     * 
     * This is a copy from cakephp core error.php supporting js and ajax requests
     *
     * @param string $template
     * @access private
     * @return void
     */
    public function _outputMessage($template)
    {
      $this->controller->layout = 'error';
      
      if(isset($this->controller->RequestHandler) && $this->controller->RequestHandler->isAjax())
      {
        if($this->controller->RequestHandler->ext == 'js')
        {
          $this->controller->layout = 'js/error';
        }
        else
        {
          $this->controller->layout = 'error_ajax';
        }
      }
      
      $this->controller->render($template);
      $this->controller->afterFilter();
      echo $this->controller->output;
    }
  
  }

?>
