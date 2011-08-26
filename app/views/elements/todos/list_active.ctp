
<?php if(!empty($todosActive)): ?>
  <div class="box">
    <div class="banner">
      <h3><?php __('Active to-do lists'); ?></h3>

      <ul class="right important">
        <li><?php
        
          //Reorder link
          $notActive = __('Reorder lists',true);
          $active = __('Done reordering lists',true);
          
          if(empty($filter) && !empty($todos))
          {
            echo $html->link($notActive,array('action'=>'reorder'),array(
              'id'              => 'reorderLists',
              'rel-not-active'  => $notActive,
              'rel-active'      => $active,
              'rel-update-url'  => $html->url(array('action'=>'update_positions'))
            ));
          }
          else
          {
            echo '<span>'.$notActive.'</span>';
          }
          
        ?></li>
      </ul>
      
    </div>
    <div class="content">
    
      <ul id="TodoActiveList">
        <?php foreach($todosActive as $todo): ?>
          <li data-todo-id="<?php echo $todo['Todo']['id']; ?>"><?php echo $html->link($todo['Todo']['name'],array('action'=>'view',$todo['Todo']['id'])); ?></li>
        <?php endforeach; ?>
      </ul>
      
    </div>
  </div>
<?php endif; ?>
