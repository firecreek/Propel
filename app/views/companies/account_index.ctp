
<div class="box">
  <div class="banner">
    <h2><?php __('Every company and person in your system'); ?></h2>
    <?php echo $layout->button(__('Add a new company',true),array('action'=>'add'),'add large'); ?>
  </div>
  <div class="content">

    <?php foreach($records as $company): ?>

      <?php
        //My company?
        $myCompany = false;
        if($company['Company']['id'] == $session->read('Auth.Company.id'))
        {
          $myCompany = true;
        }
      ?>

      <div class="section">
      
        <div class="banner">
          <h3><?php
            if($myCompany) { echo __('Your company',true).': '; }
            echo $company['Company']['name'];
          ?></h3>
        </div>
        
        <div class="content">
          
          <p><?php echo $layout->button(__('Add a new person',true),array('controller'=>'people','action'=>'add',$company['Company']['id']),'large add'); ?></p>
          
          <ul class="people-list clearfix">
            <li class="company">
              <div class="detail">
                <h4><?php echo $company['Company']['name']; ?></h4>
                <p class="edit"><?php echo $html->link('Edit',array('controller'=>'companies','action'=>'edit',$company['Company']['id']),array('class'=>'important')); ?> this company</p>
              </div>
            </li>
            <?php foreach($company['People'] as $person): ?>
              <li class="person">
                <div class="avatar">
                  <?php echo $layout->avatar($person['user_id']); ?>
                  <?php if($person['company_owner']): ?>
                    <p class="label owner"><?php __('Owner'); ?></p>
                  <?php elseif($person['status'] == 'invited'): ?>
                    <p class="label invited"><?php __('Invited'); ?></p>
                  <?php endif; ?>
                </div>
                <div class="detail">
                  <h4><?php echo $person['full_name']; ?></h4>
                  <?php
                    //Title
                    $title = $person['title'];
                    if($person['company_owner'])
                    {
                      $title = __('Propel account owner',true);
                    }
                  ?>
                  <?php if($title): ?><p class="title"><?php echo $title; ?></p><?php endif; ?>
                  <p class="email"><?php echo $html->link($person['email'],'mailto:'.$person['email']); ?></p>
                  <p class="edit"><?php echo $html->link('Edit',array('controller'=>'people','action'=>'edit',$person['id']),array('class'=>'important')); ?></p>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
          
        </div>
        
      </div>
        
    <?php endforeach; ?>

    
  </div>
</div>
