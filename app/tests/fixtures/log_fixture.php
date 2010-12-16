<?php
/* Log Fixture generated on: 2010-12-16 20:12:51 : 1292507751 */
class LogFixture extends CakeTestFixture {
	var $name = 'Log';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'strike_through' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'action' => 'Lorem ipsum dolor sit amet',
			'name' => 'Lorem ipsum dolor sit amet',
			'strike_through' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:55:51',
			'created' => '2010-12-16 20:55:51'
		),
	);
}
?>