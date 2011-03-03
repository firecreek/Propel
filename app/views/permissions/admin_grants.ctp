
<h2><?php __('Grants and sets'); ?></h2>

<p><?php echo $html->link('Update Aco Alises',array('action'=>'grants','?'=>array('update'=>true))); ?></p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Alias</th>
      <th>Aco Alias</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($records as $record): ?>
      <tr>
        <td><?php echo $record['Grant']['id']; ?></td>
        <td><?php echo $record['Grant']['name']; ?></td>
        <td><?php echo $record['Grant']['alias']; ?></td>
        <td><?php echo $record['Grant']['aco_alias']; ?></td>
        <td><?php echo $html->link(__('Edit',true),array('action'=>'grant_edit',$record['Grant']['id'])); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
