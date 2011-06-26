<?php
class AcoData {

	public $table = 'acos';

	public $records = array(
		array(
			'id' => '1',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'propel',
			'lft' => '1',
			'rght' => '192'
		),
		array(
			'id' => '2',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'models',
			'lft' => '2',
			'rght' => '3'
		),
		array(
			'id' => '3',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'controllers',
			'lft' => '4',
			'rght' => '191'
		),
		array(
			'id' => '4',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Accounts',
			'lft' => '5',
			'rght' => '8'
		),
		array(
			'id' => '5',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '6',
			'rght' => '7'
		),
		array(
			'id' => '6',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Categories',
			'lft' => '9',
			'rght' => '16'
		),
		array(
			'id' => '7',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'add',
			'lft' => '10',
			'rght' => '11'
		),
		array(
			'id' => '8',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'edit',
			'lft' => '12',
			'rght' => '13'
		),
		array(
			'id' => '9',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'delete',
			'lft' => '14',
			'rght' => '15'
		),
		array(
			'id' => '10',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Comments',
			'lft' => '17',
			'rght' => '26'
		),
		array(
			'id' => '11',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'index',
			'lft' => '18',
			'rght' => '19'
		),
		array(
			'id' => '12',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'delete',
			'lft' => '20',
			'rght' => '21'
		),
		array(
			'id' => '13',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'subscribe',
			'lft' => '22',
			'rght' => '23'
		),
		array(
			'id' => '14',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'unsubscribe',
			'lft' => '24',
			'rght' => '25'
		),
		array(
			'id' => '15',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Companies',
			'lft' => '27',
			'rght' => '44'
		),
		array(
			'id' => '16',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '28',
			'rght' => '29'
		),
		array(
			'id' => '17',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '30',
			'rght' => '31'
		),
		array(
			'id' => '18',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '32',
			'rght' => '33'
		),
		array(
			'id' => '19',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_delete',
			'lft' => '34',
			'rght' => '35'
		),
		array(
			'id' => '20',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '36',
			'rght' => '37'
		),
		array(
			'id' => '21',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_permissions',
			'lft' => '38',
			'rght' => '39'
		),
		array(
			'id' => '22',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '40',
			'rght' => '41'
		),
		array(
			'id' => '23',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '42',
			'rght' => '43'
		),
		array(
			'id' => '24',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Dashboard',
			'lft' => '45',
			'rght' => '48'
		),
		array(
			'id' => '25',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '46',
			'rght' => '47'
		),
		array(
			'id' => '26',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Milestones',
			'lft' => '49',
			'rght' => '66'
		),
		array(
			'id' => '27',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '50',
			'rght' => '51'
		),
		array(
			'id' => '28',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '52',
			'rght' => '53'
		),
		array(
			'id' => '29',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '54',
			'rght' => '55'
		),
		array(
			'id' => '30',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add_multiple',
			'lft' => '56',
			'rght' => '57'
		),
		array(
			'id' => '31',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '58',
			'rght' => '59'
		),
		array(
			'id' => '32',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update',
			'lft' => '60',
			'rght' => '61'
		),
		array(
			'id' => '33',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '62',
			'rght' => '63'
		),
		array(
			'id' => '34',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '64',
			'rght' => '65'
		),
		array(
			'id' => '35',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Pages',
			'lft' => '67',
			'rght' => '70'
		),
		array(
			'id' => '36',
			'parent_id' => '35',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'display',
			'lft' => '68',
			'rght' => '69'
		),
		array(
			'id' => '37',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'People',
			'lft' => '71',
			'rght' => '84'
		),
		array(
			'id' => '38',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '72',
			'rght' => '73'
		),
		array(
			'id' => '39',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '74',
			'rght' => '75'
		),
		array(
			'id' => '40',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_delete',
			'lft' => '76',
			'rght' => '77'
		),
		array(
			'id' => '41',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_invite_resend',
			'lft' => '78',
			'rght' => '79'
		),
		array(
			'id' => '42',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '80',
			'rght' => '81'
		),
		array(
			'id' => '43',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '82',
			'rght' => '83'
		),
		array(
			'id' => '44',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Permissions',
			'lft' => '85',
			'rght' => '92'
		),
		array(
			'id' => '45',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '86',
			'rght' => '87'
		),
		array(
			'id' => '46',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_actions',
			'lft' => '88',
			'rght' => '89'
		),
		array(
			'id' => '47',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_actions_update',
			'lft' => '90',
			'rght' => '91'
		),
		array(
			'id' => '48',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Posts',
			'lft' => '93',
			'rght' => '104'
		),
		array(
			'id' => '49',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '50',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '51',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '98',
			'rght' => '99'
		),
		array(
			'id' => '52',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '100',
			'rght' => '101'
		),
		array(
			'id' => '53',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '102',
			'rght' => '103'
		),
		array(
			'id' => '54',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Projects',
			'lft' => '105',
			'rght' => '114'
		),
		array(
			'id' => '55',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '106',
			'rght' => '107'
		),
		array(
			'id' => '56',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_start',
			'lft' => '108',
			'rght' => '109'
		),
		array(
			'id' => '57',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '110',
			'rght' => '111'
		),
		array(
			'id' => '58',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '59',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Search',
			'lft' => '115',
			'rght' => '124'
		),
		array(
			'id' => '60',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '116',
			'rght' => '117'
		),
		array(
			'id' => '61',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '118',
			'rght' => '119'
		),
		array(
			'id' => '62',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_forget',
			'lft' => '120',
			'rght' => '121'
		),
		array(
			'id' => '63',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_forget',
			'lft' => '122',
			'rght' => '123'
		),
		array(
			'id' => '64',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Settings',
			'lft' => '125',
			'rght' => '138'
		),
		array(
			'id' => '65',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '126',
			'rght' => '127'
		),
		array(
			'id' => '66',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_view',
			'lft' => '128',
			'rght' => '129'
		),
		array(
			'id' => '67',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '130',
			'rght' => '131'
		),
		array(
			'id' => '68',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_logo',
			'lft' => '132',
			'rght' => '133'
		),
		array(
			'id' => '69',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_appearance',
			'lft' => '134',
			'rght' => '135'
		),
		array(
			'id' => '70',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_categories',
			'lft' => '136',
			'rght' => '137'
		),
		array(
			'id' => '71',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Todos',
			'lft' => '139',
			'rght' => '156'
		),
		array(
			'id' => '72',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '140',
			'rght' => '141'
		),
		array(
			'id' => '73',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '142',
			'rght' => '143'
		),
		array(
			'id' => '74',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_view',
			'lft' => '144',
			'rght' => '145'
		),
		array(
			'id' => '75',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '146',
			'rght' => '147'
		),
		array(
			'id' => '76',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '148',
			'rght' => '149'
		),
		array(
			'id' => '77',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '150',
			'rght' => '151'
		),
		array(
			'id' => '78',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '152',
			'rght' => '153'
		),
		array(
			'id' => '79',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update_positions',
			'lft' => '154',
			'rght' => '155'
		),
		array(
			'id' => '80',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'TodosItems',
			'lft' => '157',
			'rght' => '168'
		),
		array(
			'id' => '81',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '158',
			'rght' => '159'
		),
		array(
			'id' => '82',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '160',
			'rght' => '161'
		),
		array(
			'id' => '83',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '162',
			'rght' => '163'
		),
		array(
			'id' => '84',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update',
			'lft' => '164',
			'rght' => '165'
		),
		array(
			'id' => '85',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update_positions',
			'lft' => '166',
			'rght' => '167'
		),
		array(
			'id' => '86',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Users',
			'lft' => '169',
			'rght' => '184'
		),
		array(
			'id' => '87',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'register',
			'lft' => '170',
			'rght' => '171'
		),
		array(
			'id' => '88',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'invitation',
			'lft' => '172',
			'rght' => '173'
		),
		array(
			'id' => '89',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'login',
			'lft' => '174',
			'rght' => '175'
		),
		array(
			'id' => '90',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'logout',
			'lft' => '176',
			'rght' => '177'
		),
		array(
			'id' => '91',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'forgotten',
			'lft' => '178',
			'rght' => '179'
		),
		array(
			'id' => '92',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'reset',
			'lft' => '180',
			'rght' => '181'
		),
		array(
			'id' => '93',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '182',
			'rght' => '183'
		),
		array(
			'id' => '94',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'ToolbarAccess',
			'lft' => '185',
			'rght' => '190'
		),
		array(
			'id' => '95',
			'parent_id' => '94',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'history_state',
			'lft' => '186',
			'rght' => '187'
		),
		array(
			'id' => '96',
			'parent_id' => '94',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'sql_explain',
			'lft' => '188',
			'rght' => '189'
		),
	);

}
?>