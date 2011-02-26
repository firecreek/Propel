<?php

  $javascript->link('jquery/colorpicker.js', false);
  $javascript->link('settings_appearance.js', false);
  $html->css('colorpicker', null, array('inline'=>false));
  
  
?>


<div class="box">
  <div class="banner">
    <?php echo $this->element('settings/menu'); ?>
  </div>
  <div class="content">    
    
    <div id="schemeExample">
      <header>
        <h1><?php __('Project Name'); ?> <span><?php __('Client Name'); ?></span></h1>
        <nav class="main top tabs">
          <?php
            $active = $this->name;
            $menu = array(
              'current' => array('name'=>__('Current tab',true),'url'=>false,'class'=>'active'),
              'normal' => array('name'=>__('Normal tab',true),'url'=>false),
              'hover' => array('name'=>__('Hovered tab',true),'url'=>false,'class'=>'hover'),
            );
            echo $layout->menu($menu);
          ?>
        </nav>
      </header>
      <div class="mainid">
        <div class="box">
          <div class="content"><p><?php echo $html->link(__('This is the link colour',true),'#'); ?></p></div>
        </div>
      </div>
      <div class="cover"></div>
    </div>
      
    
    <?php echo $form->create('Scheme',array('url'=>$this->here)); ?>
  
    <div class="cols scheme clearfix">
      
      <div class="col sets">
      
        <h3><?php __('Choose a colour scheme'); ?></h3>
        
        <?php
          
          //Build radio buttons
          $schemeOptions = array();
          $schemeSetsJs = null;
          
          foreach($records as $record)
          {
            $colours = Set::combine($record, 'SchemeStyle.{n}.key', 'SchemeStyle.{n}.value');
            
            $schemeKey = $record['Scheme']['id'];
            if(!empty($record['Scheme']['account_id'])) { $schemeKey = 'custom'; }
          
            $schemeOptions[$schemeKey] = '
              <span class="scheme" style="background-color:'.$colours['backgroundColor'].'">
                <span style="background-color:'.$colours['projectTextColour'].'"></span>
                <span style="background-color:'.$colours['clientTextColour'].'"></span>
                <span style="background-color:'.$colours['tabBackground'].'"></span>
              </span>
            ';
            
            $schemeData = array();
            foreach($colours as $key => $val)
            {
              $schemeData[] = $key.': "'.$val.'"';
            }
            
            $schemeSetsJs .= "colourSchemes['".$schemeKey."'] = {".implode(', ',$schemeData)."};\n\n";
          }
        
          echo $javascript->codeBlock("
            var colourSchemes = new Array;
            ".$schemeSetsJs."
          ");
          
          $schemeOptions['custom'] = __('Custom colours',true);
          
          //Selected
          $selected = isset($schemeOptions[$auth->read('Account.scheme_id')]) ? $selected = $auth->read('Account.scheme_id') : 'custom';
          
          //Output
          echo '<fieldset class="schemes">';
          echo $form->input('scheme_id',array(
            'options'   => $schemeOptions,
            'type'      => 'radio',
            'label'     => false,
            'before'    => '<div class="option">',
            'separator' => '</div><div class="option">',
            'after'     => '</div>',
            'legend'    => false,
            'value'     => $selected
          ));
          echo '</fieldset>';
          
        ?>
      
      </div>
      
      <div class="col colours">
      
          <h3><?php __('Customise the colors'); ?></h3>
          
          <?php
            foreach($schemeKeys as $key => $options)
            {
              $value = $auth->read('Style.'.$key);
            
              echo $form->hidden('SchemeStyle.'.$key,array(
                'value' => $value,
                'id'    => 'SchemeStyle'.$key,
                'class' => 'styleColour'
              ));
              
              echo '
                <div class="scheme-preview" id="SchemeStylePreview'.$key.'">
                  <p>'.$options['name'].'</p>
                  <span style="background-color:'.$value.'"></span>
                </div>
              ';
              
            }
          ?>
        
      </div>
      
      <div class="col picker">
      
        <div id="colorpicker" style="display:none;"></div>

      </div>
      
    </div>
    
    <?php
      echo $form->submit(__('Save these colours',true),array('after'=>__('or',true).' '.$html->link(__('Revert my changes',true),array('controller'=>'settings','action'=>'appearance') ) ));
      echo $form->end();
    ?>
  
  </div>
</div>

