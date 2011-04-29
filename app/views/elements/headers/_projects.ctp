<?php
  $authProjects = $auth->read('Projects');
  
  if(!empty($authProjects))
  {
    echo '<ul>';
    foreach($authProjects as $authProject)
    {
      $class = null;
      if($authProject['Project']['id'] == $this->Auth->read('Project.id'))
      {
        $class = 'active';
      }
    
      echo '<li class="'.$class.'">'.$html->link($authProject['Project']['name'],array(
        'accountSlug' => $authProject['Account']['slug'],
        'projectId' => $authProject['Project']['id'],
        'controller' => 'projects',
        'action' => 'index'
      )).'</li>';
    }
    echo '</ul>';
  }
?>
