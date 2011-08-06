
<h2><?php __('Actions'); ?></h2>

<p><?php echo $html->link('Update Aco Alises',array('action'=>'actions_update')); ?></p>

<table cellpadding="0" cellspacing="0">
<?php
    $tableHeaders = array(
        __('Id', true),
        __('Alias', true),
    );
    $tableHeaders =  $this->Html->tableHeaders($tableHeaders);
    echo $tableHeaders;

    $currentController = '';
    foreach ($acos AS $id => $alias) {
        $class = '';
        if(substr($alias, 0, 1) == '_') {
            $level = 1;
            $class .= 'level-'.$level;
            $oddOptions = array('class' => 'hidden controller-'.$currentController);
            $evenOptions = array('class' => 'hidden controller-'.$currentController);
            $alias = substr_replace($alias, '', 0, 1);
        } else {
            $level = 0;
            $class .= ' controller expand';
            $oddOptions = array();
            $evenOptions = array();
            $currentController = $alias;
        }
        
        $row = array(
            $id,
            $this->Html->div($class, $alias),
        );

        echo $this->Html->tableCells(array($row), $oddOptions, $evenOptions);
    }

    echo $tableHeaders;
?>
</table>