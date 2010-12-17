<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $title_for_layout; ?> - <?php __('OpenCamp'); ?></title>
    <?php
        echo $html->css(array(
            'reset',
            '960',
            'front',
            'type',
            'elements',
        ));
        echo $scripts_for_layout;
    ?>
</head>

<body>

    <div id="wrapper" class="install">
        <header>
            <h1><?php __('OpenCamp'); ?></h1>
        </header>

        <div id="main">
        <?php
            $session->flash();
            echo $content_for_layout;
        ?>
        </div>

    </div>


    </body>
</html>
