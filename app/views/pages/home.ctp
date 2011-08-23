
<div class="box">
  <div class="content">

    <h2>Project Management with Propel</h2>
    
    <?php echo $session->flash(); ?>
    
    <ul>
      <li><?php echo $html->link('Sign up',array('controller'=>'users','action'=>'register')); ?></li>
      <li><?php echo $html->link('Login',array('controller'=>'users','action'=>'login')); ?></li>
    </ul>

  </div>
</div>
