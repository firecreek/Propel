<?php __('You\'re invited to join Opencamp, our project management and collaboration system.'); ?>


<?php __('Hi'); ?> <?php echo $data['Person']['first_name'] ?>,

<?php echo $data['Invitee']['full_name'] ?> <?php __('just set up an account for you. All you need to do is choose a username and password. It only takes a few seconds.'); ?>


<?php __('Click this link to get started:'); ?> 
<?php
  echo Router::url(array('prefix'=>false,'account'=>false,'admin'=>false,'controller'=>'users','action'=>'invitation',$data['Person']['invitation_code']),true);
?>

<?php if(isset($data['Person']['invitation_note'])): ?>

<?php echo $data['Invitee']['first_name'] ?> <?php __('says'); ?>:
<?php echo $data['Person']['invitation_note']; ?>

<?php endif; ?>

--

<?php __('Have questions? Contact'); ?> <?php echo $data['Invitee']['full_name'] ?> <?php __('at'); ?> <?php echo $data['Invitee']['email'] ?>
