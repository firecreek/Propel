
<?php if(!empty($todosActive)): ?>
  <div class="area plain">
    <div class="banner"><h3><?php __('Current to-do lists'); ?></h3></div>
    <div class="content">
      <ul>
        <?php foreach($todosActive as $todo): ?>
          <li><?php echo $html->link($todo['Todo']['name'],array('action'=>'view',$todo['Todo']['id'])); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
