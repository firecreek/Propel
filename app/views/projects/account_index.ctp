<?php
  $html->css('projects', null, array('inline'=>false));
?>

<div class="cols">

  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php echo $this->Auth->read('Account.name'); ?> <?php __('Projects'); ?></h2>
      </div>
      <div class="content">        
        
        <div class="section">
          <div class="content">
            <?php
              $projects = $auth->read('Projects');
            ?>
            <ul class="project-list">
              <?php foreach($projects as $project): ?>
                <li>
                  <h3><?php
                    echo $html->link($project['Project']['name'],array(
                      'accountSlug' => $project['Account']['slug'],
                      'projectId'   => $project['Project']['id'],
                      'controller'  => 'projects',
                      'action'      => 'index'
                    ));
                  ?></h3>
                  <p class="company"><?php echo $project['Company']['name']; ?></p>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <?php
      if($this->Auth->check(array('controller'=>'projects','action'=>'add')))
      {
        echo $layout->button(__('Create a new project',true),array('controller'=>'projects','action'=>'add'),'large add');
      }
    ?>
    
  
  </div>
</div>
