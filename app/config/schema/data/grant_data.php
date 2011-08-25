<?php
class GrantData {

	public $table = 'Grants';

	public $records = array(
		array(
			'id' => '1',
			'alias' => 'owner',
			'name' => 'Owner',
			'model' => 'Account',
			'position' => '0',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '2',
			'alias' => 'shared',
			'name' => 'Shared',
			'model' => 'Account',
			'position' => '0',
			'updated' => '0000-00-00 00:00:00',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '3',
			'alias' => 'owner',
			'name' => 'Admin',
			'model' => 'Project',
			'position' => '4',
			'updated' => '2011-08-21 18:26:29',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '4',
			'alias' => 'shared',
			'name' => 'Message & Files',
			'model' => 'Project',
			'position' => '1',
			'updated' => '2011-08-21 18:26:21',
			'created' => '0000-00-00 00:00:00'
		),
		array(
			'id' => '5',
			'alias' => 'shared-level1',
			'name' => '...plus To-dos',
			'model' => 'Project',
			'position' => '2',
			'updated' => '2011-08-21 18:26:24',
			'created' => '2011-08-21 16:58:15'
		),
		array(
			'id' => '6',
			'alias' => 'shared-level2',
			'name' => '...plus Milestones',
			'model' => 'Project',
			'position' => '3',
			'updated' => '2011-08-21 18:26:27',
			'created' => '2011-08-21 16:58:28'
		),
	);

}
?>