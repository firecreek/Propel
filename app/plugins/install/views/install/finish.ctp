<div class="install">
    <div class="banner">
        <h2><?php echo $title_for_layout; ?></h2>
    </div>
    <div class="content">
        <p>
            Admin panel: <?php echo $html->link(Router::url('/admin', true), Router::url('/admin', true)); ?><br />
            Username: admin<br />
            Password: <?php echo $password; ?>
        </p>
        
        <p>
            Home: <?php echo $html->link(Router::url('/', true), Router::url('/', true)); ?><br />
        </p>

        <p>Delete the installation directory <strong>/app/plugins/install</strong>.</p>

        <p>
        <?php
            echo $html->link(__('Click here to delete installation files', true), array(
                'plugin' => 'install',
                'controller' => 'install',
                'action' => 'finish',
                'delete' => 1,
            ),array('class'=>'button'));
        ?>
        </p>
    </div>
</div>
