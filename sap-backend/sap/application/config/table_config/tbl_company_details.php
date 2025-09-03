<?php
// COMPANY DETAILS
$config['COMPANY_DETAILS']['columns_list']	= array(
	array(
		'display_name'		=> 'Id',
		'tbl_field_name' 	=> 'id',
		'field_key' 		=> 'id',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'			=> 1,
		'update_flag'       => 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
		)
	), 
	array(
		'display_name'		=> 'SMTP Host',
		'tbl_field_name' 	=> 'smtp_host',
		'field_key' 		=> 'smtpHost',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'SMTP Secure',
		'tbl_field_name' 	=> 'smtp_secure',
		'field_key' 		=> 'smtpSecure',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'SMTP protocol',
		'tbl_field_name' 	=> 'smtp_protocol',
		'field_key' 		=> 'smtpProtocol',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'SMTP Port',
		'tbl_field_name' 	=> 'smtp_port',
		'field_key' 		=> 'smtpPort',
		'field_type' 	 	=> 'number',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 1,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'SMTP Username',
		'tbl_field_name' 	=> 'smtp_username',
		'field_key' 		=> 'smtpUsername',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'SMTP Password',
		'tbl_field_name' 	=> 'smtp_password',
		'field_key' 		=> 'smtpPassword',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Mail Provider',
		'tbl_field_name' 	=> 'mail_provider',
		'field_key' 		=> 'mailProvider',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 0,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Sender User Name',
		'tbl_field_name' 	=> 'sender_user_name',
		'field_key' 		=> 'senderUserName',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	),
	array(
		'display_name'		=> 'Sender User EmailId',
		'tbl_field_name' 	=> 'sender_user_emailid',
		'field_key' 		=> 'senderUserEmailid',
		'field_type' 	 	=> 'alpha',
		'search_flag'		=> 0,
		'sort_flag'			=> 0,
		'excel_flag'		=> 0,
		'create_flag'		=> 0,
		'edit_flag'		    => 0,
		'update_flag'		=> 1,
		'field_validation'	=> array(
			'is_mandatory'   => 1,
			'is_numeric' 	 => 0,
			'is_date'        => 0,
	 )
	)
	
);
?>