
<div class="box">
  <div class="content">
        
    <h2><?php echo sprintf(__('Send %s another invitation',true),$record['Person']['first_name']); ?></h2>
    
    <p><?php echo sprintf(__('You can send another invitation if %s didn\'t receive the previous one. The invitation includes a link to set a username and password so %s can sign in.',true),$record['Person']['first_name'],$record['Person']['first_name']); ?></p>
  
    <p><?php echo sprintf(__('The last invitation was sent on %s to %s.',true),date('F j, Y',strtotime($record['Person']['invitation_date'])),$record['Person']['email']); ?></p>

    <?php
      echo $this->Form->create('Person',array('url'=>$this->here,'class'=>'block light'));
      echo $this->Form->input('email',array('label'=>__('Send the invitation to this email address:',true),'div'=>'input text half-width' ));
      echo $this->Form->submit(__('Send another invitation',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'edit',$personId) ) ));
    ?>
    
    
  </div>
</div>
