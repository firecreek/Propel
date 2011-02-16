
<div class="box">
  <div class="banner">
    <h2><?php __('Which company do you want to add to the project?'); ?></h2>
  </div>
  <div class="content">
    
    <p><?php __('After you add a company you\'ll be able to specify which people from this company can access the project.'); ?></p>
  
    <?php
      echo $session->flash();
    ?>
    
    <?php
      echo $form->create('Company',array('url'=>$this->here,'class'=>'block'));
    ?>
      
    <?php
      //Create
      echo $form->input('name',array('label'=>__('Enter a new company name',true),'div'=>'input text strong'));
    ?>
      
      
    <?php if(!empty($companies)): ?>
    
      <p><?php __('..or choose an existing company'); ?></p>
    
      <?php
        //Choose
        echo $form->input('id',array('options'=>$companies,'empty'=>true,'label'=>__('Choose a company',true),'div'=>'input text strong'));
      ?>
      
    <?php endif; ?>
    
    
    <?php
      echo $form->submit(__('Add company',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'permissions') ) ));
      echo $form->end();
    ?>
  </div>
</div>
