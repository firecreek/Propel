
<div class="box">
  <div class="banner">
    <h2><?php __('Every company and person in your system'); ?></h2>
    
    <?php echo $html->link(__('Add a new company',true),array('action'=>'add'),array('class'=>'button action add large')); ?>
    
  </div>
  <div class="content">
  
    <?php
      echo $session->flash();
    ?>
    
    <?php foreach($records as $company): ?>
    
      <?php
        //My company?
        $myCompany = false;
        if($company['Company']['id'] == $session->read('Auth.Company.id'))
        {
          $myCompany = true;
        }
      ?>
    
      <h3 class="section"><?php
        if($myCompany) { echo __('Your company',true).': '; }
        echo $company['Company']['name'];
      ?></h3>
      
      <p><?php echo $html->link(__('Add a new person',true),array('controller'=>'people','action'=>'add',$company['Company']['id']),array('class'=>'button action add')); ?></p>
      
      <ul class="people-list">
        <li class="company">
          <div class="detail">
            <h4><?php echo $company['Company']['name']; ?></h4>
            <p class="edit"><?php echo $html->link('Edit',array('controller'=>'companies','action'=>'edit',$company['Company']['id']),array('class'=>'lnk-red')); ?> this company</p>
          </div>
        </li>
        <?php foreach($company['People'] as $person): ?>
          <li class="person">
            <?php echo $html->image('avatar.png'); ?>
            <div class="detail">
              <h4><?php echo $person['full_name']; ?></h4>
              <?php
                //Title
                $title = $person['title'];
                if($person['company_owner'])
                {
                  $title = __('OpenCamp account owner',true);
                }
              ?>
              <?php if($title): ?><p class="title"><?php echo $title; ?></p><?php endif; ?>
              <p class="email"><?php echo $html->link($person['email'],'mailto:'.$person['email'],array('class'=>'lnk-blue')); ?></p>
              <p class="edit"><?php echo $html->link('Edit',array('controller'=>'people','action'=>'edit',$person['id']),array('class'=>'lnk-red')); ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    
    <?php endforeach; ?>
    
  </div>
</div>
