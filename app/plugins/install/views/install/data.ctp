<div class="install">
    <div class="banner">
        <h2><?php echo $title_for_layout; ?></h2>
    </div>
    <div class="content">
        <?php
            echo $html->link(__('Click here to build your database', true), array(
                'plugin' => 'install',
                'controller' => 'install',
                'action' => 'data',
                'run' => 1
            ),array('class'=>'button'));
        ?>
    </div>
</div>
