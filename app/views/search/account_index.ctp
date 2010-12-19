<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php __('Search across all projects'); ?></h2>
        <?php
          if(!isset($scope)) { $scope = 'all'; }
          echo $form->create('Search',array('url'=>$this->here,'type'=>'get','class'=>'single'));
          echo $form->hidden('scope',array('value'=>$scope));
          echo $form->input('terms',array('label'=>false,'value'=>$terms));
          echo $form->submit(__('Search',true));
          echo $form->end();
        ?>
      </div>
      <div class="content">
      
        <?php if(!$search): ?>
        
          <p><?php __('Enter your search terms above.'); ?></p>
        
        <?php else: ?>
        
          <?php            
            $scopesOut = '';
            $count = 0;
            
            foreach($modelScopes as $key => $val)
            {
              $class = ($key == $scope) ? 'active' : null;
              $scopesOut .= $html->link($val,array('?'=>array('terms'=>$terms,'scope'=>$key)),array('class'=>$class));
              
              $count++;
              if($count != count($modelScopes))
              {
                $scopesOut .= ', ';
                if($count == count($modelScopes)-1) { $scopesOut .= ' and '; }
              }
            }
            
            $scopeAllClass = ($scope == 'all' || empty($scope)) ? 'active' : null;
            $scopeAll = $html->link(__('All matches',true),array('?'=>array('terms'=>$terms,'scope'=>'all')),array('class'=>$scopeAllClass));
            
          ?>
          
          <p class="lnk-blue"><?php __('Show'); ?> <?php echo $scopeAll; ?> <?php __('or filter by'); ?> <?php echo $scopesOut; ?></p>
          
          <hr />
          
          <?php if(empty($records)): ?>
          
            <p><?php __('Sorry, your search for'); ?> <strong><?php echo Sanitize::html($terms); ?></strong> <?php __('had no results'); ?>.</p>
          
            <p><?php __('Suggestions:'); ?>
          
            <ul class="bullet lnk-blue">
              <li><?php __('Check your spelling.'); ?></li>
              <?php if($scope != 'all'): ?>
                <li><?php __(sprintf('Try allowing %s instead of only messages.',$scopeAll)); ?></li>
              <?php endif; ?>
              <li><?php __('Use fewer words (results have to match the exact phrase you type).'); ?></li>
            </ul>
          
          <?php else: ?>
          
            <p>To do, show results here</p>
          
          <?php endif; ?>
        
        <?php endif; ?>
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner">
        <h3><?php __('Your recent searches across all projects'); ?></h3>
      </div>
      <div class="content">
        <p>hello!</p>
      </div>
    </div>
  
  </div>

</div>
