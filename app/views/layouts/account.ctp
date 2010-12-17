<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $title_for_layout; ?> - <?php __('OpenCamp'); ?></title>
    <?php
        echo $html->css(array(
            'reset',
            '960',
            'type',
            'elements',
            'account'
        ));
        echo $scripts_for_layout;
    ?>
</head>

<body>

    <div id="wrapper" class="install">
    
        <div id="launchbar">
            <p><?php __('OpenCamp'); ?></p>
        </div>
    
        <header>
            <h1><?php echo $session->read('Account.Company.name'); ?></h1>
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
