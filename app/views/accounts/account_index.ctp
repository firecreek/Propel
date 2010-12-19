
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
      if($auth->check('Account.Projects','create'))
      {
        echo $html->link(__('Create a new project',true),array('controller'=>'projects','action'=>'add'),array('class'=>'button action add large'));
      }
    ?>
    
    <?php $projects = $auth->read('Projects'); ?>
    <?php if(!empty($projects)): ?>
    <div class="area">
      <h3><?php __('Your projects'); ?></h3>
      <?php
        //Group projects by company
        $projectCompanies = array();
        
        foreach($projects as $project)
        {
          if(!isset($projectCompanies[$project['Company']['name']]))
          {
            $projectCompanies[$project['Company']['name']] = array();
          }
          
          $projectCompanies[$project['Company']['name']][] = $project;
        }
      ?>
      <div class="content">
        <ul class="project-list">
          <?php foreach($projectCompanies as $companyName => $companyProjects): ?>
            <li>
              <strong><?php echo $companyName; ?></strong>
              <ul>
                <?php foreach($companyProjects as $project): ?>
                  <li><?php
                    echo $html->link($project['Project']['name'],array(
                      'accountSlug' => $project['Account']['slug'],
                      'projectId'   => $project['Project']['id'],
                      'controller'  => 'projects',
                      'action'      => 'index'
                    ));
                  ?></li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>
  
  </div>
</div>
