
if($(<?php echo $name; ?>))
{
  <?php if($count == 0): ?>
    $(<?php echo $name; ?>).find('.item-content > .count').hide();
  <?php else: ?>
    $(<?php echo $name; ?>).find('.item-content > .count').show();
    $(<?php echo $name; ?>).find('.item-content > .count span').html('<?php echo $count; ?>');
  <?php endif; ?>
}
