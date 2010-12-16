<?php
/* Company Fixture generated on: 2010-12-16 20:12:47 : 1292507687 */
class CompanyFixture extends CakeTestFixture {
	var $name = 'Company';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => false),
		'name' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address_one' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address_two' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'zip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'web_address' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_office' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_office_ext' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_fax' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timezone_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'person_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'account_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'address_one' => 'Lorem ipsum dolor sit amet',
			'address_two' => 'Lorem ipsum dolor sit amet',
			'city' => 'Lorem ipsum dolor sit amet',
			'state' => 'Lorem ipsum dolor sit amet',
			'zip' => 'Lorem ipsum dolor sit amet',
			'country_id' => 'Lorem ipsum dolor sit amet',
			'web_address' => 'Lorem ipsum dolor sit amet',
			'phone_number_office' => 'Lorem ipsum dolor sit amet',
			'phone_number_office_ext' => 'Lore',
			'phone_number_fax' => 'Lorem ipsum dolor sit amet',
			'timezone_id' => 1,
			'private' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:54:47',
			'created' => '2010-12-16 20:54:47'
		),
	);
}
?>