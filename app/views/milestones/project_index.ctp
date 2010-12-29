
<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Milestones'); ?> <span>(<?php echo __('Today is ',true).date('j F'); ?>)</span></h2>
      </div>
      <div class="content">
        <?php echo $session->flash(); ?>
        

        <div class="section small highlight indented">
          <div class="banner">
            <h3><?php __('Upcoming milestones'); ?></h3>
          </div>
          <div class="content">
          
            <h3 class="sub"><?php __('Due in the next 14 days'); ?></h3>
            <?php
              echo $this->element('calendar/month',array('type'=>'short','month'=>date('n'),'year'=>date('Y')));
            ?>
            
            <br />
          
            <h3 class="sub"><?php __('All upcoming'); ?></h3>
            
            <?php echo $form->create('Milestones',array('url'=>$this->here)); ?>
            
            <div class="listable">
            
              <div class="group large">
                <div class="banner">
                  <h3>Today <span class="light">(Wednesday, 29 December)</span> <span class="responsibility">Darren Moore</span></h3>
                </div>
                <div class="content">
                  <?php
                    echo $this->element('listable/item',array('id'=>1,'name'=>'gfhyh hfgh trh rtwdqwsdv vs'));
                    echo $this->element('listable/item',array('id'=>1,'name'=>'as dasd asdas dasd asda sd asdasd as das das da sda sd ad ads'));
                    echo $this->element('listable/item',array('id'=>1,'name'=>'asda sd asdasdasda sd asdasdsdf ds fsdfudshf iudshfiuhds ifuh dsiuhf iudsh fiuhdsiuhfius dhiufhsiudfh idshfiuhsiudh fiudshiuf sfsdfsdf'));
                    echo $this->element('listable/item',array('id'=>1,'name'=>'df gdfgdfg dfg dg dfg dfg'));
                  ?>
                </div>
              </div>
            
              <div class="group">
                <div class="banner">
                  <h3>Today <span class="light">(Wednesday, 29 December)</span> <span class="responsibility">Darren Moore</span></h3>
                </div>
                <div class="content">
                  <?php
                    echo $this->element('listable/item',array('id'=>1,'name'=>'gfhyh hfgh trh rtwdqwsdv vs','position'=>true));
                    echo $this->element('listable/item',array('id'=>1,'name'=>'as dasd asdas dasd asda sd asdasd','position'=>true));
                  ?>
                </div>
              </div>
            
              <div class="group slim">
                <div class="banner">
                  <h3>Today <span class="light">(Wednesday, 29 December)</span> <span class="responsibility">Darren Moore</span></h3>
                </div>
                <div class="content">
                  <?php
                    echo $this->element('listable/item',array('id'=>1,'name'=>'gfhyh hfgh trh rtwdqwsdv vs','edit'=>false,'checked'=>true));
                    echo $this->element('listable/item',array('id'=>1,'name'=>'as dasd asdas dasd asda sd asdasd','edit'=>false,'checked'=>true));
                  ?>
                </div>
              </div>
              
            </div>
            
            <hr />
            
            <?php echo $form->submit(__('Submit Changes',true)); ?>
            
            <?php echo $form->end(); ?>
            
            
            
          </div>
        </div>
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    
    <?php
      if($auth->check('Project.Milestones','create'))
      {
        echo $layout->button(__('Add a new milestone',true),array('action'=>'add'),'large add');
      }
    ?>
    

    <?php
    
      //Small calendar
      echo $this->element('calendar/month',array('type'=>'small','month'=>date('n'),'year'=>date('Y')));
      
    ?>
    
    
  </div>
  
</div>

