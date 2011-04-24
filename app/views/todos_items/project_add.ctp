
<div class="cols">

  <div class="col left">
    <div class="box">
      <div class="banner">
        <h2><?php __('New to-do item to'); ?> <?php echo $html->link($record['Todo']['name'],array('controller'=>'todos','action'=>'view',$todoId)); ?></h2>
      </div>
      <div class="content">
        <?php
          echo $this->element('todos_items/add',array(
            'class'=>'block',
            'cancelText'=>__('Cancel',true),
            'todoId' => $id
          ));
        ?>
      </div>
    </div>
  </div>
  
  <div class="col right">
    <!-- empty -->
  </div>
  
</div>

