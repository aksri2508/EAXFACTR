<?php
// EMPLOYEE LIST 
$config['ACCESS_CONTROL']['columns_list']	= array(

	array(
		'display_name'		=> 'Access Control Name',
		'tbl_field_name' 	=> 'access_control_name',
		'field_key' 		=> 'accessControlName',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 1,
		'update_flag'       => 1,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
		)
	),
	array(
		'display_name'		=> 'Status',
		'tbl_field_name' 	=> 'status',
		'field_key' 		=> 'status',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 0,
		'update_flag'       => 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
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
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 0,
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
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Created On',
		'tbl_field_name' 	=> 'created_on',
		'field_key' 		=> 'createdOn',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 0,
		'update_flag'       => 0,
		'field_validation'	=> array(
			   'is_mandatory'   => 0,
			   'is_numeric' 	=> 0,
			   'is_date'        => 0,
		)
	),
	array(
		'display_name'		=> 'Created By',
		'tbl_field_name' 	=> 'created_by',
		'field_key' 		=> 'createdBy',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 0,
		'update_flag'       => 0,
		'field_validation'	=> array(
			   'is_mandatory'   => 0,
			   'is_numeric' 	=> 0,
			   'is_date'        => 0,
		)
	),
	array(
		'display_name'		=> 'Updated On',
		'tbl_field_name' 	=> 'updated_on',
		'field_key' 		=> 'UpdatedOn',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 0,
		'update_flag'       => 0,
		'field_validation'	=> array(
			   'is_mandatory'   => 0,
			   'is_numeric' 	=> 0,
			   'is_date'        => 0,
		)
	),
	array(
		'display_name'		=> 'Updated By',
		'tbl_field_name' 	=> 'updated_by',
		'field_key' 		=> 'UpdatedBy',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 0,
		'update_flag'       => 0,
		'field_validation'	=> array(
			   'is_mandatory'   => 0,
			   'is_numeric' 	=> 0,
			   'is_date'        => 0,
		)
	)
);
?>