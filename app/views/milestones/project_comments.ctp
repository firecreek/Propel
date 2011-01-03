
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Comments on this milestone'); ?></h2>
      </div>
      <div class="content">
        <?php
          echo $session->flash();
        ?>
        <?php
          echo $this->element('comments/list',array('comments'=>$record['Comment']));
        ?>
        <?php
          echo $this->element('comments/add');
        ?>
      </div>
    </div>

  </div>
  
  <div class="col right">
    <?php
      echo $this->element('comments/rhs');
    ?>
  </div>
  
</div>
