<?php
// EMPLOYEE LIST 
$config['MASTER_MODULE_SCREEN_MAPPING']['columns_list']	= array(
	array(
		'display_name'		=> 'Module Name',
		'tbl_field_name' 	=> 'module_name',
		'field_key' 		=> 'moduleName',
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
		'display_name'		=> 'Screen Name',
		'tbl_field_name' 	=> 'screen_name',
		'field_key' 		=> 'screenName',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
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
		'display_name'		=> 'Enable Udf',
		'tbl_field_name' 	=> 'enable_udf',
		'field_key' 		=> 'enableUdf',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Enable Document Numbering',
		'tbl_field_name' 	=> 'enable_document_numbering',
		'field_key' 		=> 'enableDocumentNumbering',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Enable Aapproval Pprocess',
		'tbl_field_name' 	=> 'enable_approval_process',
		'field_key' 		=> 'enableApprovalProcess',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Enable Notification',
		'tbl_field_name' 	=> 'enable_notification',
		'field_key' 		=> 'enableNotification',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Screen Type',
		'tbl_field_name' 	=> 'screen_type',
		'field_key' 		=> 'screenType',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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