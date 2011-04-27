<?php

  $javascript->link('listable.js', false);
  $javascript->link('categories.js', false);
  $html->css('categories', null, array('inline'=>false));
  
  //
  if(!isset($filter)) { $filter = false; }
  
  //
  $typeHuman = Inflector::humanize($type);
  
  //Standarise model data
  if(isset($records[0]['Category']))
  {
    $records = Set::combine($records,'{n}.Category.id','{n}.Category.name');
  }
  
?>

<div class="listable category-list" id="Categories<?php echo $typeHuman; ?>">

  <div class="items">
    <?php
      foreach($records as $catId => $catName)
      {
        //Filter
        if($filter)
        {
          $listOptions = array('url'=>array_merge($filter,array('category'=>$catId)));
        }
        
        //Selected
        if(isset($active) && $active == $catId)
        {
          $listOptions['class'] = 'active';
        }
      
        echo $listable->item('Category',$catId,$catName,array_merge(array(
          'position'  => false,
          'checkbox'  => false,
          'comments'  => false,
          'editUrl'   => $html->url(array('controller'=>'categories','action'=>'edit',$catId)),
          'deleteUrl' => $html->url(array('controller'=>'categories','action'=>'delete',$catId)),
        ),$listOptions));
      }
    ?>
  </div>
  
  <div class="add-record-container">
    <div class="add-record-link" style="display:none;">
      <?php echo $html->link(sprintf(__('Add a new %s category',true),$type),array('controller'=>'categories','action'=>'add',$type),array('class'=>'important','rel'=>'Categories'.$typeHuman)); ?>
    </div>
    <?php
      echo $this->element('categories/add',array(
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

