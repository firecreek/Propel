
<div id="comment-add">

  <div class="section plain avatar">
    <div class="avatar"><?php echo $layout->avatar($this->Auth->read('Person.user_id')); ?></div>
    <div class="banner">
      <h4><?php
        if(isset($edit))
        {
          $action = 'edit';
          echo __('Edit this comment...',true);
        }
        else
        {
          $action = 'add';
          echo __('Leave a comment...',true);
        }
      ?></h4>
    </div>
    <div class="content">
      <?php
        $formUrl = $html->url(array('action'=>$action,$id));
      
        echo $form->create('Comment',array('url'=>$formUrl)); 
        
        if(isset($edit) && is_numeric($edit))
        {
          echo $form->hidden('id',array('value'=>$edit));
        }
        
        echo $form->input('body',array('type'=>'textarea','label'=>false,'class'=>'wysiwyg','div'=>'input textarea editor'));
        echo $form->hidden('format',array('value'=>'textile'));
      ?>
      
      <?php
        //Subscribe people
        if(isset($subscribe) && $subscribe == true)
        {
          echo $this->element('people/subscribe');
        }
      ?>
      
      <?php
        $submitText = __('Add this comment',true);
        
        if(isset($edit))
        {
          $submitText = __('Save changes',true);
        }
        
        echo $form->submit($submitText);
        echo $form->end();
      ?>
    </div>
  </div>
  
</div>

<?php

  /*
  echo $javascript->codeBlock("
    $('#comment-add').ajaxSubmit();
  ");
  */

?>
