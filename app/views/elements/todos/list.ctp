
<div class="listable">

  <?php foreach($records as $record): ?>
  
    <?php
      $ident = 'todo-'.$record['Todo']['id'].'-'.rand(1000,9999);
    ?>

    <div class="group" id="<?php echo $ident; ?>" rel-todo-id="<?php echo $record['Todo']['id']; ?>">
    
      <div class="header">
        <?php
          
          $headerOptions = array(
            'controls' => array(
              'position' => array(
                'url' => $this->Html->url(array('controller'=>'todos','action'=>'edit',$record['Todo']['id'])),
                'hide' => true
              )
            ),
            'url' => $this->Html->url(array('controller'=>'todos','action'=>'view',$record['Todo']['id'])),
          );
          
          if(!empty($record['Todo']['description']))
          {
            $headerOptions['after'] = nl2br($record['Todo']['description']);
          }
          
          if($record['Todo']['private'])
          {
            $headerOptions['private'] = true;
          }
        
          echo $listable->item('Todo',$record['Todo']['id'],$record['Todo']['name'],$headerOptions);
        ?>
      </div>
      
      <p class="list-description"><?php echo $this->element('todos/list_description',array('record'=>$record)); ?></p>
      
      <div class="item-content">
        
        <div class="items sortable">
          <?php foreach($record['TodoItem'] as $item): ?>
            <?php
              $extras = array();
              
              if(isset($item['Responsible']) && !empty($item['Responsible']))
              {
                $extras[] = $item['Responsible']['name'];
              }
              
              if(!empty($item['TodoItem']['deadline']))
              {
                $extras[] = date('j M Y',strtotime($item['TodoItem']['deadline']));
              }
              
              $extra = implode(' ',$extras);
            
              echo $listable->item('TodosItem',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
                'controls' => array(
                  'edit'      => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'edit',$item['TodoItem']['id']))),
                  'position'  => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id']))),
                  'delete'    => array('url'=>$this->Html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id']))),
                ),
                'comments' => array(
                  'enabled'     => true,
                  'count'       => $item['TodoItem']['comment_count'],
                  'unread'      => $item['TodoItem']['comment_unread'],
                  'controller'  => 'todos_items'
                ),
                'checkbox' => array(
                  'enabled' => true,
                  'checked' => false,
                  'url'     => $this->Html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id']))
                ),
                'extra'     => $extra
              ));
            ?>
          <?php endforeach; ?>
        </div>
        
        <div class="add-item-container">
          <div class="add-item-link" style="display:none;">
            <?php echo $this->Html->link(__('Add an item',true),array('controller'=>'todos_items','action'=>'add',$record['Todo']['id']),array('rel'=>$ident,'class'=>'important')); ?>
          </div>
          <?php
            echo $this->element('todos_items/add',array(
              'todoId'      => $record['Todo']['id'],
              'groupIdent'  => $ident
            ));
          ?>
        </div>
        
        <div class="recent">
          <?php if(!empty($record['TodoItemRecent'])): ?>
          <?php foreach($record['TodoItemRecent'] as $item): ?>
            <?php
              echo $listable->item('TodosItem',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
                'controls' => array(
                  'delete' => array(
                    'url' => $this->Html->url(array('controller'=>'todos_items','action'=>'delete',$item['TodoItem']['id']))
                  )
                ),
                'checkbox' => array(
                  'enabled' => true,
                  'checked' => true,
                  'url'     => $this->Html->url(array('controller'=>'todos_items','action'=>'update',$item['TodoItem']['id']))
                ),
                'prefix' => date('M j',strtotime($item['TodoItem']['completed_date']))
              ));
            ?>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        
        <?php
          $countDisplay = 'none';
          if(
            isset($record['TodoItemCountCompleted']) && 
            $record['TodoItemCountCompleted'] > 3
          )
          { 
            $countDisplay = 'block';
          }
        ?>
        <?php if($showCount): ?>
          <div class="count" style="display:<?php echo $countDisplay; ?>">
            <p><?php
              $text = sprintf(__('View all %s completed items',true),'<span>'.$record['TodoItemCountCompleted'].'</span>');
              echo $this->Html->link($text,array('action'=>'view',$record['Todo']['id']),array('escape'=>false));
            ?></p>
          </div>
        <?php endif; ?>
        
      </div>
      
    </div>
    
    <?php
      echo $javascript->codeBlock("
        $('#".$ident." .item-add form').ajaxSubmit();
        $('#".$ident." .add-item-link').show();
        $('#".$ident." .item-add').hide();
      ");
    ?>

  <?php endforeach; ?>

</div>
