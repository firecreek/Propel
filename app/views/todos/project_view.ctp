<?php
  $javascript->link('listable.js', false);
  
  $javascript->link('projects/todos.js', false);
  $html->css('projects/todos_index', null, array('inline'=>false));
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php echo $html->link(__('See all to-do lists',true),array('action'=>'index')); ?></h2>
        
        <?php echo $this->element('todos/banner_filters'); ?>
        
        <ul class="right important">
          <li><?php
            echo $html->link(__('Delete this list',true),array('action'=>'delete',$id));
          ?></li>
        </ul>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        
        
        <?php
          echo $this->element('todos/list',array(
            'records' => array($todo),
            'headerLink' => false,
            'showCount' => false
          ));
        ?>
        
        <?php
          echo $javascript->codeBlock("
            $('.listable').listable({
              sortable:true,
              positionUrl:'".$html->url(array('controller'=>'todos_items','action'=>'update_positions'))."'
            });
          ");
        ?>
      </div>
    </div>

  </div>
  <div class="col right">
  
    <?php
      echo $this->element('todos/filter');
      echo $this->element('todos/list_active');
      echo $this->element('todos/list_completed');
    ?>
    
  </div>
  
</div>
