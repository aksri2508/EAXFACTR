<?php
// MASTER ALTERNATIVE ITEMS 
$config['MASTER_ALTERNATIVE_ITEMS_LIST']['columns_list']	= array(
	array(
		'display_name'		=> 'Id',
		'tbl_field_name' 	=> 'id',
		'field_key' 		=> 'id',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 1,
		'update_flag'		=> 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Master Alternative Item Id',
		'tbl_field_name' 	=> 'master_alternative_item_id',
		'field_key' 		=> 'masterAlternativeItemId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 0,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Alt Item Id',
		'tbl_field_name' 	=> 'alt_item_id',
		'field_key' 		=> 'altItemId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 1,
		'edit_flag'		    => 1,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Remarks',
		'tbl_field_name' 	=> 'remarks',
		'field_key' 		=> 'remarks',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 1,
		'edit_flag'		    => 1,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Match Factor',
		'tbl_field_name' 	=> 'match_factor',
		'field_key' 		=> 'matchFactor',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 1,
		'edit_flag'		    => 1,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
		),
		array(
			'display_name'		=> 'Sap Id',
			'tbl_field_name' 	=> 'sap_id',
			'field_key' 		=> 'sapId',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 1,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
		),
		array(
			'display_name'		=> 'Posting Status',
			'tbl_field_name' 	=> 'posting_status',
			'field_key' 		=> 'postingStatus',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
		),
		array(
			'display_name'		=> 'Sap Error',
			'tbl_field_name' 	=> 'sap_error',
			'field_key' 		=> 'sapError',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 1,
			'sort_flag'			=> 1,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'		    => 1,
			'update_flag'		=> 1,
			'field_validation'	=> array(
				'is_mandatory'   => 0,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
		 )
			)
);


?>