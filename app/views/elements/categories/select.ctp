<?php

  echo $form->input('category_id',array(
    'label'       => __('Category',true),
    'options'     => $this->Layout->categoryList($categories),
    'empty'       => true,
    'selected'    => isset($this->params['named']['category']) ? $this->params['named']['category'] : null,
    'div'         => 'input select category-select',
    'rel-add-url' => $this->Html->url($addUrl),
    'after'       => '<div class="saving" style="display:none;"></div>'
  ));
  
?>
