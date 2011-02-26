<?php

  $html->css('pages/search', null, array('inline'=>false));
  
?>

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
      
        <?php echo $this->element('search/display'); ?>
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner">
        <h3><?php __('Your recent searches across all projects'); ?></h3>
        
        <?php if(!empty($recentSearches)): ?>
          <ul class="right">
            <li><?php echo $html->link(__('Clear',true),array('action'=>'forget')); ?></li>
          </ul>
        <?php endif; ?>
        
      </div>
      <div class="content">
        
        <?php if(empty($recentSearches)): ?>
        
          <p><?php __('Your searches will be saved here for future reference.'); ?></p>
        
        <?php else: ?>
        
          <ul>
            <?php foreach($recentSearches as $recentSearch): ?>
              <li><?php echo $html->link($recentSearch['terms'],array('action'=>'index','?'=>$recentSearch),array('class'=>'lnk-blue')); ?></li>
            <?php endforeach; ?>
          </ul>
        
        <?php endif; ?>
        
      </div>
    </div>
  
  </div>

</div>
