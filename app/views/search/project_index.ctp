<?php

  $html->css('pages/search', null, array('inline'=>false));
  
?>

<div class="cols">

  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2>
          <?php if(!$global): ?>
            <?php echo sprintf(__('Search the "%s" project',true),$this->Auth->read('Project.name')); ?>
            <span>
              <?php __('or'); ?> 
              <?php echo $html->link(__('search all projects',true),array('?'=>array('terms'=>$terms,'scope'=>$scope,'global'=>true))); ?>
            </span>
          <?php else: ?>
            <?php echo __('Search all projects',true); ?>
            <span>
              <?php __('or just the'); ?> 
              <?php echo $html->link($this->Auth->read('Project.name'),array('?'=>array('terms'=>$terms,'scope'=>$scope,'global'=>false))); ?> 
              <?php __('project'); ?>
            </span>
          <?php endif; ?>
        </h2>
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
      
        <?php
          $searchAllLink = $html->link(__('search all projects',true),array('?'=>array('terms'=>$terms,'scope'=>$scope,'global'=>true)));
        
          echo $this->element('search/display',array(
            'defaultMessage' => sprintf(__('Enter your search terms above to search the %s project. You might also want to %s.',true),$this->Auth->read('Project.name'),$searchAllLink),
            'showAccount' => false
          ));
        ?>
        
      </div>
    </div>

  </div>
  <div class="col right">
  
    <div class="area">
      <div class="banner">
        <h3><?php echo sprintf(__('Your recent searches in %s',true),$this->Auth->read('Project.name')); ?></h3>
        
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
