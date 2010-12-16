<?php
/* Apicall Fixture generated on: 2010-12-16 20:12:32 : 1292506832 */
class ApicallFixture extends CakeTestFixture {
	var $name = 'Apicall';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ip_address' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'ip_address' => 'Lorem ipsum dolor sit amet',
			'user_id' => 1,
			'created' => '2010-12-16 20:40:32'
		),
	);
}
?>