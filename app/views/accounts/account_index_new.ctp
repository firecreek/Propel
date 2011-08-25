
<div class="cols">

  <div class="col left">
  
    <div class="box">
      <div class="banner">
        <h2><?php __('Welcome to your Propel'); ?></h2>
      </div>
      <div class="content">
        
        <?php if($this->Auth->check(array('controller'=>'projects','action'=>'add'))): ?>
          
          <div class="start-bar">
            <h2><?php echo $html->link(__('Create your first project',true),array('controller'=>'projects','action'=>'add')); ?></h2>
            <p><?php __('It takes just a few seconds and you\'ll be up and running'); ?></p>
          </div>
          
          
          <div class="section other">
            <div class="banner"><h3><?php __('Or first you can...'); ?></h3></div>
            <div class="content">
              
              <ul>
                <li>
                  <?php echo $html->link(__('Upload your logo',true),array('controller'=>'settings','action'=>'index')); ?><br />
                  Your logo will appear on the login page and on the dashboard.
                </li>
                <li>
                  <?php echo $html->link(__('Change colors',true),array('controller'=>'settings','action'=>'appearance')); ?><br />
                  Your logo will appear on the login page and on the dashboard.
                </li>
                <li>
                  <?php echo $html->link(__('Invite other people',true),array('controller'=>'companies','action'=>'index')); ?><br />
                  Your logo will appear on the login page and on the dashboard.
                </li>
              </ul>
              
              <p class="note"><span>Note:</span> You can do any of the above at any time.</p>
              
            </div>
          </div>
          
        <?php endif; ?>
        
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <!-- empty -->
  
  </div>
</div>
