<?php

  if(!isset($class)) { $class = null; }

  $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
  $firstDayInMonth = date('D', mktime(0,0,0, $month, 1, $year));
  $today = false;

  if($year == date('Y') && $month == date('n'))
  {	
    $today = date('j');
  }

  $dayList = array(
    __('Mon',true),
    __('Tue',true),
    __('Wed',true),
    __('Thu',true),
    __('Fri',true),
    __('Sat',true),
    __('Sun',true)
  );
  
?>

<table class="calendar<?php if(!empty($class)) { echo ' '.$class; } ?>">
  <thead>
    <tr>
      <th>&nbsp;</th>
      <?php foreach($dayList as $day): ?>
        <th><?php echo $day; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php

      $str = '';

      $day = 1;
      while($day <= $daysInMonth)
      {
        if($day == 1) { echo '<tr><th class="month" rowspan="7"><div>'.date('M',mktime(0,0,0,$month,1,$year)).'</div></th></tr>'; }
        
        $str .= '<tr class="days">';
        
     
        for($i = 0; $i < 7; $i ++)
        {
          $cell = '&nbsp;';
          $class = array();
     
          if($i > 4) {
            $class[] = 'weekend';
          }
          else
          { 
            $class[] = 'day';
          }
     
          if($day == $today) {
            $class[] = 'today';
          }
     
          if(($firstDayInMonth == $dayList[$i] || $day > 1) && ($day <= $daysInMonth))
          {
            $dayDisplay = ($day == $today) ? __('Today',true) : $day;
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
