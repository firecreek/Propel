<?php
class M4d0a1ebbc0284d8592c52d14cbdd56cb extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	var $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	var $migration = array(
		'up' => array(
			'create_table' => array(
				'accounts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'slug' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'diskspace_used' => array('type' => 'float', 'null' => false, 'length' => '10,2'),
					'project_count' => array('type' => 'integer', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'user_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'apicalls' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'ip_address' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'attachments' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'byte_size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
					'project_id' => array('type' => 'integer', 'null' => false),
					'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'model' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model_id' => array('type' => 'integer', 'null' => false),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'version' => array('type' => 'integer', 'null' => false, 'length' => 4),
					'current' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'name' => array('type' => 'string', 'null' => false, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'comments' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model_id' => array('type' => 'integer', 'null' => false),
					'body' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'companies' => array(
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
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'countries' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'logs' => array(
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
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'milestones' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'deadline' => array('type' => 'date', 'null' => true, 'default' => NULL),
					'title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'responsible_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'comments_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'completed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'completed_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'completed_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'people' => array(
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
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'posts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'milestone_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'milestone_complete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'comments_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'body' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'posts_peoples' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'post_id' => array('type' => 'integer', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'projects' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'account_id' => array('type' => 'integer', 'null' => false),
					'name' => array('type' => 'string', 'null' => false, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'status' => array('type' => 'string', 'null' => false, 'default' => 'active', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'start_controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'projects_companies' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'company_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'projects_people' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'roles' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'times' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'todo_item_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'hours' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'date' => array('type' => 'datetime', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'timezones' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'todos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'milestone_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'length' => 90, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'time_enabled' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'position' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'todos_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false),
					'todo_id' => array('type' => 'integer', 'null' => false),
					'time_total' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
					'responsible_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
					'position' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'comment_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'completed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'completed_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'completed_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'person_id' => array('type' => 'integer', 'null' => false),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'account_id' => array('type' => 'integer', 'null' => false),
					'person_id' => array('type' => 'integer', 'null' => false),
					'role_id' => array('type' => 'integer', 'null' => false, 'default' => '2'),
					'username' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'password' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'avatar_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'timezone_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'api_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ical_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ip_address' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'updated' => array('type' => 'datetime', 'null' => false),
					'created' => array('type' => 'datetime', 'null' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'accounts', 'apicalls', 'attachments', 'categories', 'comments', 'companies', 'countries', 'logs', 'milestones', 'people', 'posts', 'posts_peoples', 'projects', 'projects_companies', 'projects_people', 'roles', 'times', 'timezones', 'todos', 'todos_items', 'users'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	function after($direction) {
		if ($direction === 'up') {
			if (!class_exists('Security')) {
				App::import('Core', 'Security');
			}
	 
			//Create user roles
			$Role = $this->generateModel('Role');
			
			$Role->save(array(
				'id' => 1,
				'name' => 'admin',
			));
			$Role->save(array(
				'id' => 2,
				'name' => 'registered',
			));
	 
			//Create admin user
			$User = $this->generateModel('User');
			
			$User->save(array(
				'User' => array(
					'username' => 'admin',
					'password' => Security::hash('testadmin', null, true)),
					'role_id' => 1
			));
		}
		return true;
	}
}
?>
