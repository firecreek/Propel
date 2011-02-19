<?php

  //Settings
  if(!isset($class)) { $class = array(); }
  elseif(is_string($class)) { $class = array($class); }

  
  if(!isset($type)) { $type = 'full'; }
  
  $class[] = $type;

  //
  $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
  $firstDayInMonth = date('D', mktime(0,0,0, $month, 1, $year));
  $today = false;

  if($year == date('Y') && $month == date('n'))
  {
    $today = date('j');
  }
  
  //Settings
  $showMonth = true;
  $todayText = true;
  $continueDates = false;
  $monthPosition = 'top';
  $dayEnd = $daysInMonth;
  $showEvents = true;
  $day = 1;

  //Types
  if($type == 'full')
  {
    //Full page calendar
    $monthPosition = 'inner';
    
    $dayList = array(
      __('Mon',true),
      __('Tue',true),
      __('Wed',true),
      __('Thu',true),
      __('Fri',true),
      __('Sat',true),
      __('Sun',true)
    );
  }
  elseif($type == 'small')
  {
    //Small calendar
    $todayText = false;
    $showEvents = false;
    
    $dayList = array(
      __('Mon',true),
      __('Tue',true),
      __('Wed',true),
      __('Thu',true),
      __('Fri',true),
      __('Sat',true),
      __('Sun',true)
    );
    
    $dayHeader = array('M','T','W','T','F','S','S');
  }
  elseif($type == 'select')
  {
    //Small calendar
    $showMonth = false;
    $todayText = false;
    $showEvents = false;
    
    $dayList = array(
      __('Mon',true),
      __('Tue',true),
      __('Wed',true),
      __('Thu',true),
      __('Fri',true),
      __('Sat',true),
      __('Sun',true)
    );
    
    $dayHeader = array('M','T','W','T','F','S','S');
  }
  elseif($type == 'short')
  {
    //14 days ahead short calendar with trailing days
    $showMonth = false;
    $continueDates = true;
    $day = $today;
    
    $dayEnd = $day + 13;
    
    $dayList = array();
    for($ii = 0; $ii < 7; $ii++)
    {
      $dayList[] = date('D',strtotime('+'.$ii.' day'));
    }
  }
  
  //Sort event dates
  //@todo Move this to model behavior, eventable or something
  $events = array();
  if(isset($records) && !empty($records))
  {
    foreach($records as $record)
    {
      $date = strtotime($record['Milestone']['deadline']);
      
      if(!isset($events[$date])) { $events[$date] = array(); }
      
      /*$events[$date][] = array(
        'title' => $record['Milestone']['title'],
        'url'   => array(
          'controller'  => 'milestones',
          'action'      => 'index',
          'projectId'   => $record['Milestone']['project_id'],
          '#Milestone-'.$record['Milestone']['id']
        ),
      );*/
      $events[$date][] = array(
        'title' => $record['Milestone']['title'],
        'url'   => '#Milestone-'.$record['Milestone']['id'],
        'class' => isset($record['class']) ? $record['class'] : null
      );
    }
  }
  
?>

<table class="calendar<?php if(!empty($class)) { echo ' '.implode(' ',$class); } ?>">
  <thead>
    <tr>
      <?php if($showMonth): ?>
        <?php if($monthPosition == 'top'): ?>
          <th class="month" rowspan="8"><?php echo date('M',mktime(0,0,0,$month,1,$year)); ?></th>
        <?php else: ?>
          <th>&nbsp;</th>
        <?php endif; ?>
      <?php endif; ?>
      <?php foreach($dayList as $key => $dayName): ?>
        <th><?php echo (isset($dayHeader) && isset($dayHeader[$key])) ? $dayHeader[$key] : $dayName; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
  
    <?php if($showMonth && $monthPosition == 'inner'): ?>
      <tr><th class="month" rowspan="7"><div><?php echo date('M',mktime(0,0,0,$month,1,$year)); ?></div></th></tr>
    <?php endif; ?>
  
    <?php

      $str = '';
      $dayShowMonth = false;
      
      while($day <= $dayEnd)
      {
        $str .= '<tr class="days">';
        
        if($showMonth && $monthPosition != 'inner') { $str .= '<td></td>'; }
     
        for($i = 0; $i < 7; $i ++)
        {
          $cell = '&nbsp;';
          $class = array();
     
          if($i > 4)
          {
            $class[] = 'weekend';
          }
          else
          { 
            $class[] = 'day';
          }
     
          if($day == $today)
          {
            if($continueDates) { $dayShowMonth = true; }
            $class[] = 'today';
          }
     
          if(($firstDayInMonth == $dayList[$i] || $day > 1) && ($day <= $daysInMonth || $continueDates == true))
          {
            $dayDisplay = ($day == $today && $todayText) ? __('Today',true) : $day;
            
            //Defaults
            $cmonth = $month;
            $cday   = $day;
            $cyear  = $year;
            
            //Continue days?
            if($day > $daysInMonth)
            {
              $ahead = strtotime('+1 month',mktime(0, 0, 0, $month, 1, $year));
                
              $dayDisplay = $day - $daysInMonth;
              
              //Ammend date
              $cmonth = date('n',$ahead);
              $cyear = date('Y',$ahead);
              $cday = $dayDisplay;
              
              if($continueDates && $dayDisplay == 1)
              {
                $dayDisplay = date('M',$ahead).' '.$dayDisplay;
              }
            }
            
            //Show month appending day
            if($day != $today && $dayShowMonth)
            {
              $dayShowMonth = false;
              $dayDisplay = date('M').' '.$dayDisplay;
            }
            
            //Check for events
            $eventDate = mktime(0, 0, 0, $cmonth, $cday, $cyear);
            if(isset($events[$eventDate]))
            {
              $class[] = 'with';
              $cell = '';
            
              foreach($events[$eventDate] as $event)
              {
                if(isset($event['class']))
                {
                  $class[] = $event['class'];
                }
                if($showEvents)
                {
                  $cell .= '<div class="event">'.$html->link($event['title'],$event['url']).'</div>';
                }
              }
            }
            
            //Build
            $str .= '
              <td class="'.implode(' ',$class).'">
                <div class="wrapper">
                  <div class="day">' . $dayDisplay . '</div>
                  <div class="data">' . $cell . '</div>
                </div>
              </td>
            ';
            $day++;
          }
          else
          {
            $str .= '<td class="empty">&nbsp;</td>';
          }
          
        }
        $str .= '</tr>';
      }
     
      echo $str;
    
    ?>
  </tbody>
</table>
