
<div class="area category-filter">
  <div class="banner">
    <h3><?php __('Categories'); ?></h3>
    <ul class="right">
      <li><?php echo $html->link(__('Edit',true),'#',array('class'=>'important','rel'=>'edit-mode','rel-edit-text'=>__('Done editing',true))); ?></li>
    </ul>
  </div>
  <div class="content">
    <?php
      echo $this->element('categories/list',array(
        'type'      => $type,
        'records'   => $records,
        'filter'    => array('controller'=>'posts','action'=>'index'),
        'active'    => isset($this->params['named']['category']) ? $this->params['named']['category'] : null
      ));
    ?>
  </div>
</div>

<?php
  //Make it listable
  echo $javascript->codeBlock("
    $('.category-filter .listable').listable({
    });
  ");
?>
