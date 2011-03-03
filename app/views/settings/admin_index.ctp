
<h2><?php __('Settings'); ?></h2>

<ul>
  <?php foreach($keys as $key): ?>
    <li><?php echo $html->link($key,array('action'=>'view',$key)); ?></li>
  <?php endforeach; ?>
</ul>
