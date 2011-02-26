<?php
  $this->set('activeMenu','companies');
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Add person to'); ?> <?php echo $record['Company']['name']; ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $form->create('Person',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
        <p class="intro"><?php __('This person\'s name will be displayed next to their messages, comments, to-dos, milestones, and files.'); ?></p>
        <fieldset>
          <?php
            echo $form->input('first_name',array('label'=>__('First name',true),'div'=>'input text strong'));
            echo $form->input('last_name',array('label'=>__('Last name',true),'div'=>'input text strong'));
            echo $form->input('email',array('label'=>__('Email',true),'div'=>'input text strong'));
          ?>
        </fieldset>
        
        
        
        <p class="intro"><?php __('The rest is optional. You can fill it in later if you\'d like.'); ?></p>
        <fieldset>
          <?php
            echo $form->input('title');
            echo $form->input('phone_number_office',array('label'=>__('Office',true).' #'));
            echo $form->input('phone_number_mobile',array('label'=>__('Mobile',true).' #'));
            echo $form->input('phone_number_fax',array('label'=>__('Fax',true).' #'));
            echo $form->input('phone_number_home',array('label'=>__('Home',true).' #'));
          ?>
        </fieldset>
        
        
        
        <h3><?php __('Include a personal note along with the invitation to set up their account?'); ?></h3>
        <p class="intro"><?php __('This person will receive a welcome email with a link to choose their username and password. You can also add a personalized note that will appear at the bottom of the email. Please use plain text (no HTML tags).'); ?></p>
        <fieldset class="tight">
          <?php
            echo $form->input('invitation_note',array('label'=>false));
          ?>
        </fieldset>
        
        <hr />
        
        <h3><?php __('What happens now?'); ?></h3>
        <p><?php __('When you click the "Add this person" button below, we\'ll fire off a nice invitation to the email address you entered above. The email will contain a link to a web page where this person will complete the setup process by picking their own username and password. Plus, you can immediately start involving them in projects even before they\'ve chosen their username and password.'); ?></p>
        
        <?php
          echo $form->submit(__('Add this person',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
          
          echo $form->end();
        ?>
      </div>
    </div>

  </div>
  
  <div class="col right">
    
    <div class="area">
      <div class="banner">
        <h3><?php __('Sample welcome email'); ?></h3>
      </div>
      <div class="content">
        <p><?php __('As soon as you submit this form, this person will receive a welcome email with a link to pick their own username and password.'); ?></p>
      </div>
    </div>
      
  </div>
  
</div>
