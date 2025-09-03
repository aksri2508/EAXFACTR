<?php
// MASTER BRANCHES 
$config['MASTER_BRANCHES']['columns_list']	= array(
			array(
				'display_name'		=> 'Id',
				'tbl_field_name' 	=> 'id',
				'field_key' 		=> 'id',
				'field_type' 	 	=> 'number',
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
				'display_name'		=> 'Branch Code',
				'tbl_field_name' 	=> 'branch_code',
				'field_key' 		=> 'branchCode',
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
				'display_name'		=> 'Branch Name',
				'tbl_field_name' 	=> 'branch_name',
				'field_key' 		=> 'branchName',
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
				'display_name'		=> 'First Name',
				'tbl_field_name' 	=> 'first_name',
				'field_key' 		=> 'firstName',
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
				'display_name'		=> 'Last Name',
				'tbl_field_name' 	=> 'last_name',
				'field_key' 		=> 'lastName',
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
				'display_name'		=> 'Email Id',
				'tbl_field_name' 	=> 'email_id',
				'field_key' 		=> 'emailId',
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
			)
);

?>