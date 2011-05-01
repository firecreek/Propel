<?php
  //Project and company
  echo $this->element('email/text/_header');
?>

* <?php echo $data['Todo']['name']; ?> *
<?php echo $data['TodoItem']['description']; ?>


<?php __('assigned to you by'); ?> <?php echo $data['Person']['full_name']; ?>
