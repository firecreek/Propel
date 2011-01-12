
<div class="listable">

  <?php foreach($records as $record): ?>
  
    <?php
      $ident = 'todo-'.$record['Todo']['id'].'-'.rand(1000,9999);
    ?>

    <div class="group" id="<?php echo $ident; ?>">
      <div class="header">
        <?php
          echo $listable->item('Todo',$record['Todo']['id'],$record['Todo']['name'],array(
            'checkbox' => false,
            'comments' => false,
            'url'      => $html->url(array('controller'=>'todos','action'=>'view',$record['Todo']['id'])),
            'editUrl'   => $html->url(array('controller'=>'todos','action'=>'edit',$record['Todo']['id'])),
          ));
        ?>
      </div>
      <div class="items">
        <?php foreach($record['TodoItem'] as $item): ?>
          <?php
            echo $listable->item('Todo',$item['TodoItem']['id'],$item['TodoItem']['description'],array(
              'editUrl'   => $html->url(array('controller'=>'todos','action'=>'edit_item',$record['Todo']['id'],$item['TodoItem']['id'])),
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
    </div>

  <?php endforeach; ?>

</div>
