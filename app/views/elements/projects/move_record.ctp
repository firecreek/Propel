<?php
  $projectOptions = $this->Layout->projectList($this->Auth->read('Projects'));
  
  $controller = Inflector::tableize($alias);
  $name = strtolower(Inflector::humanize($alias));
?>
  
  
<?php if($projectOptions): ?>

  <p class="move-project-link"><?php echo $html->link('Move...','#',array('class'=>'unimportant')); ?></p>
  
  <div class="dialog default move-project-form" style="display:none;">
    <div class="wrapper">
      <?php
        echo $form->create($alias,array('class'=>'block','url'=>array(
          'controller'  => $controller,
          'action'      => 'move_project',
          $id
        )));
        
        if(isset($this->params['form']['objId']))
        {
          echo $form->hidden('ident',array('value'=>$this->params['form']['objId']));
        }
      ?>
      
      <?php
        //Move to a different project
        echo $form->input($alias.'.project_id',array('empty'=>true,'options'=>$projectOptions,'label'=>sprintf(__('Move this %s to another project:',true),$name)));
      ?>
      
      <?php
        echo $form->submit(sprintf(__('Move this %s',true),$name),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
        echo $form->end();
      ?>
    </div>
  </div>
  
<?php endif; ?>


<?php
  echo $this->Javascript->codeBlock("
    Account.initMoveProject('".$alias."Edit".$id."');
  ");
?>

