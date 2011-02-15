<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?> - <?php __('Opencamp'); ?></title>
  <?php
    echo $html->css(array(
      'reset',
      'type',
      'elements',
      'account',
      'listable'
    ));
    echo $javascript->link(array(
      'jquery/jquery',
      'jquery/jquery-ui',
      'account'
    ));
    echo $scripts_for_layout;
  ?>
  <style type="text/css" media="screen"><!--
    <?php echo $this->element('style'); ?>
  --></style>
</head>

<body>


    <div id="launchbar">
      <p><?php __('Opencamp'); ?></p>
    </div>

    <header>
      <h1><?php echo $header; ?></h1>
      
      <nav id="account">
          <ul>
              <li><span><?php echo $session->read('AuthAccount.Person.first_name').' '.$session->read('AuthAccount.Person.last_name'); ?></span></li>
              <li><?php echo $html->link(__('My info',true),array('controller'=>'people','action'=>'edit',$session->read('AuthAccount.Person.id'))); ?></li>
              <li><?php echo $html->link(__('Sign out',true),array('account'=>false,'controller'=>'users','action'=>'logout')); ?></li>
          </ul>
      </nav>
      
      <?php
        echo $this->element('menu/'.$prefix);
      ?>
    </header>

    <div id="main"><?php echo $content_for_layout; ?></div>
    
    <?php echo $this->element('account/footer'); ?>

</body>
</html>
