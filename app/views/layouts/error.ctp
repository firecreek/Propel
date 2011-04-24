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
      'front',
    ));
    echo $scripts_for_layout;
  ?>
</head>
<body>

  <header>
    <h1><?php echo $html->link('Opencamp','/'); ?></h1>
  </header>

  <div id="main">
    <div class="error-box">
      <?php echo $content_for_layout; ?>
    </div>
  </div>

</body>
</html>
