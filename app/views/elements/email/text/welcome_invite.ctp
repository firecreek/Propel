Thanks for creating an account.

You're all set. You'll find your username and a link to sign in below.

Your new username is:
<?php echo $data['User']['username']; ?>


Access your account now:
<?php
  echo Router::url(array('accountSlug'=>$data['Account']['slug'],'controller'=>'accounts','action'=>'index'),true);
?>


--

Have questions? Contact <?php echo $data['PersonInvitee']['full_name'] ?> at <?php echo $data['PersonInvitee']['email'] ?>

