<?php

  $javascript->link('listable.js', false);
  $javascript->link('accounts/categories', false);
  $html->css('accounts/categories', null, array('inline'=>false));
  
  $typeHuman = Inflector::humanize($type);
  
?>

<div class="listable category-list" id="Categories<?php echo $typeHuman; ?>">

  <div class="items">
    <?php
      foreach($records as $record)
      {
        echo $listable->item('Category',$record['Category']['id'],$record['Category']['name'],array(
          'position'  => false,
          'checkbox'  => false,
          'comments'  => false,
          'editUrl'   => $html->url(array('controller'=>'categories','action'=>'edit',$record['Category']['id'])),
          'deleteUrl' => $html->url(array('controller'=>'categories','action'=>'delete',$record['Category']['id'])),
        ));
      }
    ?>
  </div>
  
  <div class="add-record-container">
    <div class="add-record-link" style="display:none;">
      <?php echo $html->link(sprintf(__('Add a new %s category',true),$type),array('controller'=>'categories','action'=>'add',$record['Category']['id']),array('class'=>'important','rel'=>'Categories'.$typeHuman)); ?>
    </div>
    <?php
      echo $this->element('categories/add',array(
        'id'    => $record['Category']['id'],
        'type'  => $type
      ));
    ?>
  </div>

</div>


<?php
  echo $this->Javascript->codeBlock("
    $('#Categories".$typeHuman." .category-add form').ajaxSubmit();
    $('#Categories".$typeHuman." .add-record-link').show();
    $('#Categories".$typeHuman." .category-add').hide();
  ");
?>

