
<div class="cols">

  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php
          if($activeProjectCount == 1)
          {
            echo __('Latest activity in your project',true);
          }
          else
          {
            echo __('Latest activity across your projects',true);
          }
        ?></h2>
      </div>
      <div class="content">
        
        <?php if($activeProjectCount == 1): ?>
          <?php
            $project = $auth->read('Projects.0');
          ?>
          <p class="large"><strong>
            <?php __('Your project'); ?>:
            <?php echo $html->link($project['Project']['name'],array('controller'=>'projects','action'=>'index','projectId'=>$project['Project']['id'],)); ?>
          </strong></p>
        <?php endif; ?>
        
        
        <?php if(!$activeProjectCount): ?>
          <p>
            <strong class="highlight"><?php __('There hasn\'t been any activity in your project yet'); ?></strong><br />
            <?php __('After you begin posting messages, comments, milestones or to-dos, you\'ll see a log of recent activity on this page.'); ?>
          </p>
        <?php endif; ?>
        
        
        
        <div class="section">
          <div class="content">
            <?php
              foreach($logs as $log)
              {
                echo $this->element('logs/display',array(
                  'logs'        => $log['Log'],
                  'pagination'  => false,
                  'dateHeader'  => false,
                  'dateColumn'  => true,
                  'header'      => $html->link($log['Project']['name'].' — <span>'.$log['Company']['name'].'</span>',array('projectId'=>$log['Project']['id'],'controller'=>'projects','action'=>'index'),array('class'=>'unimportant','escape'=>false))
                ));
              }
            ?>
          </div>
        </div>
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <?php
      //Account image
      $imageFile = ASSETS_DIR.DS.'accounts'.DS.$auth->read('Account.id').DS.'logo'.DS.'account.png';
      
      if(file_exists($imageFile))
      {
        echo '<div class="account-image">'.$image->resize($imageFile,300,300).'</div>';
      }
    ?>
  
    <?php
      if($auth->check('Account.Projects','create'))
      {
        echo $layout->button(__('Create a new project',true),array('controller'=>'projects','action'=>'add'),'large add');
      }
    ?>
    
    <?php $projects = $auth->read('Projects'); ?>
    <?php if(!empty($projects)): ?>
    <div class="area">
      <div class="banner">
        <h3><?php __('Your projects'); ?></h3>
      </div>
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
