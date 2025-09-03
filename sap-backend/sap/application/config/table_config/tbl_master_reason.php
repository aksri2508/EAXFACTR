<?php
$config['MASTER_REASON']['columns_list']	= array(
	array(
		'display_name'		=> 'Reason Description',
		'tbl_field_name' 	=> 'reason_description',
		'field_key' 		=> 'reasonDescription',
		'field_type' 	 	=> 'alpha',
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
		'display_name'		=> 'status',
		'tbl_field_name' 	=> 'status',
		'field_key' 		=> 'status',
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
	),
);


?>