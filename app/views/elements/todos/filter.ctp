
<div class="area plain" id="todoFilter">
  <div class="content">
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Todos',array('url'=>$this->here,'type'=>'get','class'=>'block'));
      echo $form->input('responsible',array(
        'label'     => __('Show to-dos assigned to',true),
        'options'   => $responsibleOptions,
        'name'      => 'responsible',
        'selected'  => $this->data['Todo']['responsible']
      ));
      echo $form->input('due',array(
        'label'     => __('Show to-dos that are due',true),
        'options'   => $dueOptions,
        'name'      => 'due',
        'selected'  => $this->data['Todo']['due']
      ));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>
  </div>
</div>
