<?php
class ArosAco extends AppModel {

    var $name = 'ArosAco';
    var $useTable = 'aros_acos';
    
    var $belongsTo = array(
        'Aro' => array(
            'className' => 'Aro',
            'foreignKey' => 'aro_id',
        ),
        'Aco' => array(
            'className' => 'Aco',
            'foreignKey' => 'aco_id',
        ),
    );

}
?>