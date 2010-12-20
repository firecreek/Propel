
<div class="box">
  <div class="banner">
    <h2><?php __('Edit'); ?> <?php echo $record['Company']['name']; ?></h2>
    <?php if(!$record['Company']['account_owner']): ?>
      <ul class="right">
        <li><?php echo $html->link(__('Delete this company',true),array('action'=>'delete',$companyId),array('class'=>'red','confirm'=>__('Are you sure you want to delete this company and all people associated with it?',true))); ?></li>
      </ul>
    <?php endif; ?>
  </div>
  <div class="content">
    
    <p><?php __('Only the company name is required here, but the rest will come in handy when you want to take your communication offline'); ?></p>
  
    <?php
      echo $session->flash();
      
      echo $form->create('Company',array('url'=>$this->here,'class'=>'basic'));
      
      echo '<fieldset>';
      echo $form->input('name',array('label'=>__('Company name',true),'div'=>'input text strong'));
      echo $form->input('address_one');
      echo $form->input('address_two');
      echo $form->input('city');
      echo $form->input('state');
      echo $form->input('zip',array('label'=>__('ZIP/Postal Code',true)));
      echo $form->input('country_id',array('empty'=>true));
      echo $form->input('web_address',array('after'=>'<small>'.__('(ex: http://www.site.com)',true).'</small>'));
      echo $form->input('phone_number_office',array('label'=>__('Office',true).' #'));
      echo $form->input('phone_number_fax',array('label'=>__('Fax',true).' #'));
      echo '</fieldset>';
    ?>
    
    <?php if(!$record['Company']['account_owner']): ?>
      <h3><?php __('Can the people in this company view Private items?'); ?></h3>
      <p><?php __('Normally the only people who can view private messages and to-dos are people in your own company, but this setting allows you to grant people in other companies the ability to see private items. Use this option with care.'); ?></p>
      <fieldset class="tight">
        <?php
          echo $form->input('private',array('label'=>__('Yes, allow everyone in this company to view items marked Private',true)));
        ?>
      </fieldset>
    <?php endif; ?>
    
    <?php
      echo $form->submit(__('Save changes',true),array('after'=>__('or',true).' '.$html->link(__('Cancel',true),array('controller'=>'companies','action'=>'index') ) ));
      
      echo $form->end();
    ?>
  </div>
</div>
