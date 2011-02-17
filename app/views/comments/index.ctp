<?php
  $javascript->link('listable.js', false);
  
  $html->css('pages/comments', null, array('inline'=>false));
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <?php
        echo $this->element(Inflector::underscore($associatedController).'/comments_head');
      ?>      
      
      <div class="content">
        <?php
          echo $session->flash();
        ?>
        <div class="comments">
          <?php
            echo $this->element('comments/list',array('comments'=>$record['Comment']));
          ?>
          <?php
            echo $this->element('comments/add');
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