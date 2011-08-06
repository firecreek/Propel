
<h2>Grants</h2>

<p><?php echo $html->link('Add Grant',array('action'=>'add')); ?></p>


<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Alias</th>
      <th>Model</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <?php foreach($records as $record): ?>
    <tr>
      <td><?php echo $record['Grant']['name']; ?></td>
      <td><?php echo $record['Grant']['alias']; ?></td>
      <td><?php echo $record['Grant']['model']; ?></td>
      <td>
        <?php echo $this->Html->link('Edit',array('action'=>'edit',$record['Grant']['id'])); ?>,
        <?php echo $this->Html->link('Permissions',array('action'=>'view',$record['Grant']['id'])); ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>