<?php
$config['MASTER_DISTRIBUTION_RULES']['columns_list']	= array(
	array(
		'display_name'		=> 'Distribution Code',
		'tbl_field_name' 	=> 'distribution_code',
		'field_key' 		=> 'distributionCode',
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
		'display_name'		=> 'Distribution Name',
		'tbl_field_name' 	=> 'distribution_name',
		'field_key' 		=> 'distributionName',
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
		'display_name'		=> 'Dimension Name',
		'tbl_field_name' 	=> 'dimension_name',
		'field_key' 		=> 'dimensionName',
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
		'display_name'		=> 'Emp Id',
		'tbl_field_name' 	=> 'emp_id',
		'field_key' 		=> 'empId',
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
		'display_name'		=> 'Sort Code',
		'tbl_field_name' 	=> 'sort_code',
		'field_key' 		=> 'sortCode',
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
		'display_name'		=> 'Dimension Id',
		'tbl_field_name' 	=> 'dimension_id',
		'field_key' 		=> 'dimensionId',
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
		'display_name'		=> 'Effective From',
		'tbl_field_name' 	=> 'effective_from',
		'field_key' 		=> 'effectiveFrom',
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
		'display_name'		=> 'Effective To',
		'tbl_field_name' 	=> 'effective_to',
		'field_key' 		=> 'effectiveTo',
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
		'display_name'		=> 'Status',
		'tbl_field_name' 	=> 'status',
		'field_key' 		=> 'status',
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
