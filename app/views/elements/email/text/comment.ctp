Project: <?php echo $this->Auth->read('Project.name')."\n"; ?>
Company: <?php echo $this->Auth->read('Company.name')."\n"; ?>

<?php echo $data['Person']['full_name']; ?> commented on the <?php echo $data['Extra']['alias']; ?>:
*<?php echo $data['Extra']['description']; ?>*

<?php echo $data['Comment']['body']; ?>
