
<?php foreach($comments as $record): ?>

  <div class="comment">
    <div class="avatar"><?php echo $html->image('avatar.png'); ?></div>
    <div class="area">
      <div class="banner">
        <h4>
          <?php echo $record['Person']['full_name']; ?>
          <span><?php echo date('D, j M Y \a\t g:ia',strtotime($record['created'])); ?></span>
        </h4>
        <ul class="right">
          <li class="delete"><?php echo $html->link(__('Delete',true),array('action'=>'delete',$id,$record['id'])); ?></li>
        </ul>
      </div>
      <div class="content">
        <?php echo $record['body']; ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>
