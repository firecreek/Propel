<?php
  $this->set('activeMenu','milestones');
  $html->css('projects/milestones', null, array('inline'=>false));
?>
<div class="banner milestone-comment-head">
  <h2>
    <?php __('Comments on this Milestone'); ?>
    <?php
      echo $html->link('('.__('Back to all Milestones',true).')',array('controller'=>'milestones','action'=>'index'));
    ?>
  </h2>

  <div class="record">
    <div class="wrapper listable"><?php
      echo $this->element('milestones/comments_record');
    ?></div>
  </div>
</div>

<?php
  echo $javascript->codeBlock("
    $('.listable').listable();
  ");
?>
