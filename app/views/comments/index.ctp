<?php
  $javascript->link('listable.js', false);
  $javascript->link('accounts/comments.js', false);
  $javascript->link('jquery/jquery-countdown.js', false);
  $javascript->link('jquery/jquery-rte.js', false);
  
  $html->css('comments', null, array('inline'=>false));
  $html->css('rte', null, array('inline'=>false));
  
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <?php
        echo $this->element(Inflector::underscore($associatedController).'/comments_head');
      ?>      
      
      <div class="content">
        <div class="comments">
          <?php
            echo $this->element('comments/list',array('comments'=>$record['Comment']));
          ?>
          <?php
            //Add comment if has permission
            if($this->Auth->check(array('controller'=>'comments','action'=>'add'),array('prefix'=>false)))
            {
              echo $this->element('comments/add');
            }
          ?>
        </div>
      </div>
    </div>

  </div>
  
  <div class="col right">
    <?php
      echo $this->element('comments/rhs');
    ?>
  </div>
  
</div>
