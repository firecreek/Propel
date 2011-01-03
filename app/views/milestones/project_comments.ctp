
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Comments on this milestone'); ?> <?php echo $html->link(__('(Back to all milestones)',true),array('action'=>'index')); ?></h2>
        
        <div class="record">
          <div class="wrapper listable"><?php
            echo $listable->item('Milestone',$id,$record['Milestone']['title'],array(
              'delete'    => false,
              'edit'      => false,
              'comments'  => false,
              'prefix'    => isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null
            ));
          ?></div>
        </div>
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
