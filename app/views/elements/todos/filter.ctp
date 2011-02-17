
<div class="area plain" id="todoFilter">
  <div class="content">
    <?php
      $responsibleOptions = $layout->permissionList($auth->read('People'));
      
      echo $form->create('Todos',array('url'=>$this->here,'type'=>'get','class'=>'block'));
      echo $form->input('responsible',array(
        'label'     => __('Show to-dos assigned to',true),
        'options'   => $responsibleOptions,
        'name'      => 'responsible',
        'selected'  => isset($this->data['Todo']['responsible']) ? $this->data['Todo']['responsible'] : null
      ));
      echo $form->input('due',array(
        'label'     => __('Show to-dos that are due',true),
        'options'   => $dueOptions,
        'name'      => 'due',
        'selected'  => isset($this->data['Todo']['due']) ? $this->data['Todo']['due'] : null
      ));
      echo $form->submit(__('Search',true));
      echo $form->end();
    ?>
  </div>
</div>
