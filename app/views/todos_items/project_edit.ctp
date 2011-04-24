
<div class="box">
  <div class="banner">
    <h2><?php __('Edit to-do item'); ?></h2>
  </div>
  <div class="content">
      
    <?php
      echo $this->element('todos_items/add',array(
        'todoId'=>$todoId,
        'edit'=>true,
        'todoItemIdent' => isset($this->params['form']['objId']) ? $this->params['form']['objId'] : null
      ));
    ?>
    
  </div>
</div>
