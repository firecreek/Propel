<?php __('Hi'); ?> Darren,

<?php __('Can\'t remember your password? Don\'t worry about it - it happens.'); ?>


<?php __('Your username is'); ?>: <?php echo $data['User']['username']; ?>


<?php __('Just click this link to reset your password:'); ?> 
<?php
  echo Router::url(array('controller'=>'users','action'=>'reset',$data['User']['activate_token']),true);
?>




<?php __('Didn\'t ask to reset your password?'); ?>

<?php __('If you didn\'t ask for your password, it\'s likely that another user entered your username or email address by mistake while trying to reset their password. If that\'s the case, you don\'t need to take any further action and can safely disregard this email.'); ?>

