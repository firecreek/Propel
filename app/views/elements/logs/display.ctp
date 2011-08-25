<?php
  if(!isset($pagination))   { $pagination = true; }
  if(!isset($dateHeader))   { $dateHeader = true; }
  if(!isset($dateColumn))   { $dateColumn = false; }
  if(!isset($showProject))  { $showProject = false; }

  $lastDate = null;
?>

<table class="std logs dated">
  <?php if(isset($header)): ?>
    <thead>
      <tr>
        <th colspan="5"><h3><?php echo $header; ?></h3></td>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach($logs as $log): ?>
    
      <?php
        //Dates
        $createdTs = strtotime($log['Log']['created']);
        $today = false;
        
        if(date('Y-m-d',$createdTs) == date('Y-m-d'))
        {
          $today = true;
        }
      ?>
    
      <?php if($dateHeader && (empty($lastDate) || $lastDate != date('Y-m-d',$createdTs))): ?>
        <?php $lastDate = date('Y-m-d',$createdTs); ?>
        <tr class="date<?php if($today) { echo ' today'; } ?>">
          <td colspan="5">
            <h4><span><?php
              if($today)
              {
                echo __('Today',true).' <div>('.date('l, j F Y').')</div>';
              }
              else
              {
                echo date('l, j F Y',$createdTs);
              }
            ?></span></h4>
          </td>
        </tr>
      <?php endif; ?>
    
      <?php
        $_options = array(
          'badge'       => str_replace('_','-',Inflector::underscore($log['Log']['model'])),
          'name'        => Inflector::humanize($log['Log']['model']),
          'description' => $log['Log']['description'],
          'action'      => null,
          'person'      => $log['Person']['first_name'].' '.substr($log['Person']['last_name'],0,1).'.',
          'class'       => $log['Log']['action'],
        );
        
        //Call helper if exists
        $options = array();
        if(isset($this->{$log['Log']['model']}) && is_object($this->{$log['Log']['model']}))
        {
          $options = $this->{$log['Log']['model']}->beforeLog($log);
        }
        
        if(!empty($options))
        {
          $options = array_merge($_options,$options);
        }
        else
        {
          $options = $_options;
        }
        
        //Project name
        if($showProject && isset($log['Account']['id']) && isset($log['Project']['id']))
        {
          $options['description'] .= ' <span class="project">['.$this->Html->link($log['Project']['name'],array('accountSlug'=>$log['Account']['slug'],'projectId'=>$log['Project']['id'],'controller'=>'projects','action'=>'index')).']</span>';
        }
        
        //If no name
        if(empty($options['action']))
        {
          switch($log['Log']['action'])
          {
            case 'create':
            case 'add':
              $options['action'] = __('Posted by',true);
              break;
            case 'delete':
              $options['action'] = __('Deleted by',true);
              break;
            case 'edit':
              $options['action'] = __('Edited by',true);
              break;
            default:
              $options['action'] = ucwords($log['Log']['action']).' '.__('by',true);
          }
        }
        
        
      ?>
      <tr class="<?php echo $options['class']; ?>">
        <td class="type"><div class="badge <?php echo $options['badge']; ?>"><?php echo $options['name']; ?></div></td>
        <td class="description"><?php echo $options['description']; ?></td>
        <td class="action"><?php echo $options['action']; ?></td>
        <td class="person"><?php echo $options['person']; ?></td>
        <?php if($dateColumn): ?>
          <td class="date"><?php
            if($today)
            {
              echo '<span class="highlight-bright">'.__('Today',true).'</span>';
            }
            else
            {
              echo date('M j',$createdTs);
            }
          ?></td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
</table>

<?php if($pagination): ?>
  <p class="pagination">
    <?php echo $this->Paginator->prev('Previous page', null, null, array('class' => 'disabled')); ?>
    |
    <?php echo $this->Paginator->next('Next page', null, null, array('class' => 'disabled')); ?> 
  </p>
<?php endif; ?>

