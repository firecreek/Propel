<?php

  $html->css('posts', null, array('inline'=>false));
  
  //Named params
  $named = array();
  if(!empty($categoryId)) { $named['category'] = $categoryId; }
  
?>
<div class="cols">
  <div class="col left">

    <div class="box">
      <div class="banner">
        <h2><?php
          if(!empty($category))
          {
            echo __('Messages in',true).' '.$category['Category']['name'];
          }
          else
          {
            echo __('All Messages',true);
          }
        ?></h2>
        <?php
          $menu = array(
            'expanded'  => array('name'=>__('Expanded view',true),'url'=>array_merge(array('action'=>'index','view'=>'expanded'),$named)),
            'list'      => array('name'=>__('List view',true),'url'=>array_merge(array('action'=>'index','view'=>'list'),$named))
          );
          
          echo $layout->menu($menu,array('active'=>$viewType),array('class'=>'right'));
        ?>
      </div>
      <div class="content post-list">
      
      
        <?php if(!empty($activeRecords)): ?>
          <div class="note">
            <div class="wrapper">
              <h4 class="important"><?php __('Most active discussions'); ?></h4>
              
              <?php foreach($activeRecords as $record): ?>
                <?php
                  $url = array('associatedController'=>'posts','controller'=>'comments','action'=>'index',$record['Post']['id']);
                ?>
                <div class="section plain">
                  <div class="banner">
                    <h3><?php echo $html->link($record['Post']['title'],$url); ?></h3>
                  </div>
                  <div class="content">
                    <div class="avatar"><?php echo $layout->avatar($record['Person']['user_id'],'small'); ?></div>
                    <p class="detail  ">
                      <?php
                        echo $html->link(__('Latest comment',true).' '.$time->timeAgoInWords($record['CommentLast']['created'],array('end'=>false)),$url);
                      ?>
                      <?php __('by'); ?> 
                      <?php echo $record['CommentLast']['Person']['full_name']; 
                    ?></p>
                    <p class="comments"><?php echo $record['Post']['comment_count']; ?> <?php __('comments posted'); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      
        <?php if(empty($records) && $categoryId): ?>
        
          <h4><?php __('There are no posts in this category yet.'); ?></h4>
          
          <p><?php echo $html->link(__('Post the first message',true),array('action'=>'add','category'=>$categoryId),array('class'=>'important')); ?></p>
        
        <?php else: ?>
          
          <?php foreach($records as $record): ?>
          
            <?php
              //Url
              $url = array('associatedController'=>'posts','controller'=>'comments','action'=>'index',$record['Post']['id']);
              
              //Comments status
              $class = null;
              
              if($record['Post']['comment_unread'] > 0)
              {
                $class = 'comments-unread';
              }
              elseif($record['Post']['comment_count'] > 0)
              {
                $class = 'comments-with';
              }
              
            ?>
            
            <?php if($viewType == 'expanded'): ?>
            
              <div class="section plain avatar <?php echo $class; ?>">
                <div class="avatar"><?php echo $layout->avatar($record['Person']['user_id']); ?></div>
                <div class="banner">
                  <p><strong><?php echo $record['Person']['full_name']; ?></strong> <?php __('posted this message'); ?> <?php echo $time->timeAgoInWords($record['Post']['created'],array('end'=>false)); ?>.</p>
                  <h3><?php echo $html->link($record['Post']['title'],$url); ?></h3>
                </div>
                <div class="content restore-html">
                  <?php
                    if($record['Post']['format'] == 'textile')
                    {
                      echo $textile->parse($record['Post']['body']);
                    }
                    else
                    {
                      echo $record['Post']['body'];
                    }
                  ?>
                </div>
                <div class="foot">
                  <ul>
                    <li><?php
                      echo $html->link(__('Go to message',true),$url);
                    ?></li>
                    <li><?php
                      if($record['Post']['comment_count'] == 0)
                      {
                        echo $html->link(__('Add a comment',true),array_merge($url,array('#comment-add')));
                      }
                      else
                      {
                        $plural = $record['Post']['comment_count'] > 1 ? 's' : null;
                        echo $html->link($record['Post']['comment_count'].' '.sprintf(__('comment%s',true),$plural),$url);
                        echo ' <span>('.__('last by',true).' '.$record['CommentLast']['Person']['full_name'].' '.$time->timeAgoInWords($record['CommentLast']['created'],array('end'=>false)).')</span>';
                      }
                    ?></li>
                  </ul>
                </div>
              </div>
            
            <?php else: ?>
            
              <div class="section list <?php echo $class; ?>">
                <div class="content">
                
                  <div class="comments">
                    <?php if($record['Post']['comment_count'] > 0): ?>
                      <?php echo $html->link($record['Post']['comment_count'],$url,array('class'=>'count')); ?>
                    <?php endif; ?>
                    <?php echo $html->link('<span></span>',$url,array('class'=>'icon','escape'=>false)); ?>
                  </div>
                  
                  <?php if($record['Post']['comment_count'] > 0): ?>
                    <p class="right last-comment"><?php echo __('Last comment by',true).' '.$record['CommentLast']['Person']['full_name'].' '.$time->timeAgoInWords($record['CommentLast']['created'],array('end'=>false)); ?></p>
                  <?php endif; ?>
                
                  <div class="detail">
                    <h3><?php echo $html->link($record['Post']['title'],$url); ?></h3>
                    <p>
                      <?php
                        echo __('by',true).' '.$record['Person']['full_name'].' '.__('on',true).' ';
                        echo date('D, j M Y \a\t g:ia',strtotime($record['Post']['created']));
                      ?>
                    </p>
                  </div>
                </div>
              </div>
            
            <?php endif; ?>
          
          <?php endforeach; ?>
          
        <?php endif; ?>
        
      </div>
    </div>
    
  </div>
  <div class="col right">
  
    <?php
      if($auth->check(array('action'=>'add')))
      {
        echo $layout->button(__('Post a new message',true),array_merge(array('action'=>'add'),$named),'large add');
      }
    ?>
    
    <?php
      //Categories
      echo $this->element('categories/side',array(
        'type' => 'post',
        'records' => $categories,
        'all' => true,
        'name' => __('Messages',true)
      ));
    ?>
    
  </div>
</div>
