
<h2><?php __('Settings'); ?>: <?php echo $key; ?></h2>

<?php
  echo $form->create('Setting',array('class'=>'block','url'=>$this->here));
?>

<?php foreach($records as $record): ?>
  <?php
    echo $form->input($record['Setting']['key'],array(
      'label' => $record['Setting']['title'],
      'after' => $record['Setting']['description'],
      'type'  => $record['Setting']['input_type'],
      'value'  => $record['Setting']['value']
    ));
  ?>
<?php endforeach; ?>

<?php
  echo $form->submit();
  echo $form->end();
?>

