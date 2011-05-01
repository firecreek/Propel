<?php

  /**
   * Message Component
   *
   * @category Component
   * @package  Propel
   * @version  1.0
   * @author   Darren Moore <darren.m@firecreek.co.uk>
   * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
   * @link     http://www.propelhq.com
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
        'from'          => Configure::read('Email.replyName').' <'.Configure::read('Email.replyEmail').'>',
        'replyTo'       => Configure::read('Email.replyEmail'),
        'return'        => Configure::read('Email.replyEmail'),
        'layout'        => 'default',
        'view'          => $view,
        'subject'       => null,
        'sendAs'        => 'text',
        'attachments'   => array(),
        'methods'       => array('email'),
        'debug'         => false,
        'subject'       => 'Propel',
        'data'          => $data
      );
      $settings = array_merge($default,$settings);
      
      //Who to, id's, arrays etc.. can come in
      $settings['to'] = $this->_findTo($settings);
      
      //Subject, include project name or account name if set
      $settings['subject'] = $this->_subject($settings);;
      
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
      $output = array();
    
      if(is_numeric($settings['to']))
      {
        $output[] = $this->__loadPerson($settings['to']);  
      }
      elseif(is_string($settings['to']))
      {
        $output[] = $settings['to'];
      }
      elseif(is_array($settings['to']))
      {
        foreach($settings['to'] as $to)
        {
          if(is_numeric($to))
          {
            $output[] = $this->__loadPerson($to);          
          }
          elseif(is_string($to))
          {
            $output[] = $to;
          }
        }
      }
      
      return $output;
    }
    
    
    /**
     * Load person
     *
     * @param int $id Person ID
     * @access private
     * @return mixed
     */
    private function __loadPerson($id)
    {
      if($person = ClassRegistry::init('Person')->find('first',array(
        'conditions' => array('Person.id'=>$id),
        'contain' => false
      )))
      {
        $output = array(
          'email' => $person['Person']['email'],
          'name' => $person['Person']['full_name'],
        );
      }

      return isset($output) ? $output : false;
    }
    
    
    /**
     * Build subject
     *
     * @access private
     * @return string
     */
    private function _subject($settings)
    {
      if($this->Authorization->read('Project.id'))
      {
        $prefix = $this->Authorization->read('Project.name');
      }
      elseif($this->Authorization->read('Account.id'))
      {
        $prefix = $this->Authorization->read('Account.name');
      }
      
      if(isset($prefix)) { $output = '['.$prefix.'] '.$settings['subject']; }
      else { $output = $settings['subject']; }
    
      return $output;
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
      
      //To
      if(is_array($to))
      {
        $toParam = $to['name'].' <'.$to['email'].'>';
      }
      else
      {
        $toParam = '<'.$to.'>';
      }
      
      //Send email
      $this->Email->additionalParams = "-f".$settings['return'];
      $this->Email->to        = $toParam;
      $this->Email->from      = $settings['from'];
      $this->Email->replyTo   = '<'.$settings['replyTo'].'>';
      $this->Email->subject   = $settings['subject'];
      $this->Email->sendAs    = $settings['sendAs'];
      
      //Render body
      $this->controller->set('data',$settings['data']);
      $this->controller->set(compact('settings','to'));
      $settings['body'] = $this->_renderMessage($settings);
      
      /*if(!empty($settings['attachments']))
      {
        $this->Email->filePaths  = array(Configure::read('Store.dir').DS);
        $this->Email->attachments = $settings['attachments']; 
      }*/
      
      return $this->Email->send($settings['body']);
    }
    
  
  }


?>
