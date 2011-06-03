<div class="install">
    <div class="banner">
        <h2><?php echo $title_for_layout; ?></h2>
    </div>
    <div class="content">
      <h3>Great!</h3>
      <p>We have connection to your database. Now we need to create the database schema.</p>
      <p>This may take a minute or depending on your setup.</p>
      <?php
          echo $html->link(__('Click here to build your database', true), array(
              'plugin' => 'install',
              'controller' => 'install',
              'action' => 'data',
              'run' => 1
          ),array('class'=>'button large'));
      ?>
    </div>
</div>
