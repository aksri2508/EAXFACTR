<?php
$config['INDIVIDUAL_APPROVAL_STATUS_REPORT']['columns_list'] = array(
		array(
			'display_name'		=> 'Remarks',
			'tbl_field_name' 	=> 'approver_remarks',
			'field_key' 		=> 'remarks',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 0,
			'sort_flag'			=> 0,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'			=> 1,
			'update_flag'       => 0,
			'field_validation'	=> array(
				'is_mandatory'   => 1,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
			)
		),
		array(
			'display_name'		=> 'Status',
			'tbl_field_name' 	=> 'approval_status',
			'field_key' 		=> 'status',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 0,
			'sort_flag'			=> 0,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'			=> 1,
			'update_flag'       => 0,
			'field_validation'	=> array(
				'is_mandatory'   => 1,
				'is_numeric' 	 => 0,
				'is_date'        => 0,
			)
		),
		array(
			'display_name'		=> 'Approval Status Report ID',
			'tbl_field_name' 	=> 'approval_status_report_id',
			'field_key' 		=> 'approvalStatusReportId',
			'field_type' 	 	=> 'alpha',
			'search_flag'		=> 0,
			'sort_flag'			=> 0,
			'excel_flag'		=> 0,
			'create_flag'		=> 1,
			'edit_flag'			=> 1,
			'update_flag'       => 0,
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