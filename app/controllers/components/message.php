<?php

  /**
   * Message Component
   *
   * @category Component
   * @package  OpenCamp
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://opencamp.firecreek.co.uk
   */
  class MessageComponent extends Object
  {    
    /**
     * Components to use
     *
     * @access public
     * @type array
     */
    public $components = array('Authorization','Email');
    
    /**
     * Send email switch
     *
     * @access public
     * @type boolean
     */
    public $sendEmail = true;
    
    
    /**
     * Initialize Component
     *
     * @param object $controller Controller object, reference
     * @param array $settings Settings passed
     * @access public
     * @return boolean Success
     */
    public function initialize(&$controller, $settings = array())
    {
      $this->controller =& $controller;
    }
    
    
    /**
     * Send message
     * 
     * @param string $view View filename to render
     * @param array $data For the email view
     * @param array $settings Settings as below
     *                          - methods: email, web and/or sms
     * @access private
     * @return int
     */
    public function send($view,$settings = array(),$data = array())
    {
      //Settings
      $default = array(
        'from'          => 'Opencamp <opencamp@firecreek.co.uk>',
        'replyTo'       => 'darren.m@firecreek.co.uk',
        'return'        => 'darren.m@firecreek.co.uk',
        'layout'        => 'default',
        'view'          => $view,
        'subject'       => null,
        'sendAs'        => 'text',
        'attachments'   => array(),
        'methods'       => array('email'),
        'debug'         => false,
        'subject'       => 'Opencamp'
      );
      $settings = array_merge($default,$settings);
      
      //Who to
      $settings['to'] = $this->_findTo($settings);
      
      //Set for view
      $this->controller->set('data',$data);
      
      //Render body
      $settings['body'] = $this->_renderMessage($settings);
      
      //Debug
      if($settings['debug'])
      {
        debug($settings);
        exit;
      }
      
      //Send message(s)
      return $this->_sendMessages($settings);
    }
    
    
    /**
     * Find to
     *
     * @param array $settings
     * @access private
     * @return array
     */
    private function _findTo($settings)
    {
      $to = array($settings['to']);
      
      return $to;
    }
    
    
    /**
     * Render message
     *
     * @param array settings
     * @access private
     * @return string
     */
    private function _renderMessage($settings)
    {
      $View = new View($this->controller, false);
      $View->layout = $settings['layout'];
      
      return $View->element('email' . DS . 'text' . DS . $settings['view'], array('content' => null), true);
    }
    
    
    /**
     * Send messages
     *
     * @todo SMS
     * @todo Return success on multiple records
     * @param array settings
     * @access private
     * @return boolean
     */
    private function _sendMessages($settings)
    {
      $success = false;
    
      //Loop
      foreach($settings['to'] as $to)
      {
        //Save in messages table
        /*$messageSave = array(
          'id'          => 0,
          'user_id'     => !empty($to['userId']) ? $to['userId'] : null,
          'friend_id'   => !empty($to['friendId']) ? $to['friendId'] : null,
          'event_id'    => isset($settings['eventId']) ? $settings['eventId'] : null,
          'to'          => $to['email'],
          'from'        => $settings['from'],
          'type'        => $settings['type'],
          'view'        => $settings['view'],
          'subject'     => $settings['subject'],
          'body'        => $settings['body'],
          'hidden'      => $settings['hidden']
        );
        ClassRegistry::init('Message')->save($messageSave);*/

        //If in test mode then skip the rest
        if(defined('CAKE_UNIT_TEST') && CAKE_UNIT_TEST)
        {
          continue;
        }
        
        //Email
        if(in_array('email',$settings['methods']) && $this->sendEmail == true)
        {
          $success = $this->_sendEmail($to,$settings);
        }
      }
      
      //Return success
      return $success;
    }
    
    
    /**
     * Send email
     *
     * @todo Attachments
     * @param string $to
     * @param array $settings
     * @access private
     * @return boolean
     */
    private function _sendEmail($to,$settings)
    {
      if(Configure::read('Email.delivery') == 'smtp')
      {
        $this->Email->smtpOptions = array(
          'host'      => Configure::read('Email.host'),
          'port'      => Configure::read('Email.port'),
          'username'  => Configure::read('Email.username'),
          'password'  => Configure::read('Email.password'),
          'client'    => Configure::read('Email.client'),
          'timout'    => Configure::read('Email.timout'),
        );
        $this->Email->delivery = 'smtp';
      }
      
      //Send email
      $this->Email->additionalParams = "-f".$settings['return'];
      $this->Email->to        = '<'.$to.'>';
      $this->Email->from      = $settings['from'];
      $this->Email->replyTo   = '<'.$settings['replyTo'].'>';
      $this->Email->subject   = $settings['subject'];
      $this->Email->sendAs    = $settings['sendAs'];
      
      /*if(!empty($settings['attachments']))
      {
        $this->Email->filePaths  = array(Configure::read('Store.dir').DS);
        $this->Email->attachments = $settings['attachments']; 
      }*/
      
      return $this->Email->send($settings['body']);
    }
    
  
  }


?>
