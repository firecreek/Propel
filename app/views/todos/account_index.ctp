<?php

  $javascript->link('listable.js', false);
  $html->css('todos', null, array('inline'=>false));
  
  if(!isset($responsibleName))
  {
    $responsibleName = __('Anyone',true);
  }
  
?>

<div class="box">
  <div class="banner">
    <h2><?php __(sprintf('%s to-do items across all projects',$responsibleName.'\'s')); ?></h2>
    <?php echo $this->element('todos/banner_filters'); ?>
  </div>
  <div class="content account-list">
  
    <?php if(!empty($projects)): ?>
    
      <?php foreach($projects as $project): ?>
      
        <div class="section plain todos clearfix">
          <div class="banner">
            <h3><?php
              echo $html->link($project['Project']['name'].' <span>— '.$project['Account']['name'].'</span>',array('projectId'=>$project['Project']['id'],'controller'=>'todos','action'=>'index'),array('escape'=>false));
            ?></h3>
          </div>
          <div class="content">
          
            <?php foreach($project['Todo'] as $todoRecord): ?>
            
              <div class="cols clearfix">
                <div class="col left">
                  <div class="wrapper">
                    <p><?php
                      echo $html->link($todoRecord['Todo']['name'],array(
                        'projectId'   => $project['Project']['id'],
                        'controller'  => 'todos',
                        'action'      => 'view',
                        $todoRecord['Todo']['id']
                      ));
                    ?></p>
                  </div>
                </div>
                <div class="col right">
                  <div class="wrapper listable">
                    <?php foreach($todoRecord['TodoItem'] as $item): ?>
                      <?php
                        $extras = array();
                        
                        if(isset($item['Responsible']) && !empty($item['Responsible']))
                        {
                          $extras[] = $item['Responsible']['name'];
                        }
                        
                        if(!empty($item['TodoItem']['deadline']))
                        {
                          $extras[] = date('j M Y',strtotime($item['TodoItem']['deadline']));
                        }
                        
                        $extra = implode(' ',$extras);
                        
                        echo $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
                          'position'  => false,
                          'extra'     => $extra,
                          'delete'    => false,
                          'edit'      => false,
                          'comments'    => false,
                          'updateUrl'   => $html->url(array(
                            'projectId'   => $project['Project']['id'],
                            'controller'  => 'todos_items',
                            'action'      => 'update',
                            $item['TodoItem']['id'],
                            '?' => array('from'=>'account')
                          )),
                        ));
                      ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
              
            <?php endforeach; ?>
          
          </div>
        </div>
      
      <?php endforeach; ?>


      <?php
        echo $javascript->codeBlock("
          $('.listable').listable({
            sortable:false
          });
        ");
      ?>
      
    <?php endif; ?>
    

    <div id="noRecords" style="<?php if(!empty($projects)) { echo 'display:none;'; } ?>">

      <?php if(isset($responsibleName) && isset($dueName)): ?>
        
          <p>
            <strong><?php echo $responsibleName; ?></strong>
            <?php __('isn\'t responsible for any to-do items'); ?>
            <strong><?php __('due'); ?> <?php echo $dueName; ?></strong>.
          </p>
          <p><?php __('Choose another person or due date with the pulldown to the right.'); ?></p>
        
      <?php elseif(isset($responsibleName)): ?>
      
          <strong><?php echo $responsibleName; ?></strong> <?php __('isn\'t responsible for any to-do items'); ?>
          <p><?php __('Choose another person with the pulldown to the right.'); ?></p>
        
      <?php elseif(isset($dueName)): ?>
      
          <?php __('There are no to-do items'); ?><strong> <?php __('due'); ?> <?php echo $dueName; ?></strong>
          <p><?php __('Choose another due date with the pulldown to the right.'); ?></p>

      <?php else: ?>
      
        <p><?php __('All the to-do lists in your projects are completed.'); ?></p>
      
      <?php endif; ?>
      
    </div>
    
    
  </div>
</div>
