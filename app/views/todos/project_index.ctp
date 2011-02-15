<?php

  $javascript->link('listable.js', false);
  
  $javascript->link('projects/todos_index.js', false);
  $html->css('projects/todos_index', null, array('inline'=>false));
  
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('To-do lists'); ?></h2>
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
        
        <?php
          if(!empty($todos))
          {
            echo $this->element('todos/list',array(
              'records' => $todos,
              'showCount' => true
            ));
          }
        ?>
        
        <?php
          echo $javascript->codeBlock("
            $('.listable').listable({
              sortable:true,
              positionUrl:'".$html->url(array('action'=>'update_item_positions'))."'
            });
          ");
        ?>
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
    
    <div class="area plain">
      <div class="content">
        <?php
          $responsibleOptions = $layout->permissionList($auth->read('People'));
          
          echo $form->create('Todos',array('url'=>$this->here,'type'=>'get','class'=>'block'));
          echo $form->input('responsible',array('label'=>__('Show to-dos assigned to',true),'options'=>$responsibleOptions));
          echo $form->input('due',array('label'=>__('Show to-dos that are due',true),'options'=>$dueOptions));
          echo $form->submit(__('Search',true));
          echo $form->end();
        ?>
      </div>
    </div>
    
    
    <?php if(!empty($todos)): ?>
      <div class="area plain">
        <div class="banner"><h3><?php __('Current to-do lists'); ?></h3></div>
        <div class="content">
          <ul>
            <?php foreach($todos as $todo): ?>
              <li><?php echo $html->link($todo['Todo']['name'],array('action'=>'view',$todo['Todo']['id'])); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>
    
    
    <?php if(!empty($todosCompleted)): ?>
      <div class="area plain">
        <div class="banner"><h3><?php __('Completed to-do lists'); ?></h3></div>
        <div class="content">
          <ul>
            <?php foreach($todosCompleted as $todo): ?>
              <li><?php echo $html->link($todo['Todo']['name'],array('action'=>'view',$todo['Todo']['id'])); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>
    
    
  
  </div>
  
</div>
