<?php
/* Person Fixture generated on: 2010-12-16 20:12:03 : 1292507823 */
class PersonFixture extends CakeTestFixture {
	var $name = 'Person';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'company_id' => array('type' => 'integer', 'null' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_office' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_office_ext' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_mobile' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_fax' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number_home' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'company_id' => 1,
			'user_id' => 1,
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'title' => 'Lorem ipsum dolor sit amet',
			'phone_number_office' => 'Lorem ipsum dolor sit amet',
			'phone_number_office_ext' => 'Lore',
			'phone_number_mobile' => 'Lorem ipsum dolor sit amet',
			'phone_number_fax' => 'Lorem ipsum dolor sit amet',
			'phone_number_home' => 'Lorem ipsum dolor sit amet',
			'country_id' => 1,
			'updated' => '2010-12-16 20:57:03',
			'created' => '2010-12-16 20:57:03'
		),
	);
}
?>