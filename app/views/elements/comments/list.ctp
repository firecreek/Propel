
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
      if(
        $this->Auth->check(array('controller'=>'comments','action'=>'edit'),array('prefix'=>false)) &&
        $created > strtotime('-'.Configure::read('Comments.edit_expiry').' minutes')
      )
      {
        $canEdit = true;
        $minutesAgo = floor((time() - $created)/60);
      }
    }
  ?>

  <div class="section plain avatar" id="Comment<?php echo $record['id']; ?>" rel-id=<?php echo $record['id']; ?>>
    <div class="avatar"><?php echo $layout->avatar($record['Person']['user_id']); ?></div>
    <div class="banner">
      <h4 class="<?php echo $class; ?>">      
        <?php
          echo $html->link($record['Person']['full_name'].' <span>'.date('D, j M Y \a\t g:ia',$created).'</span>','#Comment'.$record['id'],array('escape'=>false));
        ?>
        
        <?php if($canEdit): ?>
          <span class="edit"> | 
            <?php echo $html->link(__('Edit',true),array('action'=>'index','edit'=>$record['id'],$id)); ?>
            (for another <span class="countdown-container"><?php echo $minutesAgo; ?> minutes</span>)
          </span>
        <?php endif; ?>
      </h4>
      <ul class="right">
        <?php if($this->Auth->check(array('controller'=>'comments','action'=>'delete'),array('prefix'=>false))): ?>
          <li class="delete"><?php echo $html->link(__('Delete',true),array('action'=>'delete',$id,$record['id'])); ?></li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="content restore-html">
      <?php
        if($record['format'] == 'textile')
        {
          echo $textile->parse($record['body']);
        }
        else
        {
          echo $record['body'];
        }
      ?>
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
