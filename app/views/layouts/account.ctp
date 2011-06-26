<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?></title>
  <?php
    echo $html->css(array(
      'reset',
      'style',
      'account',
      'listable'
    ));
    echo $javascript->link(array(
      'jquery/jquery',
      'jquery/jquery-ui',
      'jquery/jquery-ajaxsubmit',
      'jquery/jquery-oc-calendar',
      'jquery/jquery-oc-dialog',
      'jquery/superfish',
      'jquery/supersubs',
      'account'
    ));
    echo $scripts_for_layout;
  ?>
  <style type="text/css" media="screen"><!--
    <?php echo $this->element('style'); ?>
  --></style>
</head>

<body>

    <?php
      echo $this->element('headers/'.$prefix);
    ?>

    <div id="main">
      <?php echo $session->flash(); ?>
      <?php echo $content_for_layout; ?>
    </div>
    
    <footer>
      <p><?php __('Managed with'); ?> <?php echo $html->link('Propel','http://www.propelhq.com?ref=accfoot'); ?>.</p>
    </footer>


</body>
</html>
