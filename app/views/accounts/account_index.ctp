
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
        
        
        <?php
        /*
        <div class="section outlined small important">
          <div class="banner">
            <h3><?php __('Late milestones'); ?></h3>
          </div>
          <div class="content">
            <p>Some text here</p>
            
            <?php
              echo $this->element('calendar/month',array('type'=>'short','month'=>date('n'),'year'=>date('Y')));
            ?>
          </div>
        </div>
        
        
        <div class="section outlined small highlight">
          <div class="banner">
            <h3><?php __('Upcoming milestones'); ?></h3>
          </div>
          <div class="content">
            <p>Some text here</p>
            
            <?php
              echo $this->element('calendar/month',array('type'=>'short','month'=>date('n'),'year'=>date('Y')));
            ?>
          </div>
        </div>
        
        
        
        <div class="section">
          <div class="content">
          
            <table class="logs std">
              <thead>
                <tr>
                  <th colspan="5"><h3><?php echo $html->link('Test Project Name','#',array('class'=>'unimportant')); ?></h3></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="type"><div class="badge todo">To-do</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge milestone">Milestone</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post">Message</div></td>
                  <td class="description"><a href="#">Shared colleague to access the Calendar for the shared job</a></td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge comment">Comment</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Posted by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post">Message</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post private">Message</div></td>
                  <td class="description">This is a private message</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
              </tbody>
            </table>
            
            
            

            <table class="std logs dated">
              <tbody>
                <tr class="date">
                  <td colspan="5">
                    <h4><span>Thursday, 16 December 2010</span></h4>
                  </td>
                </tr>
                <tr>
                  <td class="type"><div class="badge todo">To-do</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge milestone">Milestone</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr class="date">
                  <td colspan="5">
                    <h4><span>Monday, 13 December 2010</span></h4>
                  </td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post">Message</div></td>
                  <td class="description"><a href="#">Shared colleague to access the Calendar for the shared job</a></td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge comment">Comment</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Posted by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post">Message</div></td>
                  <td class="description">Shared colleague to access the Calendar for the shared job</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
                <tr class="date">
                  <td colspan="5">
                    <h4><span>Saturday, 9 December 2010</span></h4>
                  </td>
                </tr>
                <tr>
                  <td class="type"><div class="badge post private">Message</div></td>
                  <td class="description">This is a private message</td>
                  <td class="action">Completed by</td>
                  <td class="person">Darren M.</td>
                  <td class="date">Dec 16</td>
                </tr>
              </tbody>
            </table>
          
          </div>
        </div>
        */
        ?>
        
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <?php
      //Account image
      $image = ASSETS_DIR.DS.'accounts'.DS.$auth->read('Account.id').DS.'logo'.DS.'account.png';
      
      if(file_exists($image))
      {
        echo '<div class="account-image">'.$html->image('/'.$auth->read('Account.slug').'/assets/image/logo/account.png/size:300x300').'</div>';
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
