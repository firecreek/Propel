<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?></title>
  <?php
    echo $html->css(array(
      'style',
      'front',
    ));
    echo $scripts_for_layout;
  ?>
</head>
<body>

  <header>
    <h1><?php echo $html->link('Propel','/'); ?></h1>
  </header>

  <div id="main"><?php echo $content_for_layout; ?></div>

</body>
</html>
