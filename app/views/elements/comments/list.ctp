
<?php foreach($comments as $record): ?>

  <?php
    //Skip if editing this record
    if(isset($edit) && $edit == $record['id'])
    {
      continue;
    }
  
    $canEdit = false;
    $class = null;
    $created = strtotime($record['created']);
    
    if($record['Person']['id'] == $this->Auth->read('Person.id'))
    {
      $class = 'highlight';
      if($created > strtotime('-15 minutes'))
      {
        $canEdit = true;
        $minutesAgo = floor((time() - $created)/60);
      }
    }
  ?>

  <div class="section plain avatar" id="Comment<?php echo $record['id']; ?>" rel-id=<?php echo $record['id']; ?>>
    <div class="avatar"><?php echo $html->image('avatar.png'); ?></div>
    <div class="banner">
      <h4 class="<?php echo $class; ?>">
        <?php echo $record['Person']['full_name']; ?>
        <span><?php echo date('D, j M Y \a\t g:ia',$created); ?></span>
        <?php if($canEdit): ?>
          <span class="edit"> | 
            <?php echo $html->link(__('Edit',true),array('action'=>'edit',$id,$record['id'],'#comment-add')); ?>
            (for another <span class="countdown-container"><?php echo $minutesAgo; ?> minutes</span>)
          </span>
        <?php endif; ?>
      </h4>
      <ul class="right">
        <li class="delete"><?php echo $html->link(__('Delete',true),array('action'=>'delete',$id,$record['id'])); ?></li>
      </ul>
    </div>
    <div class="content">
      <?php echo $textile->parse($record['body']); ?>
    </div>
  </div>
  
  <?php
    if($canEdit)
    {
      $minutesLeft = strtotime('+15 minutes',$created);
    
      echo $javascript->codeBlock("
        var finishDate = new Date(".date('Y,',$minutesLeft).(date('m')-1).date(',d,H,i,s',$minutesLeft)."); 
        $('#Comment".$record['id']." .countdown-container').countdown({
          until: finishDate,
          layout: '{mn} {ml}',
          onExpiry: function(){
            $('#Comment".$record['id']." .edit').fadeOut();
          }
        });
      ");
    }
  ?>

<?php endforeach; ?>
