<?php
  $javascript->link('listable.js', false);
  
  $controllerLower = strtolower($associatedController);
  $controllerLink  = Inflector::underscore($associatedController);
?>
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <?php
          echo $this->element($controllerLink.'/comments_banner');
        ?>
      </div>
      
      
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
