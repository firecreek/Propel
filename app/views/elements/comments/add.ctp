
<div id="comment-add">

  <div class="section plain avatar">
    <div class="avatar"><?php echo $html->image('avatar.png'); ?></div>
    <div class="banner">
      <h4><?php
        if(isset($edit))
        {
          echo __('Edit this comment...',true);
        }
        else
        {
          echo __('Leave a comment...',true);
        }
      ?></h4>
    </div>
    <div class="content">
      <?php
        $formUrl = $html->url(array('action'=>'index',$id));
      
        echo $form->create('Comment',array('url'=>$formUrl)); 
        echo $form->input('body',array('type'=>'textarea','label'=>false,'class'=>'wysiwyg'));
        
        if(isset($edit) && is_numeric($edit))
        {
          echo $form->hidden('id',array('value'=>$edit));
        }
      ?>
      
      <?php
        $people = $layout->notificationList($auth->read('People'));
        if(!empty($people)):
      ?>
        
        <h5><?php __('Subscribe people to receive email notifications'); ?></h5>
        <p>
          <?php __('The people you select will get an email when you post this comment.'); ?><br />
          <?php __('They\'ll also be notified by email every time a new comment is added.'); ?>
        </p>
        
        <?php
          echo $form->input('CommentPeople.person_id',array(
            'multiple'  => 'checkbox',
            'options'   => $people,
            'label'     => false,
            'escape'    => false,
            'legend'    => false,
            'prefixResponsible' => true
          ));
        ?>
      
      <?php
        endif;
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
