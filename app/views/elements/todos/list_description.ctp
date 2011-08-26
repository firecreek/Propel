<?php

  /**
   * Used in elements/todos/list.ctp and todos/js/project_edit.ctp
   */

  if(isset($record['Milestone']['id']) && !empty($record['Milestone']['id']))
  {
    $date = date('j M',strtotime($record['Milestone']['deadline']));
    echo __('Related milestone: ',true);
    echo $html->link($date.' - '.$record['Milestone']['title'],array('controller'=>'milestones','action'=>'view',$record['Milestone']['id']),array('class'=>'unimportant'));
  }

?>
