
<div class="cols">
  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php __('Project Settings'); ?></h2>
      </div>
      <div class="content">
      
        <?php
          echo $form->create('Project',array('url'=>$this->here,'class'=>'basic'));
        ?>
        
        <h3><?php __('Project name'); ?></h3>
        <p class="intro"><?php __('The project name appears at the top of every page.'); ?></p>
        <fieldset class="tight">
        <?php
          echo $form->input('name',array('label'=>false));
        ?>
        </fieldset>
        
        <h3><?php __('Overview page announcement'); ?></h3>
        <p class="intro"><?php __('Create an announcement that appears at the top of this project\'s Overview page. You can use this to describe the project, to make a special announcement, etc. Just enter the text below and check the "Yes, display this announcement" checkbox.'); ?></p>
        <fieldset class="tight">
        <?php
          echo $form->input('announcement',array('label'=>false));
          echo $form->input('announcement_show',array('label'=>__('Yes, display this announcement on the overview page',true)));
        ?>
        </fieldset>
        
        <h3><?php __('Start page'); ?></h3>
        <p class="intro"><?php __('This is the first page people see when they view this project.'); ?></p>
        <fieldset class="tight">
        <?php
          $options = array(
            ''            => 'Overview',
            'messages'    => 'Messages',
            'todos'       => 'To-Dos',
            'milestones'  => 'Milestones',
          );
          echo $form->input('start_controller',array('label'=>false,'options'=>$options));
        ?>
        </fieldset>
        
        <h3><?php __('Select the primary company for this project'); ?></h3>
        <p class="intro"><?php __('Select the company you want this project to be associated with in the "Your Projects" list on the Dashboard. The company name will also be displayed at the top of each project page and elsewhere where necessary. Finally, the company logo will appear at the top of the All Messages page for this project.'); ?></p>
        <fieldset class="tight">
        <?php
          echo $form->input('company_id',array('label'=>__('File under this company',true)));
        ?>
        </fieldset>
        
        <hr />
      
        <?php
          echo $form->submit(__('Save changes',true));
          echo $form->end();
        ?>
      
      </div>
    </div>
  
  
  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner"><h3><?php __('Delete this project?'); ?></h3></div>
      <div class="content">
        <p><?php
          __('Deleting a project immediately and permanently deletes all the data associated with this project (messages, milestones, to-do lists, files, writeboards, etc). There is no Undo so make sure you\'re absolutely sure you want to delete this project.');
        ?></p>
        <p><?php echo $html->link(__('Yes, I understand — delete this project',true),array('action'=>'delete'),array('confirm'=>__('Are you sure you want to delete this project? Node: There is no undo.',true))); ?></p>
      </div>
    </div>
  
  </div>
</div>
