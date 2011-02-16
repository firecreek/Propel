<?php

  $javascript->link('listable.js', false);
  
  $javascript->link('projects/todos.js', false);
  $html->css('projects/todos_index', null, array('inline'=>false));
  
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('To-do lists'); ?></h2>
        
        <?php echo $this->element('todos/banner_responsible'); ?>
        
        <ul class="right important">
          <li><?php
            $notActive = __('Reorder lists',true);
            $active = __('Done reordering lists',true);
            echo $html->link($notActive,array('action'=>'reorder'),array(
              'id'              => 'reorderLists',
              'rel-not-active'  => $notActive,
              'rel-active'      => $active,
              'rel-update-url'  => $html->url(array('action'=>'update_positions'))
            ));
          ?></li>
        </ul>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        
        <?php if(!empty($todos)): ?>
        
          <?php
              echo $this->element('todos/list',array(
                'records' => $todos,
                'showCount' => true
              ));
              
            echo $javascript->codeBlock("
              $('.listable').listable({
                sortable:true,
                positionUrl:'".$html->url(array('action'=>'update_item_positions'))."'
              });
            ");
          ?>
          
        <?php else: ?>
        
          <p><strong><?php echo $responsibleName; ?></strong> <?php __('isn\'t responsible for any to-do items'); ?></p>
        
        <?php endif; ?>
      </div>
    </div>

  </div>
  <div class="col right">
  
    
    <?php
      if($auth->check('Project.Todos','create'))
      {
        echo $layout->button(__('New to-do list',true),array('controller'=>'todos','action'=>'add'),'large add');
      }
    ?>
    
    
    <?php
      echo $this->element('todos/filter');
      echo $this->element('todos/list_active');
      echo $this->element('todos/list_completed');
    ?>
    
  
  </div>
  
</div>
