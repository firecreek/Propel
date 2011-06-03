<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title_for_layout; ?> - <?php __('Propel'); ?></title>
  <?php
      echo $html->css(array(
          'style',
          '/install/css/install',
      ));
      echo $scripts_for_layout;
  ?>
</head>
<body>

    <div id="wrapper" class="install">
        <header>
            <h1><?php __('Propel'); ?></h1>
        </header>

        <div id="main">
            <div id="install">
            <?php
                $this->Session->flash();
                echo $content_for_layout;
            ?>
            </div>
        </div>

    </div>


</body>
</html>
