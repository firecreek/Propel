
<div class="listable">

  <?php foreach($records as $record): ?>
  
    <?php
      $ident = 'todo-'.$record['Todo']['id'].'-'.rand(1000,9999);
    ?>

    <div class="group" id="<?php echo $ident; ?>" rel-todo-id="<?php echo $record['Todo']['id']; ?>">
    
      <div class="header">
        <?php
          echo $listable->item('Todo',$record['Todo']['id'],$record['Todo']['name'],array(
            'checkbox'    => false,
            'comments'    => false,
            'position'    => true,
            'positionHide' => true,
            'url'         => $html->url(array('controller'=>'todos','action'=>'view',$record['Todo']['id'])),
            'editUrl'     => $html->url(array('controller'=>'todos','action'=>'edit',$record['Todo']['id'])),
          ));
        ?>
      </div>
      
      <div class="item-content">
        
        <div class="items sortable">
          <?php foreach($record['TodoItem'] as $item): ?>
            <?php
              echo $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
                'position'  => true,
                'editUrl'   => $html->url(array('controller'=>'todos','action'=>'edit_item',$record['Todo']['id'],$item['TodoItem']['id'])),
                'updateUrl'   => $html->url(array('controller'=>'todos','action'=>'update_item',$record['Todo']['id'],$item['TodoItem']['id'])),
              ));
            ?>
          <?php endforeach; ?>
        </div>
        
        <div class="add-item-link" style="display:none;">
          <?php echo $html->link(__('Add an item',true),array('action'=>'item_add',$record['Todo']['id']),array('rel'=>$ident,'class'=>'important')); ?>
        </div>
        <?php
          echo $this->element('todos/add_item',array('todoId'=>$record['Todo']['id']));
          echo $javascript->codeBlock("
            $('.add-item-link').show();
            $('.item-add').hide();
          ");
        ?>
        
        <?php if(!empty($record['TodoItemRecent'])): ?>
        <div class="recent">
          <?php foreach($record['TodoItemRecent'] as $item): ?>
            <?php
              echo $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
                'edit' => false,
                'checked' => true,
                'prefix' => date('M j',strtotime($item['TodoItem']['completed_date'])),
                'updateUrl'   => $html->url(array('controller'=>'todos','action'=>'update_item',$record['Todo']['id'],$item['TodoItem']['id'])),
              ));
            ?>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
      </div>
      
    </div>

  <?php endforeach; ?>

</div>
