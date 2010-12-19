<?php
class AcoData {

	public $table = 'acos';

	public $records = array(
		array(
			'id' => '1',
			'parent_id' => null,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'opencamp',
			'lft' => 1,
			'rght' => 8
		),
		array(
			'id' => '2',
			'parent_id' => 1,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'accounts',
			'lft' => 2,
			'rght' => 3
		),
		array(
			'id' => '3',
			'parent_id' => 1,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'projects',
			'lft' => 4,
			'rght' => 5
		),
		array(
			'id' => '4',
			'parent_id' => 1,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'controllers',
			'lft' => 6,
			'rght' => 7
		)
	);

}
?>
