
<div class="cols">

  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php __('Get started by creating your first Project'); ?></h2>
      </div>
      <div class="content">
        
        <?php if($this->Auth->check(array('controller'=>'projects','action'=>'add'))): ?>
          
          <?php
            echo $this->Form->create('Project',array('url'=>array('controller'=>'projects','action'=>'add','?'=>array('back'=>$this->here)),'class'=>'block larger'));
            echo $this->Form->hidden('Form.action',array('value'=>'project'));

            echo $this->Form->input('name',array(
              'label' => __('Name the project',true).' <span>('.__('"Home page redesign" or "Marketing ideas", etc.',true).')</span>',
              'div'   => 'input text strong full-width'
            ));
            echo $this->Form->input('description',array('label'=>__('Description',true),'div'=>'input textarea strong full-width tall-15','after'=>'<small>'.__('(Not required)',true).'</small>'));

            echo $javascript->codeBlock("
              $('#ProjectName').focus();
            ");
          ?>

          <hr />
          
          <?php  
            echo $this->Form->submit(__('Create this project',true));
            echo $this->Form->end();
          ?>
          
          
          
          
        <?php endif; ?>
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <div class="box">
      <div class="banner">
        <h3><?php __('Configure your account'); ?></h3>
      </div>
      <div class="content">

        <ul>
          <li>
            <?php echo $html->link(__('Edit your profile',true),array('controller'=>'users','action'=>'edit')); ?><br />
            <?php __('Update your name, contact details and your timezone'); ?>
          </li>
          <li>
            <?php echo $html->link(__('Upload your logo',true),array('controller'=>'settings','action'=>'index')); ?><br />
            <?php __('Add your company logo that will appear in your account'); ?>
          </li>
          <li>
            <?php echo $html->link(__('Change colors',true),array('controller'=>'settings','action'=>'appearance')); ?><br />
            <?php __('Customise the colours of your dashboard'); ?>
          </li>
          <li>
            <?php echo $html->link(__('Invite other people',true),array('controller'=>'companies','action'=>'index')); ?><br />
            <?php __('Add users and companies to your account to share projects'); ?>
          </li>
        </ul>
        
        <p class="note"><span>Note:</span> You can do any of the above at any time.</p>
              
      </div>
    </div>
  
  </div>
</div>
