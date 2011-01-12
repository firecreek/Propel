<?php

  $javascript->link('listable.js', false);
  $javascript->link('todos.js', false);
  $html->css('pages/project_todos', null, array('inline'=>false));
  
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('To-do lists'); ?></h2>
        <ul class="right">
          <li><?php echo $html->link(__('Reorder lists',true),array('action'=>'reorder')); ?></li>
        </ul>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        
        <?php
          //echo $form->create('Todo',array('url'=>$this->here,'id'=>'TodoIndex'));
        ?>
        
        
        <?php
          if(!empty($todos))
          {
            echo $this->element('todos/list',array(
              'records' => $todos
            ));
          }
        ?>
        
        
        <?php
          //echo $form->submit(__('Submit Changes',true)); 
          //echo $form->end();
        ?>
        
        
        <?php
          echo $javascript->codeBlock("
            //$('form#TodoIndex .submit').hide();
            $('.listable .item').listable();
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
    
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Todos',array('url'=>$this->here,'type'=>'get'));
      echo $form->input('responsible',array('label'=>__('Show to-dos assigned to',true),'options'=>$responsibleOptions));
      echo $form->input('due',array('label'=>__('Show to-dos that are due',true),'options'=>$dueOptions));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>
  
  </div>
  
</div>
