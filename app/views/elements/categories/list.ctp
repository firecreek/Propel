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

<div class="listable inline-right edit-visible" id="Categories<?php echo $typeHuman; ?>">

  <div class="items">
    <?php
      //All
      if(isset($all) && $all)
      {
        echo $this->Listable->item('Category',false,__('All',true).' '.$name,array(
          'class'     => (!$active) ? 'active' : null,
          'url'       => array_merge($filter,array('category'=>false))
        ));
      }
    
      //
      foreach($records as $catId => $catName)
      {
        //
        $listOptions = array();
      
        //Filter
        if($filter)
        {
          $listOptions = array('url'=> $this->Html->url(array_merge($filter,array('category'=>$catId))));
        }
        
        //Selected
        if(isset($active) && $active == $catId)
        {
          $listOptions['class'] = 'active';
        }
      
        echo $this->Listable->item('Category',$catId,$catName,array_merge(array(
          'controls' => array(
            'edit' => array(
              'url' => $html->url(array('controller'=>'categories','action'=>'edit',$catId))
            ),
            'delete' => array(
              'url' => $html->url(array('controller'=>'categories','action'=>'delete',$catId))
            )
          )
        ),$listOptions));
      }
    ?>
  </div>
  
  <div class="add-record-container">
    <div class="add-record-link" style="display:none;">
      <?php
        $addName = isset($name) ? strtolower($name) : $type;
      
        echo $html->link(sprintf(__('Add a new %s category',true),$addName),array('controller'=>'categories','action'=>'add',$type),array('class'=>'important','rel'=>'Categories'.$typeHuman));
      ?>
    </div>
    <?php
      echo $this->element('categories/add',array(
        'type'    => $type,
        'filter'  => $filter
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

