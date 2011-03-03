
<h2><?php __('Grants and sets'); ?>: <?php echo $grant['Grant']['name']; ?></h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Alias</th>
      <th>Create</th>
      <th>Read</th>
      <th>Update</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($records as $record): ?>
      <tr>
        <td><?php echo $record['GrantSet']['id']; ?></td>
        <td><?php echo $record['GrantSet']['acos_alias']; ?></td>
        <td class="toggle"><?php echo $html->link($record['GrantSet']['_create'],array('action'=>'grant_set_toggle',$record['GrantSet']['id'],'create')); ?></td>
        <td class="toggle"><?php echo $html->link($record['GrantSet']['_read'],array('action'=>'grant_set_toggle',$record['GrantSet']['id'],'read')); ?></td>
        <td class="toggle"><?php echo $html->link($record['GrantSet']['_update'],array('action'=>'grant_set_toggle',$record['GrantSet']['id'],'update')); ?></td>
        <td class="toggle"><?php echo $html->link($record['GrantSet']['_delete'],array('action'=>'grant_set_toggle',$record['GrantSet']['id'],'delete')); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php

  echo $this->Javascript->codeBlock("
    
    $('table td.toggle a').bind('click',function(e){
      var self = this;
      $(this).html('...');
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('href')+'.json',
        dataType: 'json',
        success: function(data)
        {
          $(self).html(data.value);
        }
      });
      return false;
    });
    
  ");

?>
