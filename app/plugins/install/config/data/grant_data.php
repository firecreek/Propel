<?php
class GrantData {

	public $table = 'grants';

	public $records = array(
		array(
			'id' => '1',
			'alias' => 'owner',
			'name' => 'Owner',
			'model' => 'Account',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '2',
			'alias' => 'shared',
			'name' => 'Shared',
			'model' => 'Account',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '3',
			'alias' => 'owner',
			'name' => 'Owner',
			'model' => 'Project',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '4',
			'alias' => 'shared',
			'name' => 'Shared',
			'model' => 'Project',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
	);

}
?>