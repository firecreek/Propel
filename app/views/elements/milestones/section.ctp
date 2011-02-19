
<div class="section small <?php echo $class; ?> indented">
  <div class="banner">
    <h3><?php echo $title; ?></h3>
  </div>
  <div class="content">
  
    <?php if(isset($calendar) && $calendar == true): ?>
      <h3 class="sub"><?php __('Due in the next 14 days'); ?></h3>
      <?php
        echo $this->element('calendar/month',array(
          'type'=>'short',
          'month'=>date('n'),
          'year'=>date('Y'),
          'records' => isset($calendarData) ? $calendarData : null
        ));
      ?>
      <br />
      <h3 class="sub"><?php __('All upcoming'); ?></h3>
    <?php endif; ?>
  
    <div class="listable">
    
      <?php
        //Group records by date
        if(!isset($dateKey)) { $dateKey = 'deadline'; }
        
        $grouped = array();
        foreach($records as $record)
        {
          $responsible = isset($record['Responsible']['name']) ? $record['Responsible']['name'] : null;
          $key = strtotime($record['Milestone'][$dateKey]).'::'.$responsible;
          if(!isset($grouped[$key])) { $grouped[$key] = array(); }
          
          $grouped[$key][] = $record;
        }
      ?>
      
      <?php foreach($grouped as $key => $records): ?>
      
        <?php
          $split = explode('::',$key);
          $date = $split[0];
          $responsibility = $split[1];
          
          $items = array();
          foreach($records as $milestone)
          {
            //After
            $after = null;
            
            //Posts
            if(!empty($milestone['Post']))
            {
              foreach($milestone['Post'] as $afterRecord)
              {
                $after .= '<li>'.__('Message',true).': '.$html->link($afterRecord['title'],array('associatedController'=>'posts','controller'=>'comments','action'=>'index',$afterRecord['id']),array('class'=>'unimportant')).'</li>';
              }
            }
            
            //Todos
            if(!empty($milestone['Todo']))
            {
              foreach($milestone['Todo'] as $afterRecord)
              {
                $after .= '<li>'.__('To-Do',true).': '.$html->link($afterRecord['name'],array('controller'=>'todos','action'=>'view',$afterRecord['id']),array('class'=>'unimportant')).'</li>';
              }
            }
            
            if(!empty($after))
            {
              $after = '<ul class="attached-to">'.$after.'</ul>';
            }
          
            //Make item
            $items[] = array(
              'id'        => $milestone['Milestone']['id'],
              'title'     => $milestone['Milestone']['title'],
              'options'   => array(
                'ident' => 'Milestone-'.$milestone['Milestone']['id'],
                'after' => $after,
                'checked'       => (isset($checked) && $checked) ? true : false,
                'commentCount'  => $milestone['Milestone']['comment_count'],
                'commentUnread' => $milestone['Milestone']['comment_unread'],
                'editUrl'   => $html->url(array('controller'=>'milestones','action'=>'edit',$milestone['Milestone']['id'])),
                'updateUrl'   => $html->url(array('controller'=>'milestones','action'=>'update',$milestone['Milestone']['id'])),
                'deleteUrl'   => $html->url(array('controller'=>'milestones','action'=>'delete',$milestone['Milestone']['id']))
              )
            );
          }
          
          //Build title
          $titleParts = array();
          
          if($type == 'upcoming' || $type == 'overdue')
          {
            if(date('Y-m-d',$date) == date('Y-m-d'))
            {
              $titleParts[] = __('Today',true);
              $titleParts[] = '<span>('.date('l, j F',$date).')</span>';
            }
            elseif(date('Y-m-d',$date) == date('Y-m-d',strtotime('+1 day')))
            {
              $titleParts[] = __('Tomorrow',true);
              $titleParts[] = '<span>('.date('l, j F',$date).')</span>';
            }
            elseif(date('Y-m-d',$date) == date('Y-m-d',strtotime('-1 day')))
            {
              $titleParts[] = __('Yesterday',true);
              $titleParts[] = '<span>('.date('l, j F',$date).')</span>';
            }
            elseif($type == 'overdue')
            {
              //Overdue fall back
              $total = floor((time() - $date) / 86400);
            
              $titleParts[] = $total.' days ago';
              $titleParts[] = '<span>('.date('l, j F',$date).')</span>';
            }
            else
            {
              //Upcoming fall back
              $titleParts[] = date('l, j F',$date);
              
              $total = ceil(($date - time()) / 86400);
              
              if($total < 30)
              {
                $titleParts[] = '<span>('.$total.' day'.($total > 1 ? 's' : null).' away)</span>';
              }
            }
          }
          else
          {
            $titleParts[] = date('l, j F',$date);
          }
          
          if(!empty($responsibility))
          {
            $titleParts[] = '<span class="responsibility">'.$responsibility.'</span>';
          }
          
          $title = implode(' ',$titleParts);
          
          //
          echo $listable->group('Milestone',$title,$items,array('class'=>'large'));
          
        ?>
        
      <?php endforeach; ?>
    
      
    </div>
  
  </div>
</div>
