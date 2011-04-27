<?php
  //@todo Clean up this element. Can CSS grids be used instead of tables

  $html->css('people', null, array('inline'=>false));
  $javascript->link('subscribe.js', false);
  
  $people = $this->Auth->read('People');
  
  if(!empty($people)):
?>
  
  <fieldset class="subscribe-list">
    
    <h5><?php __('Subscribe people to receive email notifications'); ?></h5>
    <p class="light">
      <?php __('The people you select will get an email when you post this comment.'); ?><br />
      <?php __('They\'ll also be notified by email every time a new comment is added.'); ?>
    </p>
    
    <?php
      $grouped = Set::combine($people, '{n}.Person.id', '{n}.Person', '{n}.Company.name');
    ?>
  
    <?php
    
      echo $this->Javascript->codeBlock("
        Subscribe.company = '".$this->Auth->read('Company.name')."';
      ");
    
      $content = '';
      $perRow = 5;
      $count = 0;
    
      //
      foreach($grouped as $companyName => $people)
      {
        $count++;
        $all = false;
        
        $content .= '<tbody>';
      
        //All
        if(count($people) > 1)
        {
          $all = true;
          
          //Colspan
          $colSpan = (count($people) >= $perRow) ? $perRow : count($people);
        
          $content .= '<tr class="all" rel-company="'.$companyName.'"><td colspan="'.$colSpan.'">'.$form->input('CompanyCount.'.$count,array(
            'name'  => false,
            'type'  => 'checkbox',
            'label' => __('All of',true).' '.$companyName,
            'rel-company' => $companyName
          )).'</td></tr>';
        }
        
        $options = Set::combine($people,'{n}.id','{n}.full_name');
        
        //People
        $count = 0;
        foreach($options as $personId => $personName)
        {
          if($count == 0) { $content .= '<tr class="person" rel-company="'.$companyName.'">'; }
          
          $colSpan = null;
          $label = $personName;
          
          if(!$all)
          {
            $colSpan = $perRow;
            $label = '<strong>'.$companyName.':</strong> '.$personName;
          }
          
          $count++;
          $content .= '<td colspan="'.$colSpan.'">'.$this->Form->input('CommentPeople.'.$personId,array(
            'type'=>'checkbox',
            'label'=>$label,
            'rel-company' => $companyName
          )).'</td>';
          
          if($count == $perRow) { $content .= '</tr>'; $count = 0; }
        }
        
        $content .= '</tbody>';
      }
    ?>
      
    <table>
      <?php echo $content; ?>
    </table>
  
  </fieldset>
  
  <hr />

<?php
  endif;
?>
