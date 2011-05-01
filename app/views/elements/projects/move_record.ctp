<?php
  $projectOptions = $this->Layout->projectList($this->Auth->read('Projects'));
  
  $controller = Inflector::tableize($alias);
  if(!isset($name)) { $name = strtolower(Inflector::humanize($alias)); }
  
  $ident = md5(microtime());
?>
  
<?php if($projectOptions): ?>

  <div id="MoveProject<?php echo $ident; ?>">

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
        
        <?php if(isset($message) && !empty($message)): ?>
          <?php echo $message; ?>
          <hr />
        <?php endif; ?>
        
        <?php
          echo $form->submit(sprintf(__('Move this %s',true),$name),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('action'=>'index') ) ));
          echo $form->end();
        ?>
      </div>
    </div>
  </div>
  
  <?php
    echo $this->Javascript->codeBlock("
      Account.initMoveProject('MoveProject".$ident."');
    ");
  ?>

<?php endif; ?>


