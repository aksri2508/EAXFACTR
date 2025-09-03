<?php
$config['OPPORTUNITY_STAGES']['excel_file_name']	= time() . '_opportunity.xlsx';
$config['OPPORTUNITY_STAGES']['columns_list']	= array(
	array(
		'display_name'		=> 'Id',
		'tbl_field_name' 	=> 'id',
		'field_key' 		=> 'id',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 1,
		'update_flag'       => 0,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
		)
	),
	array(
		'display_name'		=> 'Opportunity Id',
		'tbl_field_name' 	=> 'opportunity_id',
		'field_key' 		=> 'opportunityId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 1,
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
		'display_name'		=> 'Start Date',
		'tbl_field_name' 	=> 'start_date',
		'field_key' 		=> 'startDate',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 1,
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
		'display_name'		=> 'Closing Date',
		'tbl_field_name' 	=> 'closing_date',
		'field_key' 		=> 'closingDate',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 1,
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
		'display_name'		=> 'Emp Id',
		'tbl_field_name' 	=> 'emp_id',
		'field_key' 		=> 'empId',
		'field_type' 	 	=> '',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Stage Id',
		'tbl_field_name' 	=> 'stage_id',
		'field_key' 		=> 'stageId',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 1,
		'sort_flag'			=> 1,
		'excel_flag'		=> 1,
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
		'display_name'		=> 'Stage Percentage',
		'tbl_field_name' 	=> 'stage_percentage',
		'field_key' 		=> 'stagePercentage',
		'field_type' 	 	=> '',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 1,
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
		'display_name'		=> 'Document Id',
		'tbl_field_name' 	=> 'document_id',
		'field_key' 		=> 'documentId',
		'field_type' 	 	=> '',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
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
		'display_name'		=> 'Document Type Id',
		'tbl_field_name' 	=> 'document_type_id',
		'field_key' 		=> 'documentTypeId',
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
		'display_name'		=> 'Activity Id',
		'tbl_field_name' 	=> 'activity_id',
		'field_key' 		=> 'activityId',
		'field_type' 	 	=> 'number',
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
