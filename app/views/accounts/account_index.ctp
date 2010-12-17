
<div class="cols">

  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2>Account dashboard</h2>
      </div>
      <div class="content">
        <?php
          echo $session->flash();
        ?>
        <p>Overview of all projects associated to here</p>
      </div>
    </div>

  </div>
  <div class="col right">
  
    <?php
      echo $html->link(__('Create a new project',true),array('controller'=>'projects','action'=>'add'),array('class'=>'button action add large'));
    ?>
    
    <div class="area">
      <h3>Your projects</h3>
      <div class="content">
        <ul class="project-list">
          <li>
              <strong>Company Name</strong>
              <ul>
                <li><?php echo $html->link('Example project 1','#'); ?></li>
                <li><?php echo $html->link('Example project 2','#'); ?></li>
                <li><?php echo $html->link('Example project 3','#'); ?></li>
                <li><?php echo $html->link('Example project 4','#'); ?></li>
                <li><?php echo $html->link('Example project 5','#'); ?></li>
              </ul>
          </li>
          <li>
              <strong>Lipsum Company</strong>
              <ul>
                <li><?php echo $html->link('Example project 1','#'); ?></li>
                <li><?php echo $html->link('Example project 2','#'); ?></li>
                <li><?php echo $html->link('Example project 3','#'); ?></li>
              </ul>
          </li>
        </ul>
      </div>
    </div>
  
  </div>
</div>
