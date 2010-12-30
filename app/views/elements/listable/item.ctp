<?php

  if(!isset($classes)) { $classes = array(); }
  if(!isset($checked)) { $checked = false; }
  
  if(!isset($ident)) { srand(microtime()*39494); $ident = $id.'-'.rand(10000,99999); }

  $options = array(
    'checkbox'  => isset($checkbox) ? $checkbox : true,
    'delete'    => isset($delete) ? $delete : true,
    'edit'      => isset($edit) ? $edit : true,
    'comments'  => isset($comments) ? $comments : true,
    'position'  => isset($position) ? $position : false,
  );
  
  //Depending on Person permissions
  if(isset($alias))
  {
    if(!$auth->check('Project.'.$alias,'update'))
    {
      $options['checkbox'] = false;
      $options['edit'] = false;
      $options['position'] = false;
    }
    
    if(!$auth->check('Project.'.$alias,'delete'))
    {
      $options['delete'] = false;
    }
  }

  //Style
  if($options['checkbox']) { $classes[] = 'checkbox'; }
  if($options['delete']) { $classes[] = 'delete'; }
  if($options['edit']) { $classes[] = 'edit'; }
  if($options['comments']) { $classes[] = 'comments'; }
  if($options['position']) { $classes[] = 'position'; }
  
  if(isset($class))
  {
    if(!is_array($class)) { $class = array($class); }
    $classes[] = $class;
  }

?>
<div class="item <?php echo implode(' ',$classes); ?>" id="<?php echo $ident; ?>">

  <?php if($options['checkbox']): ?>
    <div class="check">
      <?php echo $form->input('test',array('type'=>'checkbox','label'=>false,'checked'=>$checked)); ?>
    </div>
  <?php endif; ?>
    
  
  <div class="name">
    <?php echo $name; ?>
    <?php if($options['comments']): ?>
      <div class="comment"><?php echo $html->link(__('Comments',true),array('action'=>'comments',$id),array('title'=>__('Comments',true))); ?></div>
    <?php endif; ?>
  </div>
  
  <?php if($options['delete'] || $options['edit'] || $options['position']): ?>
    <div class="maintain important">
    
      <?php if($options['delete']): ?>
        <span class="delete"><?php echo $html->link(__('Delete',true),array('action'=>'delete',$id),array('title'=>__('Delete',true))); ?></span>
      <?php endif; ?>
      
      <?php if($options['edit']): ?>
        <span class="edit"><?php echo $html->link(__('Edit',true),array('action'=>'edit',$id)); ?></span>
      <?php endif; ?>
      
      <?php if($options['position']): ?>
        <span class="position"></span>
      <?php endif; ?>
      
    </div>
  <?php endif; ?>
  
</div>

<?php
  echo $javascript->codeBlock("
    Account.listableDisplay('".$ident."','hide');
  ");
?>
