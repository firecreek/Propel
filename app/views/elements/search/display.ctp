<?php

  //Options
  if(!isset($showAccount)) { $showAccount = true; }

?>


<?php if(!isset($records)): ?>

  <?php
    if(!isset($defaultMessage)) { $defaultMessage = __('Enter your search terms above.',true); }
  ?>

  <p><?php echo $defaultMessage; ?></p>

<?php else: ?>

  <?php            
    $scopesOut = '';
    $count = 0;
    
    foreach($modelScopes as $key => $val)
    {
      $class = ($key == $scope) ? 'active' : null;
      $scopesOut .= $html->link($val['name'],array('?'=>array('terms'=>$terms,'scope'=>$key,'global'=>$global)),array('class'=>$class));
      
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
  
  <p class="lnk-blue scopes"><?php __('Show'); ?> <?php echo $scopeAll; ?> <?php __('or filter by'); ?> <?php echo $scopesOut; ?></p>
  
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
  
    <div id="searchResults">
    
      <?php foreach($records as $record): ?>
      
        <?php
          $_options = array(
            'badge'       => str_replace('_','-',Inflector::underscore($record['SearchIndex']['model'])),
            'name'        => Inflector::humanize($record['SearchIndex']['model']),
            'title'       => $record['SearchIndex']['title'],
            'description' => $record['SearchIndex']['description'],
            'person'      => $record['Person']['full_name'],
            'date'        => date('j M Y',strtotime($record['SearchIndex']['model_created'])),
            'url'         => array(
              'accountSlug' => $record['Account']['slug'],
              'projectId'   => $record['Project']['id'],
              'controller'  => Inflector::tableize($record['SearchIndex']['model']),
              'action'      => 'view',
              $record['SearchIndex']['model_id'],
            ),
          );
          
          //Call helper if exists
          $options = array();
          if(isset($this->{$record['SearchIndex']['model']}) && is_object($this->{$record['SearchIndex']['model']}))
          {
            $options = $this->{$record['SearchIndex']['model']}->beforeSearch($record);
          }
          
          if(!empty($options))
          {
            $options = array_merge($_options,$options);
          }
          else
          {
            $options = $_options;
          }
        ?>
      
        <div class="section plain">
          <div class="banner">
            <div class="badge <?php echo $options['badge']; ?>"><?php echo $options['name']; ?></div>
            <h3><?php echo $html->link($options['title'],$options['url'],array('class'=>'normal')); ?></h3>
          </div>
          <div class="content">
            <?php if($showAccount): ?>
              <p class="account">
                <?php echo $record['Account']['name']; ?>
                <?php if(!empty($record['Project']['name'])): ?>
                  — <?php echo $record['Project']['name']; ?>
                <?php endif; ?>
              </p>
            <?php endif; ?>
            <?php if(!empty($options['description'])): ?>
              <p class="description"><?php echo $options['description']; ?></p>
            <?php endif; ?>
            <p class="date"><?php echo $options['date']; ?></p>
          </div>
        </div>
      
      <?php endforeach; ?>
      
    </div>
  
  <?php endif; ?>

<?php endif; ?>
