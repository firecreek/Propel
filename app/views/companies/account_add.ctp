
<div class="box">
  <div class="banner">
    <h2><?php __('Add a new company'); ?></h2>
  </div>
  <div class="content">
    
    <p><?php __('After you add a company you\'ll be able to add people to that company.'); ?></p>
  
    <?php      
      echo $form->create('Company',array('url'=>$this->here,'class'=>'block'));
      
      echo $form->input('name',array('label'=>__('Enter a new company name',true),'div'=>'input text strong half-width'));
      
      echo $form->submit(__('Create company',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
      
      echo $form->end();
    ?>
  </div>
</div>
