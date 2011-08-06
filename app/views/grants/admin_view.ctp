<?php
  $prefix = strtolower($record['Grant']['model']);
?>


<h2>Permissions, <?php echo $record['Grant']['name']; ?> for <?php echo $record['Grant']['model']; ?></h2>

<table>
<?php
    $tableHeaders = array(
        __('Id', true),
        __('Alias', true),
        __('Actions', true),
    );
    $tableHeaders =  $this->Html->tableHeaders($tableHeaders);
    echo $tableHeaders;

    $currentController = '';
    foreach ($acos AS $acoId => $alias) {
        $class = '';
        $toggle = '';
        
        if(substr($alias, 0, 2) == '__') {
            
            if(strpos($alias,'_',2) !== false)
            {
              $acoPrefix = substr($alias,2,strpos($alias,'_',2)-2);
              
              if($acoPrefix != $prefix) { continue; }
            }
        
            $level = 1;
            $class .= 'level-'.$level;
            $oddOptions = array('class' => 'hidden controller-'.$currentController);
            $evenOptions = array('class' => 'hidden controller-'.$currentController);
            $alias = substr_replace($alias, '', 0, 1);
            
            $permission = $permissions[$acoId];
            
            $toggle = $this->Html->link($this->Layout->status($permission),array('action'=>'update','aco'=>$acoId,$id,($permission?false:true)),array('escape'=>false,'class'=>'toggle'));
            
        }
        else
        {
            $level = 0;
            $class .= ' controller expand head';
            
            $oddOptions = array('class' => 'head');
            $evenOptions = array('class' => 'head');
        
            $currentController = $alias;
            $alias = substr_replace($alias, '', 0, 1);
        }
        
        $row = array(
            $id,
            $this->Html->div($class, $alias),
            $toggle
        );

        echo $this->Html->tableCells(array($row), $oddOptions, $evenOptions);
    }

    echo $tableHeaders;
?>
</table>