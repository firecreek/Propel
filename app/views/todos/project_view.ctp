<?php
  $javascript->link('listable.js', false);
  
  $javascript->link('projects/todos_index.js', false);
  $html->css('projects/todos_index', null, array('inline'=>false));
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php echo $html->link(__('See all to-do lists',true),array('action'=>'index')); ?></h2>
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
              positionUrl:'".$html->url(array('action'=>'update_item_positions'))."'
            });
          ");
        ?>
      </div>
    </div>

  </div>
  <div class="col right">
  
    
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
