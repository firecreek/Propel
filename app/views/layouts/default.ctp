<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?> - <?php __('OpenCamp'); ?></title>
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
    <h1><?php echo $html->link('OpenCamp','/'); ?></h1>
  </header>

  <div id="main"><?php echo $content_for_layout; ?></div>

</body>
</html>
