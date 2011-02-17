
<div class="box">
  <div class="banner">
    <h2><?php __('Edit to-do item'); ?></h2>
  </div>
  <div class="content">
      
    <?php
      echo $this->element('todos/add_item',array(
        'todoId'=>$todoId,
        'edit'=>true,
        'todoItemIdent' => isset($this->params['url']['objId']) ? $this->params['url']['objId'] : null
      ));
    ?>
    
  </div>
</div>
