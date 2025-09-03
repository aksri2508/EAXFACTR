<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 ** Helper Name : Custom Helper
 ** Description : To keep the common functions used for many occations.
 ** Module   : NA
 **	Actors 	  :
 **	Features : Holding the common functions like creating user id, password, hashing the password, learnet id.
 */

/**
 * @METHOD NAME 	: printr()
 *
 * @DESC 		: FUNCTION USED TO PRINT THE VARIABLE IN ARRAY FORMAT
 * @RETURN VALUE : $password string
 * @PARAMETER 	: --
 * @ACCESS POINT :
 *
 **/
function printr($data, $flag = 0)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	if ($flag == 1) {
		exit(__FILE__ . '@' . __LINE__);
	}
}

/**
 * @METHOD NAME 	: passwordHash()
 *
 * @DESC 		: to get hasing the password with the config values
 * @RETURN VALUE : $password string
 * @PARAMETER 	: $password string
 * @ACCESS POINT :
 *
 **/
function passwordHash($password)
{
	// Store password options so that rehash & hash can share them:
	$ci = &get_instance();
	return password_hash($password, $ci->config->item('password_hash'), ['cost' => $ci->config->item('password_cost')]);
}

/**
 * @METHOD NAME 	: passwordVerify()
 *
 * @DESC 		:
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function passwordVerify($password, $passwordHash, $id, $update = true)
{
	$ci = &get_instance();
	if (password_verify($password, $passwordHash)) {
		if ($update && password_needs_rehash($passwordHash, $ci->config->item('password_hash'), ['cost' => $ci->config->item('password_cost')])) {
			$_db = clone $ci->db;
			$_db->update('tbl_login', ['password' => passwordHash($password)], ['id' => $id]);
		}
		return true;
	}
	return false;
}


/**
 * @METHOD NAME 	: getListingParams()
 *
 * @DESC 		: TO GET THE LISTING PARAMETERS DETAILS
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function getListingParams($listingArray, $findKey)
{
	$searchFlag = 1;
	foreach ($listingArray as $key => $value) {
		if (!empty($value['field_key'])) {
			if ($value['field_key'] == $findKey) {
				$searchFlag = 2;
				break;
			}
		}
	}

	// FLAG VALUES 
	if ($searchFlag == 2) { // SORTING KEYS 
		//return "LCASE(".$value['tbl_field_name'].")";

		if ($value['field_type'] == 'alpha') {
			return "LCASE(" . $value['tbl_field_name'] . ")";
		}else if ($value['field_type'] == 'abs') {
			return "abs(" . $value['tbl_field_name'] . ")";
		} else {
			return $value['tbl_field_name'];
		}
	}
}

/**
 * @METHOD NAME 	: arrangeDistributionRulesData()
 *
 * @DESC 		: TO ARRANCE THE DISTRIBUTION RULES DATA
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function arrangeDistributionRulesData($distributionRulesId)
{

	//$dimensionIdValues 	  = array(1, 2, 3, 4, 5); // STATIC VALUES IN DIMENSION ID VALUES 
	$frameDimensionOrder  = ""; // STATIC 0
	//$frameDimensionOrderData = array();

	if (!empty($distributionRulesId)) {

		$distributionRulesData = explode(",", $distributionRulesId);

		// CI GET INSTANCE
		$CI = &get_instance();

		// Profile and login table
		$CI->app_db->select([
			//'MDR.dimension_id',
			'MDR.id'
		])
			->from(MASTER_DISTRIBUTION_RULES . ' AS MDR')
			->where_in('MDR.id', $distributionRulesData)
			->where('MDR.is_deleted', '0')
			->order_by('MDR.id', 'asc');
			//->order_by('MDR.dimension_id', 'asc');

		$rs = $CI->app_db->get();

		if ($rs->num_rows() > 0) { // Tot Rows > 0

			$distributionDetails	= $rs->result_array();

			//echo "<pre>";print_r($distributionDetails); 

			// foreach ($dimensionIdValues as $dimensionKey => $dimensionValues) { // STATIC ARRAY

			// 	$flag = 0;
			// 	foreach ($distributionDetails as $distDetKey => $distDetValue) {

			// 		$tempDimensionOrder 				= array();

			// 		if ($dimensionValues == $distDetValue['dimension_id']) {
			// 			$tempDimensionOrder['id'] 			= $distDetValue['id'];
			// 			$tempDimensionOrder['dimension_id'] = $distDetValue['dimension_id'];
			// 			$frameDimensionOrderData[]				= $tempDimensionOrder;
			// 			$flag = 1;
			// 			//unset($distributionDetails[$distDetKey]);
			// 		}
			// 	}
			// 	// FLAG DATA
			// 	if ($flag == 0) {
			// 		$tempDimensionOrder['id'] 			= 0;
			// 		$tempDimensionOrder['dimension_id'] = '';
			// 		$frameDimensionOrderData[]				= $tempDimensionOrder;
			// 	}
			// }
			$frameDimensionOrder 	= array_column($distributionDetails, 'id');
		}

		//echo "<pre>";print_r($frameDimensionOrder); exit;

		$frameDimensionOrder = implode(",", $frameDimensionOrder);

		//echo "<pre>";print_r($frameDimensionOrder); exit;

		return $frameDimensionOrder;
	}
	return $frameDimensionOrder;
}


/**
 * @METHOD NAME 	: processDistributionRulesData()
 *
 * @DESC 		: TO PROCESS THE DISTRIBUTION RULES DATA
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function processDistributionRulesData($listingArray, $distributionRulesId)
{

	//echo "Count of total listing array ".count($listingArray);

	$finalListingArray  = array();
	$cnt				= 0;

	foreach ($listingArray as $listKey => $listValue) {
		$processFlag  				= chkDistributionRulesRecord($listValue['distribution_rules_id'], $distributionRulesId);
		if ($processFlag == 1) {
			$finalListingArray[$cnt] =  $listValue;
			$cnt++;
		}
	}
	//echo "FINAL LISTING ARRAY count ".count($finalListingArray);
	//print_r($finalListingArray);	//echo "Total count is ".$cnt; 	//exit;
	return $finalListingArray;
}


/**
 * @METHOD NAME 	: chkDistributionRulesRecord()
 *
 * @DESC 		: TO CHECK THE DISTRIBUTION RULES DATA
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function chkDistributionRulesRecord($rowDistributionRulesList, $empDistributionRulesId)
{

	//echo "<br>";echo "Row is ====>".$rowDistributionRulesList;echo "Emp Row is ====>".$empDistributionRulesId;

	$flag  = 1;
	$rowDistributionRulesArray	  = explode(",", $rowDistributionRulesList);
	$empDistributionRulesArray	  = explode(",", $empDistributionRulesId);


	foreach ($rowDistributionRulesArray as $selRecord => $selValue) {
		$checkRecord = array_search($selValue, $empDistributionRulesArray);
		if ($checkRecord === false) {
			$flag = 0;
			break;
		}
	}
	//echo "Flag is ".$flag;
	return $flag;
}


/**
 * @METHOD NAME 	: getOffSetRecords()
 *
 * @DESC 		: TO GET THE OFFSET RECORDS 
 * @RETURN VALUE :
 * @PARAMETER 	:
 * @ACCESS POINT :
 *
 **/
function getOffSetRecords($listRecords, $offset, $limit)
{
	//printr($listRecords);	
	$listingRecords  = array_slice($listRecords, $offset, $limit);
	return $listingRecords;
}


/**
 * @METHOD NAME 	: validateInput()
 *
 * @DESC 		: to validate the input values from the HTTP request
 * @RETURN VALUE :
 * @PARAMETER 	: $array array
 * @ACCESS POINT :
 *
 **/
function validateInput(&$array)
{

	$ci = &get_instance();
	$ci->load->database();

	if (is_array($array)) { // Is Array
		$array = array_combine(
			array_map(
				function ($str) {
					return $str;
				},
				array_keys($array)
			),
			array_values($array)
		);

		foreach ($array as $key => $val) {
			if (is_array($val)) {
				validateInput($array[$key]);
			} else {
				$value = str_replace("\n", "||||", $val);
				$string = $ci->db->escape_str($value);
				$string = str_replace("||||", "\n", $val);
				$array[$key] = trim($string);
			}
		}
		return $array;
	} else { // VARIABLE
		$string = $ci->db->escape_str(trim($array));
		return $string;
	}
}


/**
 * @METHOD NAME 	: camelCaseToUnderscore()
 *
 * @DESC 		: CAMELCASE TO UNDERSCORE
 * @RETURN VALUE : $value Array
 * @PARAMETER 	: $array Array
 * @ACCESS POINT :
 *
 **/
function camelCaseToUnderscore($input)
{
	return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}


/**
 * @METHOD NAME 	: toCamelCase()
 *
 * @DESC 		: PASS ARRAY POINTER AND CONVERT THE DATA DIRECTLY
 * @RETURN VALUE : $value Array
 * @PARAMETER 	: $array Array
 * @ACCESS POINT :
 *
 **/
function toCamelCase(&$array)
{
	foreach (array_keys($array) as $key) :
		# Working with references here to avoid copying the value,
		# since you said your data is quite large.
		$value = &$array[$key];
		unset($array[$key]);
		# This is what you actually want to do with your keys:
		#  Change snake_case to camelCase
		$transformedKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
		# Work recursively
		if (is_array($value)) toCamelCase($value);
		# Store with new key
		$array[$transformedKey] = $value;
		# Do not forget to unset references!
		unset($value);
	endforeach;
}


/**
 * @METHOD NAME 	: toCamelCaseSingleWord()
 *
 * @DESC 			: PASS ARRAY POINTER AND CONVERT THE DATA DIRECTLY
 * @RETURN VALUE 	: $value Array
 * @PARAMETER 		: $array Array
 * @ACCESS POINT 	:
 *
 **/
function toCamelCaseSingleWord($data){
	
	return $transformedData = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $data))));
}	
 

/**
 * @METHOD NAME 	: getFullImgUrl()
 *
 * @DESC 		: TO GET THE FULL IMAGE URL 
 * @RETURN VALUE : $value Array
 * @PARAMETER 	: $array Array
 * @ACCESS POINT :
 *
 **/
function getFullImgUrl($type, $imgName)
{
	$fullUrl = "";

	if (!empty($imgName)) {
		if ($type == 'employee') {
			$fullUrl = EMPLOYEE_PHOTO_ACCESS_URL . "" . $imgName;
		} else if ($type == 'itemphoto') {
			$fullUrl = ITEM_PHOTO_ACCESS_URL . "" . $imgName;
		} else if ($type == 'companylogo') {
			$fullUrl = COMPANY_LOGO_ACCESS_URL . "" . $imgName;
		} else if ($type == 'invoice') {
			$fullUrl = INVOICE_ACCESS_URL . "" . $imgName;
		} else if ($type == 'rentalItemPhoto') {
			$fullUrl = RENTAL_ITEM_PHOTO_ACCESS_URL . "" . $imgName;
		} else if ($type == 'rentalEquipmentPhoto') {
			$fullUrl = RENTAL_EQUIPMENT_PHOTO_ACCESS_URL . "" . $imgName;
		} else if ($type == 'transport') {
			$fullUrl = GATEPASS_BARCODE_ACCESS_URL . "" . $imgName;
		} 
	}
	return $fullUrl;
}


/**
 * @METHOD NAME 	: generatePassword()
 *
 * @DESC 		: To create the password with random chars
 * @RETURN VALUE : $password string
 * @PARAMETER 	: --
 * @ACCESS POINT :
 *
 **/
function generatePassword()
{
	$length = 8;
	$chars  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$*";
	$password = substr(str_shuffle($chars), 0, $length);
	//$password = "123456";
	return $password;
}


/**
 * @METHOD NAME 	: generateRandomCharacters()
 *
 * @DESC 			:TO GENERATE A RANDOM CHARACTERS IN THE APPLICATION 
 * @RETURN VALUE 	: --
 * @PARAMETER 		: --
 * @ACCESS POINT 	:
 *
 **/
function generateRandomCharacters($length,$type)
{
	if($type===1){
		$chars  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$*";
	}else {
		$chars  = "0123456789";
	}	
	return substr(str_shuffle($chars), 0, $length);
}


/**
 * @METHOD NAME 	: generateBarCode()
 *
 * @DESC 			: To Generate the Bar Code
 * @RETURN VALUE 	: 
 * @PARAMETER 		: 
 * @ACCESS POINT 	:
 *
 **/
function generateBarCode($id)
{
	/*
		$barCodeNumber = '';
		$currentDate = date('D, d M Y');
		$dt = new DateTime();
	*/
	// CI GET INSTANCE
	//$CI = &get_instance();
	$barCodeNumber = $id."".generateRandomCharacters(6,2);
	return $barCodeNumber;
}




/**
 * @METHOD NAME 	: sendRegUserMail()
 *
 * @DESC 		: TO FRAME THE MAIL TEMPLATE
 * @RETURN VALUE :
 * @PARAMETER 	: --
 * @ACCESS POINT :
 *
 **/
function sendRegUserMail($userId, $password, $username = '', $companyName = '', $branchName = '')
{
	

	// CI GET INSTANCE
	$CI = &get_instance();

	// Profile and login table
	$CI->app_db->select([
		'EMP.first_name', 'EMP.last_name',
		'EMP.email_id',
		'EMP.id'
	])
		->from(EMPLOYEE_PROFILE . ' AS EMP')
		->where('EMP.id', $userId)
		->where('EMP.is_deleted', '0');

	$rs = $CI->app_db->get();

	if ($rs->num_rows() > 0) { // Tot Rows > 0

		$userDetails	 = $rs->result_array();

		$mailContent = "";
		$mailTitle	 = "";

		$templateName = "user_registration";
		$mailContent = $CI->config->item($templateName);
		$mailTitle	 = $mailContent['title'];

		// SEND REGISTRATION MAIL
		$loginUrl	 = WEB_SERVER_URL . '/page/login';

		// BODY DATA MANIPULATION
		if (empty($username)) {
			$username  = $userDetails[0]['email_id'];
		}

		$replaceBody = [
			'<<LOGIN_URL>>' 		=> $loginUrl,
			'<<MAIL_CUR_DATE>>'     => date('D, d M Y'),
			'<<NAME>>'              => implode(' ', [$userDetails[0]['first_name'], $userDetails[0]['last_name']]),
			'<<USERNAME>>'			=> $username,   //
			'<<PASSWORD>>'			=> $password, 	// DEFAULT PASSWORD
			'<<COMPANY_NAME>>'		=> $companyName, 	// DEFAULT PASSWORD
			'<<BRANCH_NAME>>'		=> $branchName, 	// DEFAULT PASSWORD
		];
		$mailContent['body'] = str_replace(array_keys($replaceBody), $replaceBody, $mailContent['body']);

		// TEMPALTE FILE MANIPULATION
		$templateArray 		=  array(
			'USER_LANGUAGE' => $CI->currentUserLanguage,
		);
		$mailerTemplateData = getMailerTemplate($templateArray);

		// OVER ALL TEMPLATE MANIPULATION
		$mailerData = str_replace("<<MAIL_TITLE>>", $mailTitle, $mailerTemplateData);
		$mailerData = str_replace("<<BODY_CONTENT>>", $mailContent['body'], $mailerData);
		$mailerData = str_replace("<<MAILIMGURL>>", MAILIMGURL, $mailerData);

		$CI->load->library('customemail');

		// MAILER CONTENT
		$mailerInfo  	= getMailerInfo();
		
		$CI->customemail->sendemail_external(
			$userDetails[0]['email_id'],
			$mailContent['subject'],
			$mailerData,
			$mailerInfo
		);
	}
}


/**
 * @METHOD NAME 	: getMailerTemplate()
 *
 * @DESC 		:
 * @RETURN VALUE :
 * @PARAMETER 	: --
 * @ACCESS POINT :
 *
 **/
function getMailerTemplate($userDetails)
{
	// TEMPALTE FILE MANIPULATION
	$replaceTemplateFileName = [
		'USER_LANGUAGE' => $userDetails['USER_LANGUAGE'],

	];

	// print_r(array_keys($replaceTemplateFileName)); 
	// echo '<br>';
	// print_r($replaceTemplateFileName);
	// echo '<br>';
	// echo MAILER_TEMPLATE_FILE;
	// echo "<br>";
	$mailTemplateFile	= str_replace(array_keys($replaceTemplateFileName), $replaceTemplateFileName, MAILER_TEMPLATE_FILE);
	// echo $mailTemplateFile; exit;

	$mailerTemplateData = file_get_contents($mailTemplateFile); // GET MAIL TEMPLATE
	
	$copyRightDate 	= date('Y');
	$mailerData 	= str_replace("<<COPYRIGHT_DATE>>",$copyRightDate,$mailerTemplateData);
	return $mailerData;
}


/**
 * @METHOD NAME 	: getMailerInfo()
 *
 * @DESC 		: get STMP details
 * @RETURN VALUE : $mailer_info array
 * @PARAMETER 	: $mailerUserId int, $mtId int
 * @ACCESS POINT :
 *
 **/
function getMailerInfo($dbObject = '')
{
	$CI = &get_instance();

	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}

	$dbObject->select([
		'sender_user_name as username',
		'sender_user_emailid as user_emailid',
		'smtp_host',
		'smtp_protocol',
		'smtp_port',
		'smtp_secure',
		'smtp_username',
		'smtp_password',
		'mail_provider',
	])
		->from(COMPANY_DETAILS . ' AS cd')
		->where('cd.is_deleted', '0');

	$rs = $dbObject->get();
	if ($rs->num_rows()) {
		$mailer_info = $rs->row_array();
		return $mailer_info;
	}
	return [];
}

/**
 * @METHOD NAME 	: getLoggedInUserInfo()
 *
 * @DESC 			: get getLoggedInUserInfo details
 * @RETURN VALUE 	: $mailer_info array
 * @PARAMETER 		: $mailerUserId int, $mtId int
 * @ACCESS POINT 	:
 *
 **/
function getLoggedInUserInfo($dbObject = '')
{
	$CI = &get_instance();

	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}

	$dbObject->select([
		'id',
		'emp_code',
		'first_name',
		'last_name',
		'email_id'
	])
	->from(EMPLOYEE_PROFILE . ' AS emp')
	->where('emp.id', $CI->currentUserId)
	->where('emp.is_deleted', '0');

	$rs = $dbObject->get();
	if ($rs->num_rows()) {
		$logged_in_user_info = $rs->row_array();
		return $logged_in_user_info;
	}
	return [];
}

/**
 * @METHOD NAME 	: processExcelData()
 *
 * @DESC 			: TO PROCESS THE EXCEL DATA 
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT 	:
 *
 **/
function processExcelData($resultsData, $fileName, $columnList)
{

	$CI = &get_instance();

	$CI->load->library('excel_readerwriter/Excel');

	$exportCols = array();
	foreach ($columnList as $columnKey => $columnValue) {
		if ($columnValue['excel_flag'] == 1) {
			//$fieldName  			= camelCaseToUnderscore($columnValue['field_key']);
			$fieldName  			= $columnValue['tbl_field_name'];
			$exportCols[$fieldName] = $columnValue['display_name'];
		}
	}
	
	if(empty($exportCols)){ // ISSUE CHECKER 
		echo json_encode(array(
					'status' => 'FAILURE',
					'message' => 'No Fields are configured for excel',
					"responseCode" => 200
				));
		exit();
	}
	

	$file		= TEMP_EXCEL_SAVE_PATH . $fileName;
	$CI->excel->setDownloadType('saveFile')->setFile($file)->doExport($resultsData, $exportCols);

	//$fileUrl 	=  EXCEL_DOWNLOAD_PATH.$fileName;
	
	$fileParam 	 = 1;
	$fileUrl 	 =  SECURE_FILE_DOWNLOAD_URL . $fileParam . "/" . $fileName;
	$outputData  = ['status' => 'SUCCESS', 'fileUrl' => $fileUrl];
	return $outputData;	
}



/**
 * @METHOD NAME 	: processExcelForTransportReport()
 *
 * @DESC 			: TO PROCESS THE EXCEL DATA 
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT 	:
 *
 **/
function processExcelForTransportReport($resultsData, $fileName, $columnList, $totalAmount)
{
	
	$CI = &get_instance();
	$CI->load->library('excel_readerwriter/Excel');

	$exportCols = array();
	foreach ($columnList as $columnKey => $columnValue) {
		if ($columnValue['excel_flag'] == 1) {
			//$fieldName  			= camelCaseToUnderscore($columnValue['field_key']);
			$fieldName  			= $columnValue['tbl_field_name'];
			$exportCols[$fieldName] = $columnValue['display_name'];
		}
	}
	
	if(empty($exportCols)){ // ISSUE CHECKER 
		echo json_encode(array(
					'status' => 'FAILURE',
					'message' => 'No Fields are configured for excel',
					"responseCode" => 200
				));
		exit();
	}
	

	$file		= TEMP_EXCEL_SAVE_PATH . $fileName;
	
	$vendorBpCode = isset($resultsData[0]["vendor_bp_code"]) ? $resultsData[0]["vendor_bp_code"] : '' ;
	$vendorBpName = isset($resultsData[0]["vendor_bp_name"]) ? $resultsData[0]["vendor_bp_name"] : '' ;

	$headerRowData = !empty($vendorBpCode) ? "Vendor Name : (".$vendorBpCode." / ".$vendorBpName.")" : "Vendor Name ";

	$footerRowData = "Total Price: ".$totalAmount;
	$CI->excel->setDownloadType('saveFile')->setFile($file)->doExportCustom($resultsData, $exportCols, $headerRowData, $footerRowData);

	//$fileUrl 	=  EXCEL_DOWNLOAD_PATH.$fileName;
	
	$fileParam 	 = 1;
	$fileUrl 	 =  SECURE_FILE_DOWNLOAD_URL . $fileParam . "/" . $fileName;
	$outputData  = ['status' => 'SUCCESS', 'fileUrl' => $fileUrl];
	return $outputData;	
}


/**
 * @METHOD NAME 	: readExcelData()
 *
 * @DESC 			: TO PROCESS THE EXCEL DATA 
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT 	:
 *
 **/
function readExcelData($fileName)
{

	$CI = &get_instance();

	$CI->load->library('excel_readerwriter/Excel');
	// $reader   = PHPExcel_IOFactory::createReaderForFile();
	// Import Excel file
	$objPHPExcel = $CI->excel->doImport($fileName);

	$highestColumn = 'C';

	// Read Excel file.
	$excelData = $CI->excel->readexcel($objPHPExcel, $highestColumn);
    
	return $excelData;

}


/**
 * @METHOD NAME 	: frameSecuredUrl()
 *
 * @DESC 		: TO FRAME THE SECURED URL
 * @RETURN VALUE :
 * @PARAMETER 	: --
 * @ACCESS POINT :
 *
 **/
function frameSecuredUrl($fileName, $pathType)
{
	$fileParam 	 = 1;
	if ($pathType == 1) {
		return $fileUrl 	 =  PRIVATE_FILE_DOWNLOAD_URL . $fileParam . "/" . $fileName;
	}
}


/**
 * @METHOD NAME 	: generateTransactionAnalytics()
 *
 * @DESC 			: TO GET THE ANALYTICS DETAILS.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateTransactionAnalytics($passData,$skipDistributionFlag='')
{

	// ACTIVITY STATUS DETAILS
	$passSearchData['category'] = 2;
	$passSearchData['delFlag']  = 1;
	$tableName	 = $passData['tableName'];
	$CI = &get_instance();

	$StatusDetails  = $CI->commonModel->getMasterStaticDataAutoList(array_merge($passSearchData, $passData),2);

	$key = 0;
	foreach ($StatusDetails as $key => $value) {
		$totalValue = $CI->commonModel->getTransactionAnalyticsCount($tableName, $value['id'],$skipDistributionFlag);
		$analyticsData[$key]['name'] 	= $value['name'];
		$analyticsData[$key]['id'] 		= $value['id'];
		$analyticsData[$key]['count']	= $totalValue;
	}
	$key++;
	$analyticsData[$key]['name'] 	= 'All';
	$analyticsData[$key]['id'] 		= 0;
	$analyticsData[$key]['count']	= $CI->commonModel->getTransactionAnalyticsCount($tableName, '',$skipDistributionFlag);
	return $analyticsData;
}



/**
 * @METHOD NAME 	: generateInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR PURCHASE MODULE.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generatePurchaseInvoice($invoiceProcessData)
{

	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);

	// print_r($modelOutput);
	// print_r($itemList);
	// exit;

	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;
			$itemName = "";
			$itemCode = "";
			$discountPercentage = "";
			$quantity = "";
			$uomName = "";
			$unitPrice = "";
			$itemTaxPercentage = "";
			$itemTaxValue = "";
			$totalItemAmount = "";
			$itemBinName = "";
			$itemHsnName = "";
			
			if(isset($itemListValue['itemInfo'][0]['item_name'])){
				$itemName = $itemListValue['itemInfo'][0]['item_name'];
			}
			if(isset($itemListValue['itemInfo'][0]['item_code'])){
				$itemCode = $itemListValue['itemInfo'][0]['item_code'];
			}
			if(isset($itemListValue['itemInfo'][0]['discount_percentage'])){
				$discountPercentage = $itemListValue['itemInfo'][0]['discount_percentage'];
			}

			if(isset($itemListValue['binInfo'][0]['bin_name'])){
				$itemBinName = $itemListValue['binInfo'][0]['bin_name'];
			}
			
			if(isset($itemListValue['hsnInfo'][0]['hsn_code'])){
				$itemHsnName = $itemListValue['hsnInfo'][0]['hsn_code'];
			}
			
			if(isset($itemListValue['quantity'])){
				$quantity = $itemListValue['quantity'];
			}
			if(isset($itemListValue['itemInfo'][0]['uom_name'])){
				$uomName = $itemListValue['itemInfo'][0]['uom_name'];
			}
			if(isset($itemListValue['unit_price'])){
				$unitPrice = $itemListValue['unit_price'];
			}
			if(isset($itemListValue['item_tax_percentage'])){
				$itemTaxPercentage = $itemListValue['item_tax_percentage'];
			}
			if(isset($itemListValue['item_tax_value'])){
				$itemTaxValue = $itemListValue['item_tax_value'];
			}
			if(isset($itemListValue['total_item_amount'])){
				$totalItemAmount = $itemListValue['total_item_amount'];
			}

			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Description</th>
				<th class="text-left">Quantity</th>
				<th class="text-left">UoM</th>
				<th class="text-left">Price</th>
				<th class="text-left">Tax %</th>
				<th class="text-left">Tax Value</th>
				<th class="text-left">Total</th>
			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<div><span class="ash-color">Discount:</span> ' . $discountPercentage . '%</div></td>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $uomName . '</td>
			<td class="text-left">' . $unitPrice . '</td>
			<td class="text-left">' . $itemTaxPercentage . '</td>
			<td class="text-left">' . $itemTaxValue . '</td>
			<td class="text-left">' . $totalItemAmount . '</td>
			</tr>';
			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}


	$partnerCode = "";
	$referenceNo = "";
	if(isset($modelOutput['vendorBpInfo'][0]['partner_code'])){
		$partnerCode = $modelOutput['vendorBpInfo'][0]['partner_code'];
	}

	if(isset($modelOutput['reference_number'])){
		$referenceNo = $modelOutput['reference_number'];
	}

	// For Customer Info details.
	$customerInfoHtml = '<tr>
			<td>
				<div class="ash-color">Customer No</div>
				' . $partnerCode . '
			</td>
			<td>
				<div class="ash-color">Reference No</div>
				' . $referenceNo . '
			</td>';
		
	if(isset($modelOutput['due_date'])){
		$customerInfoHtml .='<td>
					<div class="ash-color">Due Date</div>
					' . $modelOutput['due_date'] . '
				</td>
			</tr>';
	}

	if(isset($modelOutput['delivery_date'])){
		$customerInfoHtml .='<td>
		<div class="ash-color">Delivery Date</div>
		' . $modelOutput['delivery_date'] . '
			</td>
		</tr>';
	}

	// For PayTo details.
	$address = "";
	$countryName = "";
	$stateName = "";
	$city = "";
	$zipcode = "";


	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['address'])){
		$address = $modelOutput['vendorPayToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['countryName'])){
		$countryName = $modelOutput['vendorPayToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['stateName'])){
		$stateName = $modelOutput['vendorPayToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['city'])){
		$city = $modelOutput['vendorPayToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'])){
		$zipcode = $modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'];
	}

	$customerInfoHtml .='<tr>
			<td colspan="3"><br>
				<div class="ash-color">Pay To</div>
				' . $address . '<br>
				' . $countryName . '
				' . $stateName . '<br>
				' . $city . '
				' . $zipcode . '
			</td>
		</tr>';

	// For Ship To.
	$address = "";
	$countryName = "";
	$stateName = "";
	$city = "";
	$zipcode = "";

	// print_r($modelOutput);
	// exit;
	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['address'])){
		$address = $modelOutput['vendorShipToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['countryName'])){
		$countryName = $modelOutput['vendorShipToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['stateName'])){
		$stateName = $modelOutput['vendorShipToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['city'])){
		$city = $modelOutput['vendorShipToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'])){
		$zipcode = $modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'];
	}

	$taxCode = "";
	if(isset($modelOutput['vendorBpInfo'][0]['tax_code'])){
		$taxCode = $modelOutput['vendorBpInfo'][0]['tax_code'];
	}

	$shipToHtml = '<tr>
				<td class="ash-color">Ship To</td>
			</tr>
				<tr>
				<td>
					' . $address . '<br>
					' . $countryName . '
					' . $stateName . '<br>
					' . $city . '
					' . $zipcode . '
					<br><br><br>
					' . $taxCode . '
				</td>						
			</tr>';
	
	if($fileNameVal == "purchase_request"){
		$customerInfoHtml = "";
		$shipToHtml = "";
	}
	
	$currencyLabel = "Currency: ";
	$currencyName = "";
	if(isset($modelOutput['vendorBpInfo'][0]['currency_name'])){
		$currencyName	 = $modelOutput['vendorBpInfo'][0]['currency_name'];
	}

	if($fileNameVal == "purchase_request"){
		$currencyLabel = "";
		$currencyName = "";
	}

	$totalBeforeDiscount = "";
	$discountPercentage = "";
	$discountValue = "";
	$roundingValue = 0;
	$taxPercentage = 0;
	$totalAmount = 0;
	
	if(isset($modelOutput['total_before_discount'])){
		$totalBeforeDiscount = $modelOutput['total_before_discount'];
	}

	if(isset($modelOutput['discount_percentage'])){
		$discountPercentage = $modelOutput['discount_percentage'];
	}
	
	if(isset($modelOutput['discount_value'])){
		$discountValue	= $modelOutput['discount_value'];
	}
	
	if(isset($modelOutput['rounding'])){
		$roundingValue	= $modelOutput['rounding'];
	}
	
	if(isset($modelOutput['tax_percentage'])){
		$taxPercentage	= $modelOutput['tax_percentage'];
	}

	if(isset($modelOutput['total_amount'])){
		$totalAmount = $modelOutput['total_amount'];
	}

	$totalBeforeTax	= 0;

	$totalBeforeTax	= $totalAmount - $taxPercentage;
	
	$paymentTerm = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$paymentTerm = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	$remarks = "";
	if(isset($modelOutput['remarks'])){
		$remarks = $modelOutput['remarks'];
	}

	// Summary Page. 
	$invoiceSummaryHtml  = '<!-- Summary -->
				<div class="summary">
					<table>
						<tr>
							<td>Sub Total:</td>
							<td>' . $totalBeforeDiscount . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>DP: ' . $discountPercentage . ' %</td>
							<td>' . $discountValue . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Total Before Tax</td>
							<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Rounding</td>
							<td>' . $roundingValue. '</td>
						</tr>
						<tr>
							<td>Tax Amount</td>
							<td>' . $taxPercentage . ' ' . $currencyName . '</td>
						</tr>
				
						<tr class="strong">
							<td>Total Amount</td>
							<td>' . $totalAmount . ' ' . $currencyName . '</td>
						</tr>
					</table>
				</div>
	
				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(INVOICE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $shipToHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<INVOICE_HEADING>>", 
			"<<PAGE_NO>>",
			"<<TOTAL_PAGE_NUMBER>>",
			"<<CUSTOMER_INFO_DETAILS>>",
			"<<CURRENCY_LABEL>>",
			"<<CURRENCY>>",
			"<<BRANCH_NAME>>",
			"<<COMPANY_NAME>>",
			 "<<COMPANY_ADDRESS>>",
			"<<COMPANY_TAX_NUMBER>>",
			"<<COMPANY_LOGO>>",
			"<<DOCUMENT_NUMBER>>", 
			"<<DOCUMENT_DATE>>",
			"<<BILL_TO_INFO>>",
			"<<CONTENT_INFO_HEADING>>",
			"<<CONTENT_INFO>>",
			"<<INVOICE_SUMMARY_BLOCK>>",
			 "<<FOOTER_CONTENT>>"
		);

		$branchName = "";
		$companyName = "";
		$location = "";
		$taxNumber = "";
		$documentNumber = "";
		$documentDate = "";
		
		if(isset($modelOutput['branchInfo'][0]['branch_name'])){
			$branchName = $modelOutput['branchInfo'][0]['branch_name'];
		}

		if(isset($companyDetails[0]['company_name'])){
			$companyName = $companyDetails[0]['company_name'];
		}

		if(isset($companyDetails[0]['location'])){
			$location = $companyDetails[0]['location'];
		}

		if(isset($companyDetails[0]['tax_number'])){
			$taxNumber = $companyDetails[0]['tax_number'];
		}

		if(isset($modelOutput['document_number'])){
			$documentNumber = $modelOutput['document_number'];
		}

		if(isset($modelOutput['document_date'])){
			$documentDate = $modelOutput['document_date'];
		}

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	// Assigning Mail Generation Path.
	$fileGenerationPath = INVOICE_GENERATION_PATH;
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}



/**
 * @METHOD NAME 	: generateInventoryInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR INVENTORY MODULE.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateInventoryInvoice($invoiceProcessData)
{
	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails = $invoiceProcessData['companyDetails'];
	$modelOutput = $invoiceProcessData['modelOutput'];
	$itemList = $invoiceProcessData['itemList'];
	$itemRowCountVal = $invoiceProcessData['itemRowCountVal'];
	$fileNameVal = $invoiceProcessData['fileName'];
	$fileHeadingName = $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);
	
	// Getting Company Logo.
	$companyLogo = getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$itemListCount = count($itemList);
	$itemRowCount = $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;

			$itemName = "";
			$itemCode = "";
			$discountPercentage = "";
			$quantity = "";
			$uomName = "";
			$unitPrice = "";
			$fromWarehouseName = "";
			$fromBinName = "";
			$toWarehouseName = "";
			$toBinName = "";
			
			if(isset($itemListValue['itemInfo'][0]['item_name'])){
				$itemName = $itemListValue['itemInfo'][0]['item_name'];
			}
			if(isset($itemListValue['itemInfo'][0]['item_code'])){
				$itemCode = $itemListValue['itemInfo'][0]['item_code'];
			}
			if(isset($itemListValue['itemInfo'][0]['discount_percentage'])){
				$discountPercentage = $itemListValue['itemInfo'][0]['discount_percentage'];
			}

			if(isset($itemListValue['quantity'])){
				$quantity = $itemListValue['quantity'];
			}
			if(isset($itemListValue['itemInfo'][0]['uom_name'])){
				$uomName = $itemListValue['itemInfo'][0]['uom_name'];
			}
			if(isset($itemListValue['itemInfo'][0]['unit_price'])){
				$unitPrice = $itemListValue['itemInfo'][0]['unit_price'];
			}

			if(isset($itemListValue['fromWarehouseInfo'][0]['warehouse_name'])){
				$fromWarehouseName = $itemListValue['fromWarehouseInfo'][0]['warehouse_name'];
			}

			if(isset($itemListValue['fromBinInfo'][0]['bin_name'])){
				$fromBinName = $itemListValue['fromBinInfo'][0]['bin_name'];
			}
	
			if(isset($itemListValue['toWarehouseInfo'][0]['warehouse_name'])){
				$toWarehouseName = $itemListValue['toWarehouseInfo'][0]['warehouse_name'];
			}

			if(isset($itemListValue['toBinInfo'][0]['bin_name'])){
				$toBinName = $itemListValue['toBinInfo'][0]['bin_name'];
			}

		
			$contentInfoHeading = '
			<tr>
			<th class="text-left">Sno</th>
			<th class="text-left" style="min-width: 150px">Description</th>
			<th class="text-left">Quantity</th>
			<th class="text-left">UoM</th>
			<th class="text-left">Price</th>
			<th class="text-left">Warehouse From</th>
			<th class="text-left">Warehouse To</th>
			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<div><span class="ash-color">Discount:</span> ' . $discountPercentage . '%</div></td>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $uomName . '</td>
			<td class="text-left">' . $unitPrice . '</td>
			<td class="text-left">' . $fromWarehouseName . '</td>
			<td class="text-left">' . $toWarehouseName . '</td>
			</tr>';
			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}

	$partnerCode = "";
	$referenceNo = "";
	if(isset($modelOutput['vendorBpInfo'][0]['partner_code'])){
		$partnerCode = $modelOutput['vendorBpInfo'][0]['partner_code'];
	}

	if(isset($modelOutput['reference_number'])){
		$referenceNo = $modelOutput['reference_number'];
	}

	// For Customer Info details.
	$customerInfoHtml = '<tr>
			<td>
				<div class="ash-color">Customer No</div>
				' . $partnerCode . '
			</td>
			<td>
				<div class="ash-color">Reference No</div>
				' . $referenceNo . '
			</td>';
		
	if(isset($modelOutput['due_date'])){
		$customerInfoHtml .='<td>
					<div class="ash-color">Due Date</div>
					' . $modelOutput['due_date'] . '
				</td>
			</tr>';
	}

	if(isset($modelOutput['delivery_date'])){
		$customerInfoHtml .='<td>
		<div class="ash-color">Delivery Date</div>
		' . $modelOutput['delivery_date'] . '
			</td>
		</tr>';
	}

	// For delivery details.
	$deliveryAddress = "";
	$deliveryCountryName = "";
	$deliveryStateName = "";
	$deliveryCity = "";
	$deliveryZipcode = "";

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorShipToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorShipToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorShipToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorShipToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'];
	}

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color">Delivery Address</div>
				' . $deliveryAddress . '<br>
				' . $deliveryCountryName . '
				' . $deliveryStateName . '<br>
				' . $deliveryCity . '
				' . $deliveryZipcode . '
			</td>
		</tr>';

	// For Billing details.
	$billingAddress = "";
	$billingCountryName = "";
	$billingStateName = "";
	$billingCity = "";
	$billingZipcode = "";

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorPayToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorPayToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorPayToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorPayToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'];
	}

	$taxCode = "";
	if(isset($modelOutput['vendorBpInfo'][0]['tax_code'])){
		$taxCode = $modelOutput['vendorBpInfo'][0]['tax_code'];
	}

	$billToHtml = '<tr>
				<td class="ash-color">Bill To</td>
			</tr>
				<tr>
				<td>
					' . $billingAddress . '<br>
					' . $billingCountryName . '
					' . $billingStateName . '<br>
					' . $billingCity . '
					' . $billingZipcode . '
					<br><br><br>
					' . $taxCode . '
				</td>						
			</tr>';

	$currencyLabel = "Currency: ";
	$currencyName = "";
	if(isset($modelOutput['currencyInfo'][0]['currency_name'])){
		$currencyName	 = $modelOutput['currencyInfo'][0]['currency_name'];
	}

	$totalBeforeDiscount = "";
	$discountPercentage = "";
	$discountValue = "";
	$taxPercentage = 0;
	$totalAmount = 0;
	
	if(isset($modelOutput['total_before_discount'])){
		$totalBeforeDiscount = $modelOutput['total_before_discount'];
	}

	if(isset($modelOutput['discount_percentage'])){
		$discountPercentage = $modelOutput['discount_percentage'];
	}
	
	if(isset($modelOutput['discount_value'])){
		$discountValue	= $modelOutput['discount_value'];
	}
	
	if(isset($modelOutput['tax_percentage'])){
		$taxPercentage	= $modelOutput['tax_percentage'];
	}

	if(isset($modelOutput['total_amount'])){
		$totalAmount = $modelOutput['total_amount'];
	}

	$totalBeforeTax	= 0;

	$totalBeforeTax	= $totalAmount - $taxPercentage;

	$paymentTerm = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$paymentTerm = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	$remarks = "";
	if(isset($modelOutput['remarks'])){
		$remarks = $modelOutput['remarks'];
	}

	// Summary Page. 
	$invoiceSummaryHtml  = '<!-- Summary -->
				<!--<div class="summary">
					<table>
						<tr>
							<td>Sub Total:</td>
							<td>' . $totalBeforeDiscount . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>DP: ' . $discountPercentage . ' %</td>
							<td>' . $discountValue . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Total Before Tax</td>
							<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Tax Amount</td>
							<td>' . $taxPercentage . ' ' . $currencyName . '</td>
						</tr>
						<tr class="strong">
							<td>Total Amount</td>
							<td>' . $totalAmount . ' ' . $currencyName . '</td>
						</tr>
					</table>
				</div>-->
				<br><br>
				
				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					 This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(INVOICE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			// $customerInfoDetails  	= $customerInfoHtml;
			// $billInfoDetails		= $billToHtml;
			$customerInfoDetails  	= "";
			$billInfoDetails		= "";
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<INVOICE_HEADING>>", 
			"<<PAGE_NO>>",
			"<<TOTAL_PAGE_NUMBER>>",
			"<<CUSTOMER_INFO_DETAILS>>",
			"<<CURRENCY_LABEL>>",
			"<<CURRENCY>>",
			"<<BRANCH_NAME>>",
			"<<COMPANY_NAME>>",
			 "<<COMPANY_ADDRESS>>",
			"<<COMPANY_TAX_NUMBER>>",
			"<<COMPANY_LOGO>>",
			"<<DOCUMENT_NUMBER>>", 
			"<<DOCUMENT_DATE>>",
			"<<BILL_TO_INFO>>",
			"<<CONTENT_INFO_HEADING>>",
			"<<CONTENT_INFO>>",
			"<<INVOICE_SUMMARY_BLOCK>>",
			 "<<FOOTER_CONTENT>>"
		);

		$branchName = "";
		$companyName = "";
		$location = "";
		$taxNumber = "";
		$documentNumber = "";
		$documentDate = "";

		if(isset($modelOutput['branchInfo'][0]['branch_name'])){
			$branchName = $modelOutput['branchInfo'][0]['branch_name'];
		}
		
		if(isset($companyDetails[0]['company_name'])){
			$companyName = $companyDetails[0]['company_name'];
		}

		if(isset($companyDetails[0]['location'])){
			$location = $companyDetails[0]['location'];
		}

		if(isset($companyDetails[0]['tax_number'])){
			$taxNumber = $companyDetails[0]['tax_number'];
		}

		if(isset($modelOutput['document_number'])){
			$documentNumber = $modelOutput['document_number'];
		}

		if(isset($modelOutput['document_date'])){
			$documentDate = $modelOutput['document_date'];
		}

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	// Assigning Mail Generation Path.
	$fileGenerationPath = INVOICE_GENERATION_PATH;
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}




/**
 * @METHOD NAME 	: generateSalesInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR SALES MODULE.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateSalesInvoice($invoiceProcessData)
{
	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);
	
	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;
			$itemName = "";
			$itemCode = "";
			$discountPercentage = "";
			$quantity = "";
			$uomName = "";
			$unitPrice = "";
			$itemTaxPercentage = "";
			$itemTaxValue = "";
			$totalItemAmount = "";
			$itemBinName = "";
			$itemHsnName = "";
			
			if(isset($itemListValue['itemInfo'][0]['item_name'])){
				$itemName = $itemListValue['itemInfo'][0]['item_name'];
			}
			if(isset($itemListValue['itemInfo'][0]['item_code'])){
				$itemCode = $itemListValue['itemInfo'][0]['item_code'];
			}
			if(isset($itemListValue['itemInfo'][0]['discount_percentage'])){
				$discountPercentage = $itemListValue['itemInfo'][0]['discount_percentage'];
			}
			if(isset($itemListValue['quantity'])){
				$quantity = $itemListValue['quantity'];
			}
			if(isset($itemListValue['itemInfo'][0]['uom_name'])){
				$uomName = $itemListValue['itemInfo'][0]['uom_name'];
			}
			if(isset($itemListValue['unit_price'])){
				$unitPrice = $itemListValue['unit_price'];
			}
			if(isset($itemListValue['item_tax_percentage'])){
				$itemTaxPercentage = $itemListValue['item_tax_percentage'];
			}
			if(isset($itemListValue['item_tax_value'])){
				$itemTaxValue = $itemListValue['item_tax_value'];
			}
			if(isset($itemListValue['binInfo'][0]['bin_name'])){
				$itemBinName = $itemListValue['binInfo'][0]['bin_name'];
			}

			if(isset($itemListValue['hsnInfo'][0]['hsn_code'])){
				$itemHsnName = $itemListValue['hsnInfo'][0]['hsn_code'];
			}
		
			if(isset($itemListValue['total_item_amount'])){
				$totalItemAmount = $itemListValue['total_item_amount'];
			}
			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Description</th>
				<th class="text-left">Quantity</th>
				<th class="text-left">UoM</th>
				<th class="text-left">Price</th>
				<th class="text-left">Total</th>
			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<div><span class="ash-color">Discount:</span> ' . $discountPercentage . '%</div></td>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $uomName . '</td>
			<td class="text-left">' . $unitPrice . '</td>
			<td class="text-left">' . $totalItemAmount . '</td>
			</tr>';
			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}


	$partnerCode = "";
	$referenceNo = "";
	if(isset($modelOutput['customerBpInfo'][0]['partner_code'])){
		$partnerCode = $modelOutput['customerBpInfo'][0]['partner_code'];
	}

	if(isset($modelOutput['reference_number'])){
		$referenceNo = $modelOutput['reference_number'];
	}

	// For Customer Info details.
	$customerInfoHtml = '<tr>
			<td>
				<div class="ash-color">Customer No</div>
				' . $partnerCode . '
			</td>
			<td>
				<div class="ash-color">Reference No</div>
				' . $referenceNo . '
			</td>';
		
	if(isset($modelOutput['due_date'])){
		$customerInfoHtml .='<td>
					<div class="ash-color">Due Date</div>
					' . $modelOutput['due_date'] . '
				</td>
			</tr>';
	}

	if(isset($modelOutput['delivery_date'])){
		$customerInfoHtml .='<td>
		<div class="ash-color">Delivery Date</div>
		' . $modelOutput['delivery_date'] . '
			</td>
		</tr>';
	}

	// For delivery details.
	$deliveryAddress = "";
	$deliveryCountryName = "";
	$deliveryStateName = "";
	$deliveryCity = "";
	$deliveryZipcode = "";

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorShipToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorShipToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorShipToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorShipToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'];
	}

	// Customer Details
	$customerName = "";
	$emailId = "";
	$contactNumber = "";
	$contactType = "";

	if(isset($modelOutput['customerBpInfo'][0]['partner_name'])){
		$customerName = $modelOutput['customerBpInfo'][0]['partner_name'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$emailId = $modelOutput['customerBpContactsInfo'][0]['contact_email_id'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$contactNumber = $modelOutput['customerBpContactsInfo'][0]['contact_number'];
	}

	if(isset($modelOutput['tax_code'])){
		$taxCode = $modelOutput['tax_code'];
	}
	

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . ',
				Contact No : ' . $contactNumber . '<br>
				Tax Code/ GST/ VAT : ' . $taxCode . '<br>
			</td>
		</tr>';

	// For Billing details.
	$billingAddress = "";
	$billingCountryName = "";
	$billingStateName = "";
	$billingCity = "";
	$billingZipcode = "";

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorPayToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorPayToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorPayToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorPayToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'];
	}

	$taxCode = "";
	if(isset($modelOutput['vendorBpInfo'][0]['tax_code'])){
		$taxCode = $modelOutput['vendorBpInfo'][0]['tax_code'];
	}

	$salesEmpName = isset($modelOutput['salesEmpInfo'][0]['employee_name']) ? $modelOutput['salesEmpInfo'][0]['employee_name'] : '';
	
	$billToHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : '.$salesEmpName.'
				</td>						
			</tr>';

	$currencyLabel = "Currency: ";
	$currencyName = "";
	if(isset($modelOutput['customerBpInfo'][0]['currency_name'])){
		$currencyName	 = $modelOutput['customerBpInfo'][0]['currency_name'];
	}

	$totalBeforeDiscount = "";
	$discountPercentage = "";
	$discountValue = "";
	$roundingValue = 0;
	$taxPercentage = 0;
	$totalAmount = 0;
	
	if(isset($modelOutput['total_before_discount'])){
		$totalBeforeDiscount = $modelOutput['total_before_discount'];
	}

	if(isset($modelOutput['discount_percentage'])){
		$discountPercentage = $modelOutput['discount_percentage'];
	}
	
	if(isset($modelOutput['discount_value'])){
		$discountValue	= $modelOutput['discount_value'];
	}
	
	if(isset($modelOutput['rounding'])){
		$roundingValue	= $modelOutput['rounding'];
	}

	if(isset($modelOutput['tax_percentage'])){
		$taxPercentage	= $modelOutput['tax_percentage'];
	}

	if(isset($modelOutput['total_amount'])){
		$totalAmount = $modelOutput['total_amount'];
	}

	$totalBeforeTax	= 0;

	$totalBeforeTax	= $totalAmount - $taxPercentage;

	$paymentTerm = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$paymentTerm = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	$remarks = "";
	if(isset($modelOutput['remarks'])){
		$remarks = $modelOutput['remarks'];
	}

	// Summary Page. 
	$invoiceSummaryHtml  = '<!-- Summary -->
				<div class="summary">
					<table>
						<tr>
							<td>Sub Total:</td>
							<td>' . $totalBeforeDiscount . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>DP: ' . $discountPercentage . ' %</td>
							<td>' . $discountValue . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Total Before Tax</td>
							<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Rounding</td>
							<td>' . $roundingValue. '</td>
						</tr>
						<tr>
							<td>Tax Amount</td>
							<td>' . $taxPercentage . ' ' . $currencyName . '</td>
						</tr>
						<tr class="strong">
							<td>Total Amount</td>
							<td>' . $totalAmount . ' ' . $currencyName . '</td>
						</tr>
					</table>
				</div>
				
				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(INVOICE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $billToHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<INVOICE_HEADING>>", 
			"<<PAGE_NO>>",
			"<<TOTAL_PAGE_NUMBER>>",
			"<<CUSTOMER_INFO_DETAILS>>",
			"<<CURRENCY_LABEL>>",
			"<<CURRENCY>>",
			"<<BRANCH_NAME>>",
			"<<COMPANY_NAME>>",
			 "<<COMPANY_ADDRESS>>",
			"<<COMPANY_TAX_NUMBER>>",
			"<<COMPANY_LOGO>>",
			"<<DOCUMENT_NUMBER>>", 
			"<<DOCUMENT_DATE>>",
			"<<BILL_TO_INFO>>",
			"<<CONTENT_INFO_HEADING>>",
			"<<CONTENT_INFO>>",
			"<<INVOICE_SUMMARY_BLOCK>>",
			 "<<FOOTER_CONTENT>>"
		);

		$branchName = "";
		$companyName = "";
		$location = "";
		$taxNumber = "";
		$documentNumber = "";
		$documentDate = "";

		if(isset($modelOutput['branchInfo'][0]['branch_name'])){
			$branchName = $modelOutput['branchInfo'][0]['branch_name'];
		}

		if(isset($companyDetails[0]['company_name'])){
			$companyName = $companyDetails[0]['company_name'];
		}

		if(isset($companyDetails[0]['location'])){
			$location = $companyDetails[0]['location'];
		}

		if(isset($companyDetails[0]['tax_number'])){
			$taxNumber = $companyDetails[0]['tax_number'];
		}

		if(isset($modelOutput['document_number'])){
			$documentNumber = $modelOutput['document_number'];
		}

		if(isset($modelOutput['document_date'])){
			$documentDate = $modelOutput['document_date'];
		}

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	// Assigning Mail Generation Path.
	$fileGenerationPath = INVOICE_GENERATION_PATH;
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}


/**
 * @METHOD NAME 	: generateSalesTrasactionInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR SALES TRANSACTION.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateSalesTrasactionInvoice($invoiceProcessData)
{

	$CI = &get_instance();
	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
	
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;
			$itemName = "";
			$itemCode = "";
			$discountPercentage = "";
			$quantity = "";
			$uomName = "";
			$unitPrice = "";
			$itemTaxPercentage = "";
			$itemTaxValue = "";
			$totalItemAmount = "";
			//$itemWarehouseName = "";
			$itemBinName = "";
			$itemHsnName = "";
			
			if(isset($itemListValue['itemInfo'][0]['item_name'])){
				$itemName = $itemListValue['itemInfo'][0]['item_name'];
			}

			if(isset($itemListValue['itemInfo'][0]['item_code'])){
				$itemCode = $itemListValue['itemInfo'][0]['item_code'];
			}
			if(isset($itemListValue['itemInfo'][0]['discount_percentage'])){
				$discountPercentage = $itemListValue['itemInfo'][0]['discount_percentage'];
			}

			// if(isset($itemListValue['warehouseInfo'][0]['warehouse_name'])){
			// 	$itemWarehouseName = $itemListValue['warehouseInfo'][0]['warehouse_name'];
			// }

			if(isset($itemListValue['binInfo'][0]['bin_name'])){
				$itemBinName = $itemListValue['binInfo'][0]['bin_name'];
			}
			
			if(isset($itemListValue['hsnInfo'][0]['hsn_code'])){
				$itemHsnName = $itemListValue['hsnInfo'][0]['hsn_code'];
			}
			
			if(isset($itemListValue['quantity'])){
				$quantity = $itemListValue['quantity'];
			}
			if(isset($itemListValue['itemInfo'][0]['uom_name'])){
				$uomName = $itemListValue['itemInfo'][0]['uom_name'];
			}
			if(isset($itemListValue['unit_price'])){
				$unitPrice = $itemListValue['unit_price'];
			}
			if(isset($itemListValue['item_tax_percentage'])){
				$itemTaxPercentage = $itemListValue['item_tax_percentage'];
			}
			if(isset($itemListValue['item_tax_value'])){
				$itemTaxValue = $itemListValue['item_tax_value'];
			}
			if(isset($itemListValue['total_item_amount'])){
				$totalItemAmount = $itemListValue['total_item_amount'];
			}
			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Description</th>
				<th class="text-left">Bin</th>
				<th class="text-left">Quantity</th>
				<th class="text-left">UoM</th>
				<th class="text-left">Price</th>
				<th class="text-left">Tax %</th>
				<th class="text-left">Tax Value</th>
				<th class="text-left">Total</th>
			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<div><span class="ash-color">Discount:</span> ' . $discountPercentage . '%</div></td>
			<td class="text-left">' . $itemBinName . '</td>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $uomName . '</td>
			<td class="text-left">' . $unitPrice . '</td>
			<td class="text-left">' . $itemTaxPercentage . '</td>
			<td class="text-left">' . $itemTaxValue . '</td>
			<td class="text-left">' . $totalItemAmount . '</td>
			</tr>';

			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}


	$partnerCode = "";
	$referenceNo = "";
	if(isset($modelOutput['customerBpInfo'][0]['partner_code'])){
		$partnerCode = $modelOutput['customerBpInfo'][0]['partner_code'];
	}

	if(isset($modelOutput['reference_number'])){
		$referenceNo = $modelOutput['reference_number'];
	}

	// For Customer Info details.
	$customerInfoHtml = '<tr>
			<td>
				<div class="ash-color">Customer No</div>
				' . $partnerCode . '
			</td>
			<td>
				<div class="ash-color">Reference No</div>
				' . $referenceNo . '
			</td>';
		
	if(isset($modelOutput['due_date'])){
		$customerInfoHtml .='<td>
					<div class="ash-color">Due Date</div>
					' . $modelOutput['due_date'] . '
				</td>
			</tr>';
	}

	if(isset($modelOutput['delivery_date'])){
		$customerInfoHtml .='<td>
		<div class="ash-color">Delivery Date</div>
		' . $modelOutput['delivery_date'] . '
			</td>
		</tr>';
	}

	// // For delivery details.
	// $deliveryAddress = "";
	// $deliveryCountryName = "";
	// $deliveryStateName = "";
	// $deliveryCity = "";
	// $deliveryZipcode = "";

	// if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['address'])){
	// 	$deliveryAddress = $modelOutput['vendorShipToBpAddressInfo'][0]['address'];
	// }

	// if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['countryName'])){
	// 	$deliveryCountryName = $modelOutput['vendorShipToBpAddressInfo'][0]['countryName'];
	// }

	// if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['stateName'])){
	// 	$deliveryStateName = $modelOutput['vendorShipToBpAddressInfo'][0]['stateName'];
	// }

	// if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['city'])){
	// 	$deliveryCity = $modelOutput['vendorShipToBpAddressInfo'][0]['city'];
	// }

	// if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'])){
	// 	$deliveryZipcode = $modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'];
	// }

	// Customer Details
	$customerName = "";
	$emailId = "";
	$contactNumber = "";
	$contactType = "";

	if(isset($modelOutput['customerBpInfo'][0]['partner_name'])){
		$customerName = $modelOutput['customerBpInfo'][0]['partner_name'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$emailId = $modelOutput['customerBpContactsInfo'][0]['contact_email_id'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$contactNumber = $modelOutput['customerBpContactsInfo'][0]['contact_number'];
	}

	if(isset($modelOutput['tax_code'])){
		$taxCode = $modelOutput['tax_code'];
	}


	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . ',
				Contact No : ' . $contactNumber . '<br>
				Tax Code/ GST/ VAT : ' . $taxCode . '<br>
			</td>
		</tr>';

	// // For Billing details.
	// $billingAddress = "";
	// $billingCountryName = "";
	// $billingStateName = "";
	// $billingCity = "";
	// $billingZipcode = "";

	// if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['address'])){
	// 	$deliveryAddress = $modelOutput['vendorPayToBpAddressInfo'][0]['address'];
	// }

	// if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['countryName'])){
	// 	$deliveryCountryName = $modelOutput['vendorPayToBpAddressInfo'][0]['countryName'];
	// }

	// if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['stateName'])){
	// 	$deliveryStateName = $modelOutput['vendorPayToBpAddressInfo'][0]['stateName'];
	// }

	// if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['city'])){
	// 	$deliveryCity = $modelOutput['vendorPayToBpAddressInfo'][0]['city'];
	// }

	// if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'])){
	// 	$deliveryZipcode = $modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'];
	// }

	// $taxCode = "";
	// if(isset($modelOutput['vendorBpInfo'][0]['tax_code'])){
	// 	$taxCode = $modelOutput['vendorBpInfo'][0]['tax_code'];
	// }

	$salesEmpName = isset($modelOutput['salesEmpInfo'][0]['employee_name']) ? $modelOutput['salesEmpInfo'][0]['employee_name'] : '';
	
	$employeeDetailHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : '.$salesEmpName.'
				</td>						
			</tr>';

	$deliveryStatus = '';
	if(isset($modelOutput['delivery_status']) && $modelOutput['delivery_status'] != 0){
		$deliveryStatus = $CI->config->item('DELIVERY_STATUS')[$modelOutput['delivery_status']];
	}	

	$logisticInfoHtml = '<tr>
			<td colspan=2 class="ash-color">Logistics Info</td>
		</tr>
			<tr>
			<td>
				Ship To : '.$modelOutput['customer_ship_to_address'].'
			</td><td>
			Bill To : '.$modelOutput['customer_bill_to_address'].'
		</td>						
		</tr><tr>
		<td>
			Courier Tracking Number : '.(isset($modelOutput['tracking_number'])? $modelOutput['tracking_number'] : "-").'
		</td><td>
		Delivery Status : '.$deliveryStatus.'
	</td>						
	</tr>';
	
	$currencyLabel = "Currency: ";
	$currencyName = "";
	if(isset($modelOutput['customerBpInfo'][0]['currency_name'])){
		$currencyName	 = $modelOutput['customerBpInfo'][0]['currency_name'];
	}

	$totalBeforeDiscount = "";
	$discountPercentage = "";
	$discountValue = "";
	$roundingValue = 0;
	$taxPercentage = 0;
	$totalAmount = 0;
	
	if(isset($modelOutput['total_before_discount'])){
		$totalBeforeDiscount = $modelOutput['total_before_discount'];
	}

	if(isset($modelOutput['discount_percentage'])){
		$discountPercentage = $modelOutput['discount_percentage'];
	}
	
	if(isset($modelOutput['discount_value'])){
		$discountValue	= $modelOutput['discount_value'];
	}
	
	if(isset($modelOutput['rounding'])){
		$roundingValue	= $modelOutput['rounding'];
	}

	if(isset($modelOutput['tax_percentage'])){
		$taxPercentage	= $modelOutput['tax_percentage'];
	}

	if(isset($modelOutput['total_amount'])){
		$totalAmount = $modelOutput['total_amount'];
	}

	$totalBeforeTax	= 0;

	$totalBeforeTax	= $totalAmount - $taxPercentage;

	$paymentTerm = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$paymentTerm = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	$remarks = "";
	if(isset($modelOutput['remarks'])){
		$remarks = $modelOutput['remarks'];
	}

	$dpdocumentNumber = "";
	if(isset($modelOutput['salesArDpInvoiceDocumentInfo'][0]['document_number'])){
		$dpdocumentNumber = $modelOutput['salesArDpInvoiceDocumentInfo'][0]['document_number'];
	}

	$dptotalAmount = "";
	if(isset($modelOutput['sales_ar_dp_invoice_used_amount'])){
		$dptotalAmount = $modelOutput['sales_ar_dp_invoice_used_amount'];
	}

	// Summary Page. 
	$invoiceSummaryHtml  = '<!-- Summary -->
				<div class="summary">
					<table>
						<tr>
							<td>Sub Total:</td>
							<td>' . $totalBeforeDiscount . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>DP: ' . $discountPercentage . ' %</td>
							<td>' . $discountValue . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Total Before Tax</td>
							<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Rounding</td>
							<td>' . $roundingValue. '</td>
						</tr>
						<tr>
							<td>Tax Amount</td>
							<td>' . $taxPercentage . ' ' . $currencyName . '</td>
						</tr>
						<tr class="strong">
							<td>Total Amount</td>
							<td>' . $totalAmount . ' ' . $currencyName . '</td>
						</tr>';
				
				if($dpdocumentNumber != ""){
					$invoiceSummaryHtml .= '<tr>
					<td>Dp Invoice Document No</td>
						<td>' . $dpdocumentNumber. '</td>
					</tr>
					<tr>
						<td>Dp Invoice  Total Amount</td>
						<td>' . $dptotalAmount . '</td>
					</tr>';
				}

				$invoiceSummaryHtml .= '</table>
				</div>

				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(INVOICE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $employeeDetailHtml.$logisticInfoHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<INVOICE_HEADING>>", 
			"<<PAGE_NO>>",
			"<<TOTAL_PAGE_NUMBER>>",
			"<<CUSTOMER_INFO_DETAILS>>",
			"<<CURRENCY_LABEL>>",
			"<<CURRENCY>>",
			"<<BRANCH_NAME>>",
			"<<COMPANY_NAME>>",
			 "<<COMPANY_ADDRESS>>",
			"<<COMPANY_TAX_NUMBER>>",
			"<<COMPANY_LOGO>>",
			"<<DOCUMENT_NUMBER>>", 
			"<<DOCUMENT_DATE>>",
			"<<BILL_TO_INFO>>",
			"<<CONTENT_INFO_HEADING>>",
			"<<CONTENT_INFO>>",
			"<<INVOICE_SUMMARY_BLOCK>>",
			 "<<FOOTER_CONTENT>>"
		);

		$branchName = "";
		$companyName = "";
		$location = "";
		$taxNumber = "";
		$documentNumber = "";
		$documentDate = "";

		if(isset($modelOutput['branchInfo'][0]['branch_name'])){
			$branchName = $modelOutput['branchInfo'][0]['branch_name'];
		}

		if(isset($companyDetails[0]['company_name'])){
			$companyName = $companyDetails[0]['company_name'];
		}

		if(isset($companyDetails[0]['location'])){
			$location = $companyDetails[0]['location'];
		}

		if(isset($companyDetails[0]['tax_number'])){
			$taxNumber = $companyDetails[0]['tax_number'];
		}

		if(isset($modelOutput['document_number'])){
			$documentNumber = $modelOutput['document_number'];
		}

		if(isset($modelOutput['document_date'])){
			$documentDate = $modelOutput['document_date'];
		}

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	// Assigning Mail Generation Path.
	$fileGenerationPath = INVOICE_GENERATION_PATH;
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}



/**
 * @METHOD NAME 	: addCustomerDetails()
 *
 * @DESC 			: TO ADD CUSTOMER DETAILS WITH DYNAMIC TABLE ROWS.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function addCustomerDetails($htmlData, $recordData, $cntVal = 0){
	$tableHtml = "";
    if($cntVal == 0){
        $tableHtml = "<tr>";
	}
	if($recordData != ""){
		$tableHtml .= "<td>".$htmlData."".$recordData."</td>";
		$cntVal ++;

	}
	if($cntVal >= 3){
        $tableHtml .= "</tr>";
		$cntVal = 0;
	}

	$outputDataArr['html'] = $tableHtml;
	$outputDataArr['count'] = $cntVal;

	return $outputDataArr;
}


/**
 * @METHOD NAME 	: generateRentalTrasactionInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR RENTAL TRANSACTION
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateRentalTrasactionInvoice($invoiceProcessData)
{

	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];

	$branchName = isset($modelOutput['branchInfo'][0]['branch_name']) ? $modelOutput['branchInfo'][0]['branch_name'] : '';
	$companyName = isset($companyDetails[0]['company_name']) ? $companyDetails[0]['company_name'] : '';
	$location = isset($companyDetails[0]['location']) ? $companyDetails[0]['location'] : '';
	$taxNumber = isset($companyDetails[0]['tax_number']) ? $companyDetails[0]['tax_number'] : '';
	$documentNumber = isset($modelOutput['document_number']) ? $modelOutput['document_number'] : '';
	$documentDate = isset($modelOutput['document_date']) ? $modelOutput['document_date'] : '';

	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);

	$customerName = isset($modelOutput['customerBpInfo'][0]['partner_name']) ? $modelOutput['customerBpInfo'][0]['partner_name'] : '';
	$emailId = isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$contactNumber = isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$taxCode = isset($modelOutput['tax_code']) ? $modelOutput['tax_code'] : '';
	$empName = isset($modelOutput['empInfo'][0]['employee_name']) ? $modelOutput['empInfo'][0]['employee_name'] : '';
	$partnerCode = isset($modelOutput['customerBpInfo'][0]['partner_code']) ? $modelOutput['customerBpInfo'][0]['partner_code'] : '';	
	$referenceNo = isset($modelOutput['reference_number']) ? $modelOutput['reference_number'] : '';	
	$dueDate = isset($modelOutput['due_date']) ? $modelOutput['due_date'] : '';	
	$deliveryDate = isset($modelOutput['delivery_date']) ? $modelOutput['delivery_date'] : '';
	$postingDate = isset($modelOutput['posting_date']) ? $modelOutput['posting_date'] : '';	
	$statusName = isset($modelOutput['statusInfo'][0]['name']) ? $modelOutput['statusInfo'][0]['name'] : '';	


	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	
	// For Customer Info details.
    $customerInfoHtml = '';
	$tableDetails = addCustomerDetails('<div class="ash-color">Customer No</div>', $partnerCode, 0);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Reference No</div>', $referenceNo, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Due Date</div>', $dueDate, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Delivery Date</div>', $deliveryDate, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Posting Date</div>', $postingDate, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Status</div>', $statusName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . ',
				Contact No : ' . $contactNumber . '<br>
				Tax Code/ GST/ VAT : ' . $taxCode . '<br>
			</td>
		</tr>';

	$employeeDetailHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : '.$empName.'
				</td>						
			</tr>';




    // ITEM TABLE RECORDS - START.
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
	
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;
			// print_r($itemListValue);exit;
			$itemName = isset($itemListValue['rentalItemInfo'][0]['rental_item_name']) ? $itemListValue['rentalItemInfo'][0]['rental_item_name'] : '';
			$itemCode = isset($itemListValue['rentalItemInfo'][0]['rental_item_code']) ? $itemListValue['rentalItemInfo'][0]['rental_item_code'] : '';
			$discountPercentage = isset($itemListValue['discount_percentage']) ? $itemListValue['discount_percentage'] : '';
			$itemHsnName = isset($itemListValue['hsnInfo'][0]['hsn_code']) ? $itemListValue['hsnInfo'][0]['hsn_code'] : '';
			$quantity = isset($itemListValue['quantity']) ? $itemListValue['quantity'] : '';
			$uomName = isset($itemListValue['uomInfo'][0]['uom_name']) ? $itemListValue['uomInfo'][0]['uom_name'] : '';
			$unitPrice = isset($itemListValue['unit_price']) ? $itemListValue['unit_price'] : '';
			$itemTaxPercentage = isset($itemListValue['item_tax_percentage']) ? $itemListValue['item_tax_percentage'] : '';
			// $itemTaxValue = isset($itemListValue['item_tax_value']) ? $itemListValue['item_tax_value'] : '';
			$totalItemAmount = isset($itemListValue['total_item_amount']) ? $itemListValue['total_item_amount'] : '';
			$duration = isset($itemListValue['duration']) ? $itemListValue['duration'] : '';
			$itemStartDate = isset($itemListValue['start_date']) ? $itemListValue['start_date'] : '';
			$itemEndDate = isset($itemListValue['end_date']) ? $itemListValue['end_date'] : '';
			$itemShipDate = isset($itemListValue['ship_date']) ? $itemListValue['ship_date'] : '';
			$itemReturnDate = isset($itemListValue['return_date']) ? $itemListValue['return_date'] : '';
			$itemPoExpiryDate = isset($itemListValue['po_expiry_date']) ? $itemListValue['po_expiry_date'] : '';
			$assignEquipmentCard = isset($itemListValue['assign_equipment']) ? $itemListValue['assign_equipment'] : '';
			$taxCode = isset($itemListValue['taxInfo'][0]['tax_code']) ? $itemListValue['taxInfo'][0]['tax_code'] : '';
			$distRules = isset($itemListValue['distributionRulesInfo'][0]['distribution_name']) ? $itemListValue['distributionRulesInfo'][0]['distribution_name'] : '';

			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Description</th>
				<th class="text-left">Quantity</th>
				<th class="text-left">Duration</th>
				<th class="text-left">UoM</th>
				<th class="text-left">Start Date</th>
				<th class="text-left">End Date</th>
				<th class="text-left">Ship Date</th>
				<th class="text-left">Return Date</th>
				<th class="text-left">PO Expiry Date</th>
				<th class="text-left">Assign Equipment Card</th>
				<th class="text-left">Unit Price</th>
				<th class="text-left">HSN</th>
				<th class="text-left">Tax Code</th>
				<th class="text-left">Tax %</th>
				<th class="text-left">Total</th>
				<th class="text-left">Dist Rules</th>

			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<div><span class="ash-color">Discount:</span> ' . $discountPercentage . '%</div></td>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $duration . '</td>
			<td class="text-left">' . $uomName . '</td>
			<td class="text-left">' . $itemStartDate . '</td>
			<td class="text-left">' . $itemEndDate . '</td>
			<td class="text-left">' . $itemShipDate . '</td>
			<td class="text-left">' . $itemReturnDate . '</td>
			<td class="text-left">' . $itemPoExpiryDate . '</td>
			<td class="text-left">' . $assignEquipmentCard . '</td>
			<td class="text-left">' . $unitPrice . '</td>
			<td class="text-left">' . $itemHsnName . '</td>
			<td class="text-left">' . $taxCode . '</td>
			<td class="text-left">' . $itemTaxPercentage . '</td>
			<td class="text-left">' . $totalItemAmount . '</td>
			<td class="text-left">' . $distRules . '</td>
			</tr>';

			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}
    // ITEM TABLE RECORDS - END.

    // print_r($modelOutput);exit;
	$currencyLabel		 = "Currency: ";
	$currencyName 		 = isset($modelOutput['customerBpInfo'][0]['currency_name']) ? $modelOutput['customerBpInfo'][0]['currency_name'] : '';
	$remarks 			 = isset($modelOutput['remarks']) ? $modelOutput['remarks'] : '';
	$totalBeforeDiscount = isset($modelOutput['total_before_discount']) ? $modelOutput['total_before_discount'] : '';
	$discountPercentage  = isset($modelOutput['discount_percentage']) ? $modelOutput['discount_percentage'] : '';
	$discountValue 		 = isset($modelOutput['discount_value']) ? $modelOutput['discount_value'] : '';
	$roundingValue 		 = isset($modelOutput['rounding']) ? $modelOutput['rounding'] : '';
	$taxPercentage 		 = isset($modelOutput['tax_percentage']) ? $modelOutput['tax_percentage'] : '';
	$totalAmount 		 = isset($modelOutput['total_amount']) ? $modelOutput['total_amount'] : '';
	$paymentTerm 		 = isset($modelOutput['paymentTermsInfo'][0]['payment_term_name']) ? $modelOutput['paymentTermsInfo'][0]['payment_term_name'] : '';
	$termsAndConditionsBodyContent  = isset($modelOutput['termsandConditionsInfo'][0]['body_content']) ? $modelOutput['termsandConditionsInfo'][0]['body_content'] : '';
	$totalBeforeTax	= $totalAmount - $taxPercentage;
	
	// ITEM SUMMARY SECTION - START.
	$invoiceSummaryHtml  = '<!-- Summary -->
				<div class="summary">
					<table>
						<tr>
							<td>Sub Total:</td>
							<td>' . $totalBeforeDiscount . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>DP: ' . $discountPercentage . ' %</td>
							<td>' . $discountValue . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Total Before Tax</td>
							<td>' . $totalBeforeTax . ' ' . $currencyName . '</td>
						</tr>
						<tr>
							<td>Rounding</td>
							<td>' . $roundingValue . '</td>
						</tr>
						<tr>
							<td>Tax Amount</td>
							<td>' . $taxPercentage . ' ' . $currencyName . '</td>
						</tr>
						<tr class="strong">
							<td>Total Amount</td>
							<td>' . $totalAmount . ' ' . $currencyName . '</td>
						</tr>';
	// ITEM SUMMARY SECTION - END.


	// DOCUMENT SUMMARY SECTION - START.
	$invoiceSummaryHtml .= '</table>
				</div>

				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';
     // DOCUMENT SUMMARY SECTION - END.

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// TERMS AND CONDITIONS CONTENT 
	$termsAndConditionContent = $pageBreakContent. '
									<div> 
										Terms and Conditions
										<br>
										<div>'.$termsAndConditionsBodyContent.'</div>
									</div>';
								
	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(FILE_DOWNLOAD_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $employeeDetailHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<#INVOICE_HEADING>>", 
			"<<#PAGE_NO>>",
			"<<#TOTAL_PAGE_NUMBER>>",
			"<<#CUSTOMER_INFO_DETAILS>>",
			"<<#CURRENCY_LABEL>>",
			"<<#CURRENCY>>",
			"<<#BRANCH_NAME>>",
			"<<#COMPANY_NAME>>",
			"<<#COMPANY_ADDRESS>>",
			"<<#COMPANY_TAX_NUMBER>>",
			"<<#COMPANY_LOGO>>",
			"<<#DOCUMENT_NUMBER>>", 
			"<<#DOCUMENT_DATE>>",
			"<<#BILL_TO_INFO>>",
			"<<#CONTENT_INFO_HEADING>>",
			"<<#CONTENT_INFO>>",
			"<<#INVOICE_SUMMARY_BLOCK>>",
			"<<#FOOTER_CONTENT>>"
		);

	
		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	$printingData .= $termsAndConditionContent;

	// IF ACCESSED FOR DOCUMENT MAIL.
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}
	else{ // IF ACCESSED FOR DOCUMENT DOWNLOAD.
		$fileGenerationPath = INVOICE_GENERATION_PATH;
	}

	
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	
	// $getHtmlFile 		= $fileNameVal.'.html'; // For temp Check
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		// $fileName 	  = $time . "_$fileNameVal.html";  // For temp Check
		// $fileName 	  = "$fileNameVal.html";  // For temp Check

		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}




/**
 * @METHOD NAME 	: generateRentalInspectionTrasactionInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR RENTAL INSTPECTION TRANSACTION
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateRentalInspectionTrasactionInvoice($invoiceProcessData)
{

	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];

	$branchName = isset($modelOutput['branchInfo'][0]['branch_name']) ? $modelOutput['branchInfo'][0]['branch_name'] : '';
	$companyName = isset($companyDetails[0]['company_name']) ? $companyDetails[0]['company_name'] : '';
	$location = isset($companyDetails[0]['location']) ? $companyDetails[0]['location'] : '';
	$taxNumber = isset($companyDetails[0]['tax_number']) ? $companyDetails[0]['tax_number'] : '';
	$documentNumber = isset($modelOutput['document_number']) ? $modelOutput['document_number'] : '';
	$documentDate = isset($modelOutput['document_date']) ? $modelOutput['document_date'] : '';

	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);

	$customerName = isset($modelOutput['customerBpInfo'][0]['partner_name']) ? $modelOutput['customerBpInfo'][0]['partner_name'] : '';
	$emailId = isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$contactNumber = isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$taxCode = isset($modelOutput['tax_code']) ? $modelOutput['tax_code'] : '';
	$empName = isset($modelOutput['empInfo'][0]['employee_name']) ? $modelOutput['empInfo'][0]['employee_name'] : '';
	$partnerCode = isset($modelOutput['customerBpInfo'][0]['partner_code']) ? $modelOutput['customerBpInfo'][0]['partner_code'] : '';	
	$referenceNo = isset($modelOutput['reference_number']) ? $modelOutput['reference_number'] : '';	
	$equipmentName = isset($modelOutput['rentalEquipmentInfo'][0]['equipment_name']) ? $modelOutput['rentalEquipmentInfo'][0]['equipment_name'] : '';	
	$statusName = isset($modelOutput['statusInfo'][0]['name']) ? $modelOutput['statusInfo'][0]['name'] : '';	


	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	
	// For Customer Info details.
    $customerInfoHtml = '';
	$tableDetails = addCustomerDetails('<div class="ash-color">Customer No</div>', $partnerCode, 0);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Reference No</div>', $referenceNo, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Equipment Card</div>', $equipmentName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Status</div>', $statusName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . ',
				Contact No : ' . $contactNumber . '<br>
				Tax Code/ GST/ VAT : ' . $taxCode . '<br>
			</td>
		</tr>';

	$employeeDetailHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : '.$empName.'
				</td>						
			</tr>';


    // ITEM TABLE RECORDS - START.
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	$contentInfoHeading = "";
	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
	
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

		    $pageItems++;
			$name = isset($itemListValue->name) ? $itemListValue->name : '';
			$status = isset($itemListValue->status) ? $itemListValue->status : '';
			$remarks = isset($itemListValue->remarks) ? $itemListValue->remarks : '';
			$header = isset($itemListValue->header) ? $itemListValue->header : 0;
			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Inspection Details</th>
				<th class="text-left">Inspection Status</th>
				<th class="text-left">Inspection Remarks</th>
			</tr>
			';

			if($header == 1){
				$rowHtml = '<tr>
				<td class="text-left">' . $sNo . '</td>';
				$rowHtml .= '<td class="text-left"><b>' . $name . '</b></td>';
				$sNo++;
			}
			else{
				$rowHtml = '<tr>
				<td class="text-left"> </td>';
				$rowHtml .= '<td class="text-left">' . $name . '</td>';
			}

			$rowHtml .= '<td class="text-left">' . $status . '</td>
			<td class="text-left">' . $remarks . '</td>
			</tr>';

			
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}
    // ITEM TABLE RECORDS - END.

	$currencyLabel = "Currency: ";
	$currencyName = isset($modelOutput['customerBpInfo'][0]['currency_name']) ? $modelOutput['customerBpInfo'][0]['currency_name'] : '';
	$remarks = isset($modelOutput['remarks']) ? $modelOutput['remarks'] : '';
	

	// DOCUMENT SUMMARY SECTION - START.
	$paymentTerm = "";
	$invoiceSummaryHtml = '</table>
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';
     // DOCUMENT SUMMARY SECTION - END.
   


	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(FILE_DOWNLOAD_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $employeeDetailHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<#INVOICE_HEADING>>", 
			"<<#PAGE_NO>>",
			"<<#TOTAL_PAGE_NUMBER>>",
			"<<#CUSTOMER_INFO_DETAILS>>",
			"<<#CURRENCY_LABEL>>",
			"<<#CURRENCY>>",
			"<<#BRANCH_NAME>>",
			"<<#COMPANY_NAME>>",
			"<<#COMPANY_ADDRESS>>",
			"<<#COMPANY_TAX_NUMBER>>",
			"<<#COMPANY_LOGO>>",
			"<<#DOCUMENT_NUMBER>>", 
			"<<#DOCUMENT_DATE>>",
			"<<#BILL_TO_INFO>>",
			"<<#CONTENT_INFO_HEADING>>",
			"<<#CONTENT_INFO>>",
			"<<#INVOICE_SUMMARY_BLOCK>>",
			"<<#FOOTER_CONTENT>>"
		);

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}


	// IF ACCESSED FOR DOCUMENT MAIL.
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}
	else{ // IF ACCESSED FOR DOCUMENT DOWNLOAD.
		$fileGenerationPath = INVOICE_GENERATION_PATH;
	}
	
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	// $getHtmlFile 		= $fileNameVal.'.html'; // For temp Check
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		// $fileName 	  = $time . "_$fileNameVal.html";  // For temp Check
		// $fileName 	  = "$fileNameVal.html";  // For temp Check

		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	// print_r($outputData);exit;
	return $outputData;
}



/**
 * @METHOD NAME 	: generateRentalWorklogInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR RENTAL WORKLOG INVOICE.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateRentalWorklogInvoice($invoiceProcessData,$worklogSheetType)
{

	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];

	$branchName			= isset($modelOutput['branchInfo'][0]['branch_name']) ? $modelOutput['branchInfo'][0]['branch_name'] : '';
	$companyName		= isset($companyDetails[0]['company_name']) ? $companyDetails[0]['company_name'] : '';
	$location 			= isset($companyDetails[0]['location']) ? $companyDetails[0]['location'] : '';
	$taxNumber 			= isset($companyDetails[0]['tax_number']) ? $companyDetails[0]['tax_number'] : '';

	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);

	$customerName		= isset($modelOutput['customerBpInfo'][0]['partner_name']) ? $modelOutput['customerBpInfo'][0]['partner_name'] : '';
	$emailId			= isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$contactNumber 		= isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id']) ? $modelOutput['customerBpContactsInfo'][0]['contact_email_id'] : '';
	$empName			= isset($modelOutput['employeeInfo'][0]['employee_name']) ? $modelOutput['employeeInfo'][0]['employee_name'] : '';
	$partnerCode 		= isset($modelOutput['customerBpInfo'][0]['partner_code']) ? $modelOutput['customerBpInfo'][0]['partner_code'] : '';	
	
	$documentNumber 	= isset($modelOutput['document_number']) ? $modelOutput['document_number'] : '';
	$itemName			= isset($modelOutput['rentalItemInfo'][0]['rental_item_name']) ? $modelOutput['rentalItemInfo'][0]['rental_item_name'] : '';
	$equipmentName 		= isset($modelOutput['rentalEquipmentInfo'][0]['equipment_name']) ? $modelOutput['rentalEquipmentInfo'][0]['equipment_name'] : '';	
	$referenceNo		= isset($modelOutput['reference_number']) ? $modelOutput['reference_number'] : '';	
	$startDate 			= isset($modelOutput['start_date']) ? $modelOutput['start_date'] : '';
	$endDate 			= isset($modelOutput['end_date']) ? $modelOutput['end_date'] : '';
	// $shipDate = isset($modelOutput['end_date']) ? $modelOutput['end_date'] : '';
	$documentDate 		= isset($modelOutput['document_date']) ? $modelOutput['document_date'] : '';
	$statusName 		= isset($modelOutput['statusInfo'][0]['name']) ? $modelOutput['statusInfo'][0]['name'] : '';
	$totalBillableHours = isset($modelOutput['total_billable_hours']) ? $modelOutput['total_billable_hours'] : '';
	
	$distRules 			= isset($itemListValue['distributionRulesInfo'][0]['distribution_name']) ? $itemListValue['distributionRulesInfo'][0]['distribution_name'] : '';

	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	
	// For Customer Info details.
    $customerInfoHtml = '';
	$tableDetails = addCustomerDetails('<div class="ash-color">Customer No</div>', $partnerCode, 0);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Reference No</div>', $referenceNo, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Equipment Card</div>', $equipmentName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Item Name</div>', $itemName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Start Date</div>', $startDate, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">End Date</div>', $endDate, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Dist Rules</div>', $distRules, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$tableDetails = addCustomerDetails('<div class="ash-color">Status</div>', $statusName, $tableDetails['count']);
	$customerInfoHtml .= $tableDetails['html'];

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . '<br>
				Contact No : ' . $contactNumber . '<br>
			</td>
		</tr>';

	$employeeDetailHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : '.$empName.'
				</td>						
			</tr>';


    // ITEM TABLE RECORDS - START.
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	$contentInfoHeading = "";
	$totalPages = 0;
	
	// WORKLOG SHEET TYPE - 1
	if ($itemListCount > 0 && $worklogSheetType == 1) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
	
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

		    $pageItems++;
			$type = isset($itemListValue['itemWorklogTypeInfo'][0]['name']) ? $itemListValue['itemWorklogTypeInfo'][0]['name'] : '';
			$resource = isset($itemListValue['employeeInfo'][0]['employee_name']) ? $itemListValue['employeeInfo'][0]['employee_name'] : '';
			$startDateTime = isset($itemListValue['start_date_time']) ? $itemListValue['start_date_time'] : '';
			$endDateTime = isset($itemListValue['end_date_time']) ? $itemListValue['end_date_time'] : 0;
			$total = isset($itemListValue['total_hours']) ? $itemListValue['total_hours'] : 0;

			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Type</th>
				<th class="text-left" style="min-width: 150px">Resource</th>
				<th class="text-left">Start Date/Time</th>
				<th class="text-left">End Date/Time</th>
				<th class="text-left">Total Time</th>
			</tr>
			';

		
			$rowHtml = '<tr>
			<td class="text-left">'.$sNo.'</td>
			<td class="text-left">'.$type.'</td>
			<td class="text-left">'.$resource.'</td>
			<td class="text-left">'.$startDateTime.'</td>
			<td class="text-left">'.$endDateTime.'</td>
			<td class="text-left">'.$total.'</td>
			</tr>';
			
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
			$sNo++;
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}
	/* END OF WORKLOG SHEET TYPE 1 */
	
	// START OF WORKLOG SHEET TYPE - 2 */
	if ($itemListCount > 0 && $worklogSheetType == 2) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
	
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

		    $pageItems++;
			$entryDay		= isset($itemListValue['entry_day']) ? $itemListValue['entry_day'] : '';
			$entryDate		= isset($itemListValue['entry_date']) ? $itemListValue['entry_date'] : '';
			$shiftStartTime = isset($itemListValue['shift_start_time']) ? $itemListValue['shift_start_time'] : '';
			$shiftEndTime 	= isset($itemListValue['shift_end_time']) ? $itemListValue['shift_end_time'] : '';
			$totalHours 	= isset($itemListValue['total_hours']) ? $itemListValue['total_hours'] : '';
			$breakDownHours = isset($itemListValue['breakdown_hours']) ? $itemListValue['breakdown_hours'] : '';
			$overTimeHours 	= isset($itemListValue['overtime_hours']) ? $itemListValue['overtime_hours'] : '';
			$billableHours 	= isset($itemListValue['billable_hours']) ? $itemListValue['billable_hours'] : '';
			
			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 80px">Day</th>
				<th class="text-left" style="min-width: 80px">Date</th>
				<th class="text-left" style="min-width: 80px">Shift Start Time</th>
				<th class="text-left" style="min-width: 80px">Shift End Time</th>
				<th class="text-left" style="min-width: 80px">Total Hours</th>
				<th class="text-left" style="min-width: 80px">Breakdown Hours</th>
				<th class="text-left" style="min-width: 80px">OverTime Hours</th>
				<th class="text-left" style="min-width: 80px">Billable Hours</th>
			</tr>
			';

		
			$rowHtml = '<tr>
			<td class="text-left">'.$sNo.'</td>
			<td class="text-left">'.$entryDay.'</td>
			<td class="text-left">'.$entryDate.'</td>
			<td class="text-left">'.$shiftStartTime.'</td>
			<td class="text-left">'.$shiftEndTime.'</td>
			<td class="text-left">'.$totalHours.'</td>
			<td class="text-left">'.$breakDownHours.'</td>
			<td class="text-left">'.$overTimeHours.'</td>
			<td class="text-left">'.$billableHours.'</td>
			</tr>';
			
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
			$sNo++;
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
		
	}
	
    // ITEM TABLE RECORDS - END.
	$currencyLabel 	= "Currency: ";
	$currencyName 	= isset($modelOutput['customerBpInfo'][0]['currency_name']) ? $modelOutput['customerBpInfo'][0]['currency_name'] : '';
	$remarks 		= isset($modelOutput['remarks']) ? $modelOutput['remarks'] : '';
	

	// DOCUMENT SUMMARY SECTION - START.
	$paymentTerm = "";
	$invoiceSummaryHtml = '</table>
				</div>
				<div class="summary">
					<table>
						<tr>
							<td class="text-right" colspan="8">
								<strong>Billable Hours</strong>
							</td>
							<td class="text-left">
								<strong>'.$totalBillableHours.' Hrs</strong>
							</td>
						</tr>
					</table>
				</div>

				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';
     // DOCUMENT SUMMARY SECTION - END.
   


	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(FILE_DOWNLOAD_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $employeeDetailHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<#INVOICE_HEADING>>", 
			"<<#PAGE_NO>>",
			"<<#TOTAL_PAGE_NUMBER>>",
			"<<#CUSTOMER_INFO_DETAILS>>",
			"<<#CURRENCY_LABEL>>",
			"<<#CURRENCY>>",
			"<<#BRANCH_NAME>>",
			"<<#COMPANY_NAME>>",
			"<<#COMPANY_ADDRESS>>",
			"<<#COMPANY_TAX_NUMBER>>",
			"<<#COMPANY_LOGO>>",
			"<<#DOCUMENT_NUMBER>>", 
			"<<#DOCUMENT_DATE>>",
			"<<#BILL_TO_INFO>>",
			"<<#CONTENT_INFO_HEADING>>",
			"<<#CONTENT_INFO>>",
			"<<#INVOICE_SUMMARY_BLOCK>>",
			"<<#FOOTER_CONTENT>>"
		);

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$branchName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}


	// IF ACCESSED FOR DOCUMENT MAIL.
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}
	else{ // IF ACCESSED FOR DOCUMENT DOWNLOAD.
		$fileGenerationPath = INVOICE_GENERATION_PATH;
	}
	
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	// $getHtmlFile 		= $fileNameVal.'.html'; // For temp Check
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		// $fileName 	  = $time . "_$fileNameVal.html";  // For temp Check
		// $fileName 	  = "$fileNameVal.html";  // For temp Check

		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	// print_r($outputData);exit;
	return $outputData;
}




/**
 * @METHOD NAME 	: generateSalesTrasactionPicklistInvoice()
 *
 * @DESC 			: TO GET THE INVOICE DETAILS FOR SALES TRANSACTION (PICKLIST).
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateSalesTrasactionPicklistInvoice($invoiceProcessData)
{
	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];
	$itemList 			= $invoiceProcessData['itemList'];
	$itemRowCountVal 	= $invoiceProcessData['itemRowCountVal'];
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Getting Company Logo.
	$companyLogo		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$itemListCount 		= count($itemList);
	$itemRowCount 		= $itemRowCountVal;

	if ($itemListCount > 0) {
		// Calculating total number of Pages.					
		$totalPages  = ceil($itemListCount / $itemRowCount); 
		$sNo 			= 1;
		$itemRowInc 	= 1;
		$pageItems = 0;
		// Looping for each Item list records.
		foreach ($itemList as $itemListValue) {

			$pageItems++;
			$itemName = "";
			$itemCode = "";
			$discountPercentage = "";
			$quantity = "";
			$uomName = "";
			$unitPrice = "";
			$itemTaxPercentage = "";
			$itemTaxValue = "";
			$totalItemAmount = "";
			$binName = "";
			$warehouseName = "";
			
			
			if(isset($itemListValue['itemInfo'][0]['item_name'])){
				$itemName = $itemListValue['itemInfo'][0]['item_name'];
			}
			if(isset($itemListValue['itemInfo'][0]['item_code'])){
				$itemCode = $itemListValue['itemInfo'][0]['item_code'];
			}
			if(isset($itemListValue['itemInfo'][0]['discount_percentage'])){
				$discountPercentage = $itemListValue['itemInfo'][0]['discount_percentage'];
			}
			if(isset($itemListValue['quantity'])){
				$quantity = $itemListValue['quantity'];
			}
			if(isset($itemListValue['itemInfo'][0]['uom_name'])){
				$uomName = $itemListValue['itemInfo'][0]['uom_name'];
			}
			if(isset($itemListValue['unit_price'])){
				$unitPrice = $itemListValue['unit_price'];
			}
			if(isset($itemListValue['item_tax_percentage'])){
				$itemTaxPercentage = $itemListValue['item_tax_percentage'];
			}
			if(isset($itemListValue['item_tax_value'])){
				$itemTaxValue = $itemListValue['item_tax_value'];
			}
			if(isset($itemListValue['total_item_amount'])){
				$totalItemAmount = $itemListValue['total_item_amount'];
			}

			if(isset($itemListValue['warehouseInfo'][0]['warehouse_name'])){
				$warehouseName = $itemListValue['warehouseInfo'][0]['warehouse_name'];
			}
			if(isset($itemListValue['binInfo'][0]['bin_name'])){
				$binName = $itemListValue['binInfo'][0]['bin_name'];
			}
			
			$contentInfoHeading = '
			<tr>
				<th class="text-left">Sno</th>
				<th class="text-left" style="min-width: 150px">Description</th>
				<th class="text-left">Quantity</th>
				<th class="text-left">Warehouse</th>
				<th class="text-left">BinLocation</th>
			</tr>
			';

			$rowHtml = '<tr>
			<td class="text-left">' . $sNo . '</td>
			<td class="text-left"><div class="semi-strong">' . $itemName . '</div>
			<div><span class="ash-color">Item Code:</span>' . $itemCode . '</div>
			<td class="text-left">' . $quantity . '</td>
			<td class="text-left">' . $warehouseName . '</td>
			<td class="text-left">' . $binName . '</td>
			</tr>';
			$sNo++;
			$itemRowHtml = $itemRowHtml . "" . $rowHtml;

			if ($pageItems == $itemRowCount) {
				$itemRow[$itemRowInc] = $itemRowHtml;
				$itemRowHtml = "";
				$pageItems = 0;
				$itemRowInc++;
			}
		}

		// Last Value.
		if (!empty($itemRowHtml)) {
			$itemRow[$itemRowInc] = $itemRowHtml;
		}
	}


	$partnerCode = "";
	$referenceNo = "";
	if(isset($modelOutput['customerBpInfo'][0]['partner_code'])){
		$partnerCode = $modelOutput['customerBpInfo'][0]['partner_code'];
	}

	if(isset($modelOutput['reference_number'])){
		$referenceNo = $modelOutput['reference_number'];
	}

	// For Customer Info details.
	$customerInfoHtml = '<tr>
			<td>
				<div class="ash-color">Customer No</div>
				' . $partnerCode . '
			</td>
			<td>
				<div class="ash-color">Reference No</div>
				' . $referenceNo . '
			</td>';
		
	if(isset($modelOutput['due_date'])){
		$customerInfoHtml .='<td>
					<div class="ash-color">Due Date</div>
					' . $modelOutput['due_date'] . '
				</td>
			</tr>';
	}

	if(isset($modelOutput['delivery_date'])){
		$customerInfoHtml .='<td>
		<div class="ash-color">Delivery Date</div>
		' . $modelOutput['delivery_date'] . '
			</td>
		</tr>';
	}

	// For delivery details.
	$deliveryAddress = "";
	$deliveryCountryName = "";
	$deliveryStateName = "";
	$deliveryCity = "";
	$deliveryZipcode = "";

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorShipToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorShipToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorShipToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorShipToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorShipToBpAddressInfo'][0]['zipcode'];
	}

	// Customer Details
	$customerName = "";
	$emailId = "";
	$contactNumber = "";
	$contactType = "";

	if(isset($modelOutput['customerBpInfo'][0]['partner_name'])){
		$customerName = $modelOutput['customerBpInfo'][0]['partner_name'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$emailId = $modelOutput['customerBpContactsInfo'][0]['contact_email_id'];
	}

	if(isset($modelOutput['customerBpContactsInfo'][0]['contact_email_id'])){
		$contactNumber = $modelOutput['customerBpContactsInfo'][0]['contact_number'];
	}

	if(isset($modelOutput['tax_code'])){
		$taxCode = $modelOutput['tax_code'];
	}
	

	$customerInfoHtml .='<tr>
			<td colspan="3">
				<div class="ash-color"><br>Customer Details</div>
				Name : ' . $customerName . '<br>
				Email Id : ' . $emailId . ',
				Contact No : ' . $contactNumber . '<br>
				Tax Code/ GST/ VAT : ' . $taxCode . '<br>
			</td>
		</tr>';

	// For Billing details.
	$billingAddress = "";
	$billingCountryName = "";
	$billingStateName = "";
	$billingCity = "";
	$billingZipcode = "";

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['address'])){
		$deliveryAddress = $modelOutput['vendorPayToBpAddressInfo'][0]['address'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['countryName'])){
		$deliveryCountryName = $modelOutput['vendorPayToBpAddressInfo'][0]['countryName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['stateName'])){
		$deliveryStateName = $modelOutput['vendorPayToBpAddressInfo'][0]['stateName'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['city'])){
		$deliveryCity = $modelOutput['vendorPayToBpAddressInfo'][0]['city'];
	}

	if(isset($modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'])){
		$deliveryZipcode = $modelOutput['vendorPayToBpAddressInfo'][0]['zipcode'];
	}

	$taxCode = "";
	if(isset($modelOutput['vendorBpInfo'][0]['tax_code'])){
		$taxCode = $modelOutput['vendorBpInfo'][0]['tax_code'];
	}

	$billToHtml = '<tr>
				<td class="ash-color">Sales Employee Details</td>
			</tr>
				<tr>
				<td>
					Name : ' . $modelOutput['salesEmpInfo'][0]['employee_name'] . '
				</td>						
			</tr>';

	$currencyLabel = "";
	$currencyName = "";
	// if(isset($modelOutput['customerBpInfo'][0]['currency_name'])){
	// 	$currencyName	 = $modelOutput['customerBpInfo'][0]['currency_name'];
	// }

	$totalBeforeDiscount = "";
	$discountPercentage = "";
	$discountValue = "";
	$taxPercentage = 0;
	$totalAmount = 0;
	
	if(isset($modelOutput['total_before_discount'])){
		$totalBeforeDiscount = $modelOutput['total_before_discount'];
	}

	if(isset($modelOutput['discount_percentage'])){
		$discountPercentage = $modelOutput['discount_percentage'];
	}
	
	if(isset($modelOutput['discount_value'])){
		$discountValue	= $modelOutput['discount_value'];
	}
	
	if(isset($modelOutput['tax_percentage'])){
		$taxPercentage	= $modelOutput['tax_percentage'];
	}

	if(isset($modelOutput['total_amount'])){
		$totalAmount = $modelOutput['total_amount'];
	}

	$totalBeforeTax	= 0;

	$totalBeforeTax	= $totalAmount - $taxPercentage;
	
	$remarks = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$remarks = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	$paymentTerm = "";
	if(isset($modelOutput['paymentTermsInfo'][0]['payment_term_name'])){
		$paymentTerm = $modelOutput['paymentTermsInfo'][0]['payment_term_name'];
	}

	// Summary Page. 
	$invoiceSummaryHtml  = '<!-- Summary -->
				<br><br><br>
				<!-- PAYMENT TERMS -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 100px">Payament Term:</td>
							<td>' . $paymentTerm . '</td>
						</tr>
					</table>			
				</div>
				<!-- SIGNATURE -->
				<div class="space"></div>
				<div>
					<table>
						<tr>
							<td style="width: 50px">SIGNATURE:</td>
							<td><input type="text" class="signature" /></td>
							<td style="width: 50px">DATE:</td>
							<td><input type="text" class="signature" /></td>
						</tr>
					</table>			
				</div>
				<!-- NOTE -->
				<div class="space"></div>
				<div class="note">
					<table>
						<tr>
							<td style="width: 100px">Note:</td>
							<td>' . $remarks . '</td>
						</tr>
					</table>
				</div>';

	// Footer content data.
	$footerContent = '<p style="height:0px;"></p>
					<div align="center">Thanks. We appreciate your business
					This '.$footerLabelHeading.' has been generated using <a href="www.x-factr.com">x-factr</a>. 
					</div>';

	$pageBreakContent = '<div style = "display:block; clear:both; page-break-after:always;"></div>';

	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {
		$getContentData = file_get_contents(INVOICE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Show only to first Page.
		$customerInfoDetails  = "";
		$billInfoDetails	  = "";
		if ($page == 1) {
			$customerInfoDetails  	= $customerInfoHtml;
			$billInfoDetails		= $billToHtml;
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<INVOICE_HEADING>>", 
			"<<PAGE_NO>>",
			"<<TOTAL_PAGE_NUMBER>>",
			"<<CUSTOMER_INFO_DETAILS>>",
			"<<CURRENCY_LABEL>>",
			"<<CURRENCY>>",
			"<<BRANCH_NAME>>",
			"<<COMPANY_NAME>>",
			"<<COMPANY_ADDRESS>>",
			"<<COMPANY_TAX_NUMBER>>",
			"<<COMPANY_LOGO>>",
			"<<DOCUMENT_NUMBER>>", 
			"<<DOCUMENT_DATE>>",
			"<<BILL_TO_INFO>>",
			"<<CONTENT_INFO_HEADING>>",
			"<<CONTENT_INFO>>",
			"<<INVOICE_SUMMARY_BLOCK>>",
			 "<<FOOTER_CONTENT>>"
		);

		$companyName = "";
		$location = "";
		$taxNumber = "";
		$documentNumber = "";
		$documentDate = "";
		if(isset($companyDetails[0]['company_name'])){
			$companyName = $companyDetails[0]['company_name'];
		}

		if(isset($companyDetails[0]['location'])){
			$location = $companyDetails[0]['location'];
		}

		if(isset($companyDetails[0]['tax_number'])){
			$taxNumber = $companyDetails[0]['tax_number'];
		}

		if(isset($modelOutput['document_number'])){
			$documentNumber = $modelOutput['document_number'];
		}

		if(isset($modelOutput['document_date'])){
			$documentDate = $modelOutput['document_date'];
		}

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$fileHeadingName, 
			$page, 
			$totalPages,
			$customerInfoDetails,
			$currencyLabel,
			$currencyName,
			$companyName,
			$location, 
			$taxNumber,
			$companyLogo,
			$documentNumber,
			$documentDate,
			$billInfoDetails,
			$contentInfoHeading,
			$itemRow[$page],
			$summaryHtmlData, 
			$footerContent
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	// Assigning Mail Generation Path.
	$fileGenerationPath = INVOICE_GENERATION_PATH;
	if($invoiceProcessData['isMailDoc'] == 1){
		$fileGenerationPath = INVOICE_MAIL_GENERATION_PATH;
	}

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	$fileLocation		= $fileGenerationPath . $getHtmlFile;

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		//force_download($fileName, $fileDetails);
		if($invoiceProcessData['isMailDoc'] == 1){
			//$fileDetails = str_replace(".pdf",".html",$fileDetails);
			$outputData['url']  = $fileDetails;
		}
		else{
			$invoiceFile 		= getFullImgUrl('invoice', $fileName);
			$outputData['url']  = $invoiceFile;
		}
	}

	return $outputData;
}



/**
 * @METHOD NAME 	: generateGatepassBarCode()
 *
 * @DESC 			: TO GENERATE THE GATEPASS BARCODE
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function generateGatepassBarCodePdf($invoiceProcessData){

	$outputData['status']   = "SUCCESS";
	$itemRowHtml 			= "";

	// Assigning Values back.
	$companyDetails 	= $invoiceProcessData['companyDetails'];
	$modelOutput 		= $invoiceProcessData['modelOutput'];
	$barcodeHtml 		= $invoiceProcessData['barcodeHtml'];

    // Company Details.
	$companyLogoImg		= getFullImgUrl('companylogo', $companyDetails[0]['company_logo']);
	$companyName		= isset($companyDetails[0]['company_name']) ? $companyDetails[0]['company_name'] : '';
	$location 			= isset($companyDetails[0]['location']) ? $companyDetails[0]['location'] : '';
	$branchName			= isset($modelOutput['branch_name']) ? $modelOutput['branch_name'] : '';


	$companyLogo ='<img src="'.$companyLogoImg.'" height="70">';

	$companyInfo = '<table>
	<tr>
		<td class="semi-strong" style="padding-top:20px">'.$branchName.'</td>
	</tr>
	<tr>
		<td class="semi-strong" style="padding-top:20px">'.$companyName.'</td>
	</tr>
	<tr>
		<td>'.$location.'</td>
	</tr>
	</table>';

	$vendorBpName	= isset($modelOutput['vendor_bp_name']) ? $modelOutput['vendor_bp_name'] : '';
	$vendorBpCode	= isset($modelOutput['vendor_bp_code']) ? $modelOutput['vendor_bp_code'] : '';
	$vehicleCode    = isset($modelOutput['vehicle_code']) ? $modelOutput['vehicle_code'] : '';

	$vendorInfo = '	<td>
	<div class="ash-color">'.$vendorBpName.'('.$vendorBpCode.') / '.$vehicleCode.'</div>
	</td>';

	$fileHeadingName 	= $invoiceProcessData['fileHeadingName'];
	$footerLabelHeading = strtolower($invoiceProcessData['fileHeadingName']);
	
	$documentNumber 	= isset($modelOutput['document_number']) ? $modelOutput['document_number'] : '';
	$documentDate 		= isset($modelOutput['document_date']) ? $modelOutput['document_date'] : '';
	$referenceNo 		= isset($modelOutput['reference_number']) ? $modelOutput['reference_number'] : '';
	$deliveryDate 		= isset($modelOutput['delivery_date']) ? $modelOutput['delivery_date'] : '';
	$manualInvoiceNumber = isset($modelOutput['manual_invoice_number']) ? $modelOutput['manual_invoice_number'] : '';
	$remarks = isset($modelOutput['remarks']) ? $modelOutput['remarks'] : '';

	$totalPages = 1;
	$pageNo = 1;

	$invoices = "";
	$invoicesArray = array();
	if(isset($modelOutput['transport_invoice_status'])){

		$invoiceStatusArr = explode(",",$modelOutput['transport_invoice_status']);
		$invoiceDocArr = explode(",",$modelOutput['invoices']);

		foreach($invoiceStatusArr as $ivsKey => $ivsVal){
			$invoicesArray[] = $invoiceDocArr[$ivsKey].'-'.$ivsVal;
		}
	}
	$invoiceSummaryHtml = "";
	$invoices = implode(', ',$invoicesArray);
	
	// CHECK MANUAL INVOICE EXISTS 
	if($modelOutput['invoice_type']==2){ // Manual 
		$invoices = $manualInvoiceNumber;
	}
	
	// Page Manipulation Logic.
	$printingData = "";
	for ($page = 1; $page <= $totalPages; $page++) {

		$getContentData = file_get_contents(GATEPASS_BARCODE_TEMPLATE_FILE);

		// Show only to Last Page. 
		$summaryHtmlData = "";
		if ($page == $totalPages) {
			$summaryHtmlData = $invoiceSummaryHtml;
			$pageBreakContent = "";
		}

		// Display Data Tags. 
		$findStrings = array(
			"<<#COMPANY_LOGO>>",
			"<<#COMPANY_INFO>>",
			"<<#DOCUMENT_NUMBER>>", 
			"<<#DOCUMENT_DATE>>",
			"<<#PAGE_NO>>",
			"<<#TOTAL_PAGES>>",
			"<<#VENDOR_INFO>>",
			"<<#REFERENCE_NO>>",
			"<<#DELIVERY_DATE>>",
			"<<#BARCODE_HTML>>",
			"<<#INVOICES>>",
			"<<#REMARKS>>"
		);

		// Replacing data for above Tags.
		$replaceStrings  = array(
			$companyLogo,
			$companyInfo,
			$documentNumber,
			$documentDate,
			$pageNo,
			$totalPages,
			$vendorInfo,
			$referenceNo,
			$deliveryDate,
			$barcodeHtml,
			$invoices,
			$remarks
		);

		$printingData .= str_replace($findStrings, $replaceStrings, $getContentData);
		$printingData .= $pageBreakContent;
	}

	$fileGenerationPath = GATEPASS_BARCODE_GENERATION_PATH;
	
	$fileNameVal		= $invoiceProcessData['fileName'];
	$fileNameVal = str_replace(" ","_",strtolower($fileNameVal));

	// Making file name for HTML.
	$getHtmlFile 		= time() . '_'.$fileNameVal.'.html';
	// $getHtmlFile 		= $fileNameVal.'.html'; // For temp Check
	$fileLocation		= $fileGenerationPath . $getHtmlFile;
	

	if (!@file_put_contents($fileLocation, $printingData)) {
		$outputData['message']   = lang('MSG_145');
	} else {
		
		$time = time();
		// Making download file name for PDF.
		$fileName 	  = $time . "_$fileNameVal.pdf";
		// $fileName 	  = $time . "_$fileNameVal.html";  // For temp Check
		// $fileName 	  = "$fileNameVal.html";  // For temp Check

		shell_exec('wkhtmltopdf ' . $fileLocation . " " . $fileGenerationPath . $fileName);
		$fileDetails 	= $fileGenerationPath . $fileName;
		$invoiceFile 		= getFullImgUrl('transport', $fileName);
		$outputData['url']  = $invoiceFile;
	}

	// print_r($outputData);exit;
	return $outputData;
	
}


/**
 * @METHOD NAME 	: bindConfigTableValues()
 *
 * @DESC 			: TO BIND THE TABLE CONFIGURATION VALUES 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function bindConfigTableValues($configTableName, $operation, $getPostData, $subArrayList = array())
{
	$CI  			= &get_instance();
	
	// LOAD CRUD TABLE DETAILS - 1
	$tableName = constant($configTableName);
	$CI->config->load('table_config/'.$tableName);
	

	$tableConfig 	= $CI->config->item($configTableName)['columns_list'];
	$operationStr 	= strtolower($operation) . "_flag";
	$rowData 		= array();
	
	// CHECK WHETHER IT IS A DRAFT DOCUMENT 
	$isDraft = 0;
	if(isset($getPostData['isDraft'])){
		$isDraft = $getPostData['isDraft'];
	}
	
	// Appending sub array values - start.
	// $subArrayList = array('contactListArray','addressListArray');
    // $subArrayList = array(); // For temp check.

	foreach ($subArrayList as $skey => $sval) {

		$tempArr = array('field_key' => $sval,
						'field_type' => 'sub_array',
						'create_flag' => 1,
						'edit_flag' => 1,
						'update_flag' => 1,
						'field_validation' => array(
								'is_mandatory' => 1
								)
					);

		array_push($tableConfig, $tempArr);
	}

	// Appending sub array values - end.
	
	if(is_array($tableConfig) && count($tableConfig)>0){
	
		foreach ($tableConfig as $tKey => $tValue) {
			if ($tValue[$operationStr] == 1) {

				if ($operation == 'EDIT') {

					if ($getPostData != "") {

						$rowData[] = $tValue['tbl_field_name'];
					} else {
						echo json_encode(array(
							'status' => 'FAILURE',
							'message' => 'Request Param (id) Missing!',
							"responseCode" => 200
						));
						exit();
					}
				} else {
					if (isset($getPostData[$tValue['field_key']])) {

						// TO CHECK VALIDATION
						checkValidation($tValue, $getPostData[$tValue['field_key']],$isDraft);
						
						if($tValue['field_type']!='sub_array'){
							$rowData[$tValue['tbl_field_name']] = $getPostData[$tValue['field_key']];
						}

					} else {

						if($tValue['field_key'] == "sapId" || $tValue['field_key'] == "postingStatus" ||  $tValue['field_key'] == "sapError"){
                            // Skip for above keys.
						} else {

							echo json_encode(array(
								'status' => 'FAILURE',
								'message' => 'Request Param (' . $tValue['field_key'] . ') Missing!',
								"responseCode" => 200
							));
							exit();
						}
			
					}
				}
			}

			$rowData = processDataForDB($tValue, $getPostData, $rowData);

		}

		//print_r($rowData);
		//exit;
		return $rowData;
	}else{
		echo json_encode(array(
			'status' 		=> 'FAILURE',
			'message'		=> 'Table Configuration Issue. Please contact admin !',
			"responseCode"	=> 200
		));
		exit();
	}	
}


/**
 * @METHOD NAME 	: bindConfigDbErrorMsg()
 *
 * @DESC 			: TO BIND THE MYSQL ERROR MESSAGES 
 * @RETURN VALUE 	: 
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function bindConfigDbErrorMsg($error)
{
	if($error['code'] == "1452"){
		$mysqlErrorMessage = "Mysql - Database Insert Error due to ForeignKey Key Constraints, Please contact your system administrator !";
	}
	else{
        $mysqlErrorMessage = "Mysql - Database Insert Error, Please contact your system administrator !!";
	}

	echo json_encode(array(
		'status' 		=> 'FAILURE',
		'message'		=> $mysqlErrorMessage,
		"responseCode"	=> 200
	));
	exit();
		
}

/**
 * @METHOD NAME 	: checkValidation()
 *
 * @DESC 			: TO CHECK VALIDATION FOR REQUEST VALUES 
 * @RETURN VALUE 	: -
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkValidation($tColumn, $tColumnValue,$isDraft)
{
	if (isset($tColumn['field_validation'])) {

		// For Required Field Validation -> MANDATORY CONDITION
		if (
			isset($tColumn['field_validation']['is_mandatory'])  &&
			($tColumn['field_validation']['is_mandatory'] == 1 ) && 
			 ($isDraft ==0)
		) {
			// ADD DIFF VALIDATION FOR NUMBER AND ALPHA 
			$fieldType = $tColumn['field_type'];
			
			// ALPHA TESTING 
			if($fieldType == 'alpha' && empty($tColumnValue) ){
				echo json_encode(array(
					'status' => 'FAILURE',
					'message' => 'Value for ' . $tColumn['field_key'] . ' is Mandatory!',
					"responseCode" => 200
				));
				exit();	
			}	
			
			// NUMBER TESTING 
			if($fieldType == 'number' && ($tColumnValue=='') && ($tColumnValue==NULL) ){
				echo json_encode(array(
					'status' => 'FAILURE',
					'message' => 'Value for ' . $tColumn['field_key'] . ' is Mandatory!',
					"responseCode" => 200
				));
				exit();	
			}

			// FOR SUB ARR - OTHER
			if($fieldType == 'sub_array' && !isset($tColumnValue)){
				echo json_encode(array(
					'status' => 'FAILURE',
					'message' => 'Value for ' . $tColumn['field_key'] . ' is Mandatory!',
					"responseCode" => 200
				));
				exit();	
			}
		}

		// For Numeric Validation.
		if (
			isset($tColumn['field_validation']['is_numeric']) &&
			$tColumn['field_validation']['is_numeric'] == 1 &&
			!empty($tColumnValue) &&
			!is_numeric($tColumnValue)
		) {

			echo json_encode(array(
				'status' => 'FAILURE',
				'message' => $tColumn['field_key'] . '- Should be a Numeric Value',
				"responseCode" => 200
			));
			exit();
		}

		// For Date & Format Validation.
		if (
			isset($tColumn['field_validation']['is_date']) &&
			$tColumn['field_validation']['is_date'] == 1 &&
			!empty($tColumnValue)
		) {

			$d = DateTime::createFromFormat('Y-m-d', $tColumnValue);
			// The Y ( 4 digits year )
			if ($d && $d->format('Y-m-d') === $tColumnValue) {
			} else {
				echo json_encode(array(
					'status' => 'FAILURE',
					'message' => $tColumn['field_key'] . '- Should be a valid date format!',
					"responseCode" => 200
				));
				exit();
			}
		}
	}
}


/**
 * @METHOD NAME 	: processDataForDB()
 *
 * @DESC 			: TO ALTER REQUEST PARAMS FOR DB PURPOSE. 
 * @RETURN VALUE 	: -
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function processDataForDB($tColumn, $postData, $rowData)
{
	return $rowData;
	
	if(isset($postData[$tColumn['field_key']])){

		$tColumnValue = $postData[$tColumn['field_key']];
		
		$fieldType = $tColumn['field_type'];

		// For Alphanumeric
		if($fieldType == 'alpha' && empty($tColumnValue) ){
			unset($rowData[$tColumn['field_key']]);
		}	

		// For Numeric only
		if($fieldType == 'number' && ($tColumnValue=='' || $tColumnValue==NULL) ){
			unset($rowData[$tColumn['tbl_field_name']]);
		}
	}

	return $rowData;
}


/**
 * @METHOD NAME 	: getAutoSuggestionListHelper()
 *
 * @DESC 			: TO GET THE AUTO-SUGGESTION INFORMATION OF THE TABLE 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getAutoSuggestionListHelper($getData, $getAllDataFlag = 0, $isAutoSuggestion = 0)
{
	
	// PASS GLOBAL DATA 
	$passSearchData['category'] = 2;
	$passSearchData['delFlag']  = 0;

	// FRAME INFO DETAILS 
	$frameInfoDetails		= array();
	$CI 					= &get_instance();

	foreach ($getData as $getKey => $getValue) {

		$splitKeyValues  = explode("~", $getKey);
		$infoKeyDetails  = "";
		$defaultFlag	 = 0;

		$fieldKey = $splitKeyValues[0];
		if (isset($splitKeyValues[1])) {
			$infoKeyDetails  = $splitKeyValues[1];
		}

		// FRAME SERVICE LIST DATA
		$fieldId['id']		   = $getValue;
		$getServicesList 	   = array_merge($passSearchData, $fieldId);

		$categoryType = 2;
		if($isAutoSuggestion == 1){
			$getServicesList = array();
			$getServicesList = $getValue;
			$categoryType = 1;
		}
		
		if($getAllDataFlag==1){ // FOR GETTING ALL THE DATA 
			$getServicesList = array();
			$getServicesList['category'] = 3;
		}
		
		//echo "Field key is ".$fieldKey;

		switch ($fieldKey) {
			case "getBusinessPartnerList":
				$result 			=  $CI->commonModel->getBusinessPartnerAutoList($getServicesList);
				//$frameInfoDetails['businessPartnerInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['businessPartnerInfo'] = $result;
				}
				break;

			case "getBusinessPartnerContactsList": // MUST PASS BUSINESS PARTNER ID 
				$result =  $CI->commonModel->getBusinessPartnerContactsAutoList($getServicesList);
				//$frameInfoDetails['bpContactsInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['bpContactsInfo'] = $result;
				}
				break;

			case "getBusinessPartnerAddressList": // MUST PASS BUSINESS PARTNER ID & ADDRESS TYPE ID 
				$result =  $CI->commonModel->getBusinessPartnerAddressAutoList($getServicesList);
				//$frameInfoDetails['bpAddressInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['bpAddressInfo'] = $result;
				}
				break;

			case "getEmployeeList":
				$result =  $CI->commonModel->getEmployeeAutoList($getServicesList); //employeeInfo
				//$frameInfoDetails['employeeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['employeeInfo'] = $result;
				}
				break;
			
			
			case "getAccessControlNameList":
				$result =  $CI->commonModel->getAccessControlNameAutoList($getServicesList); //employeeInfo
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['employeeInfo'] = $result;
				}
			break;
			
			
			
			case "getCreatedByDetails":
					$result =  $CI->commonModel->getCreatedByDetails($getServicesList); //employeeInfo
					//$frameInfoDetails['employeeInfo'] = $result;
					if(!empty($infoKeyDetails)){
						$frameInfoDetails[$infoKeyDetails] = $result; 
					}else{ 
						$frameInfoDetails['createdByInfo'] = $result;
					}
				break;
			case "getTeamHeadList":
				$result =  $CI->commonModel->getTeamHeadAutoList($getServicesList);
				//$frameInfoDetails['teamHeadInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['teamHeadInfo'] = $result;
				}
				break;

			case "getTeamNameList":
				$result =  $CI->commonModel->getTeamNameAutoList($getServicesList);
				//$frameInfoDetails['teamNameInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['teamNameInfo'] = $result;
				}
				break;

			case "getIndustryList":
				$result =  $CI->commonModel->getIndustryAutoList($getServicesList);
				//$frameInfoDetails['industryInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['industryInfo'] = $result;
				}
				break;

			case "getTerritoryList":
				$result =  $CI->commonModel->getTerritoryAutoList($getServicesList);
				//$frameInfoDetails['territoryInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['territoryInfo'] = $result;
				}
				break;
			
			case "getManufacturerList":
				$result =  $CI->commonModel->getManufacturerAutoList($getServicesList);
				//$frameInfoDetails['manufacturerInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['manufacturerInfo'] = $result;
				}
				break;
				
			case "getHsnList":
				$result =  $CI->commonModel->getHsnAutoList($getServicesList);
				//$frameInfoDetails['hsnInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['hsnInfo'] = $result;
				}
				break;
				
			case "getDimensionList":
				$result =  $CI->commonModel->getDimensionAutoList($getServicesList);
				//$frameInfoDetails['dimensionInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['dimensionInfo'] = $result;
				}
				break;

			case "getInformationSourceList":
				$result =  $CI->commonModel->getInformationSourceAutoList($getServicesList);
				//$frameInfoDetails['infoSourceInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['infoSourceInfo'] = $result;
				}
				break;

			case "getPaymentMethodsList":
				$result =  $CI->commonModel->getPaymentMethodsAutoList($getServicesList);
				//$frameInfoDetails['paymentMethodInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['paymentMethodInfo'] = $result;
				}
				break;

			case "getPaymentTermsList":
				$result =  $CI->commonModel->getPaymentTermsAutoList($getServicesList);
				//$frameInfoDetails['paymentTermsInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['paymentTermsInfo'] = $result;
				}
				break;

			case "getStageList":
				$result =  $CI->commonModel->getStageAutoList($getServicesList);
				//$frameInfoDetails['stageInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['stageInfo'] = $result;
				}
				break;

			case "getActivityList":
				$result =  $CI->commonModel->getActivityAutoList($getServicesList);
				//$frameInfoDetails['activityInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityInfo'] = $result;
				}
				break;
			

			case "getCountryList":
				$result =  $CI->commonModel->getCountryAutoList($getServicesList);
				//$frameInfoDetails['countryInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['countryInfo'] = $result;
				}
				break;

			case "getStateList":
				$result =  $CI->commonModel->getStateAutoList($getServicesList);
				//$frameInfoDetails['stateInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['stateInfo'] = $result;
				}
				break;
			
			case "getCompetitorList":
				// THREAD  TYPE STATUS LIST
				//$passType['type'] 	= 'THREAD_LEVEL';
				// $threadLevelList   	= $CI->commonModel->getMasterStaticDataAutoList($passType, $categoryType);
				$getServicesList['type'] 	= 'THREAD_LEVEL';
				$threadLevelList   	= $CI->commonModel->getThreadAutoList();
				$result 			=  $CI->commonModel->getCompetitorAutoList($getServicesList);
				
				if(is_array($result) && count($result)>0){
				
					//printr($threadLevelList);
					//printr($result);
				
					foreach ($result as $resultKey => $resultLevel) {
						$threadLevelId		= array_search($resultLevel['threat_level_id'], array_column($threadLevelList, 'id'));
						$threadLevelName = $threadLevelList[$threadLevelId]['name'];
						$result[$resultKey]['threat_level_name'] = $threadLevelName;
					}
				}
				//$frameInfoDetails['competitorInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['competitorInfo'] = $result;
				}
				break;

			case "getReasonList":
				$result =  $CI->commonModel->getReasonAutoList($getServicesList);
				//$frameInfoDetails['reasonInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['reasonInfo'] = $result;
				}
				break;

			case "getUomList":
				$result =  $CI->commonModel->getUomAutoList($getServicesList);
				//$frameInfoDetails['uomInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['uomInfo'] = $result;
				}
				break;

			case "getDesignationList":
				$result =  $CI->commonModel->getDesignationAutoList($getServicesList);
				//$frameInfoDetails['uomInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['designaitonInfo'] = $result;
				}
				break;
	
			case "getItemList":
				$result =  $CI->commonModel->getItemAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['itemInfo'] = $result;
				}
				break;
			
			case "getRentalItemList":
				$result =  $CI->commonModel->getRentalItemAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails]  = $result; 
				}else{ 
					$frameInfoDetails['rentalItemInfo'] = $result;
				}
				break;
			
			case "getRentalEquipmentList":
				$result =  $CI->commonModel->getRentalEquipmentAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails]  = $result; 
				}else{ 
					$frameInfoDetails['rentalEquipmentInfo'] = $result;
				}
				break;
			
			case "getRentalEquipmentStatusList":
				$result =  $CI->commonModel->getRentalEquipmentStatusAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalEquipmentStatusInfo'] = $result;
				}
				break;
			
			case "getRentalEquipmentCategoryList":
				$result =  $CI->commonModel->getRentalEquipmentCategoryAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalEquipmentCategoryInfo'] = $result;
				}
				break;
				
			case "getItemGroupList":
				$result =  $CI->commonModel->getItemGroupAutoList($getServicesList); //itemGroupInfo
				//$frameInfoDetails['itemGroupInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['itemGroupInfo'] = $result;
				}
				break;

			case "getMasterActivityList":
				$result =  $CI->commonModel->getMasterActivityAutoList($getServicesList);
				//$frameInfoDetails['activityTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityTypeInfo'] = $result;
				}
				break;

			case "getLevelofInterestList":
				$result =  $CI->commonModel->getLevelofInterestAutoList($getServicesList);
				//$frameInfoDetails['levelofInterestInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['levelofInterestInfo'] = $result;
				}
				break;

			case "getPriorityList":
				$result =  $CI->commonModel->getPriorityAutoList($getServicesList);
				//$frameInfoDetails['priorityInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['priorityInfo'] = $result;
				}
				break;

			case "getCurrencyList":
				$result =  $CI->commonModel->getCurrencyAutoList($getServicesList);
				//$frameInfoDetails['currencyInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['currencyInfo'] = $result;
				}
				break;

			case "getTaxList":
				$result =  $CI->commonModel->getTaxAutoList($getServicesList);
				//$frameInfoDetails['taxInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['taxInfo'] = $result;
				}
				break;

			case "getBranchList": // Need to analyze 
				$result =  $CI->commonModel->getBranchAutoList($getServicesList);
				//$frameInfoDetails['branchInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['branchInfo'] = $result;
				}
				break;

			case "getTaxAttributeList":
				$result =  $CI->commonModel->getTaxAttributeAutoList($getServicesList);
				//$frameInfoDetails['taxAttributeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['taxAttributeInfo'] = $result;
				}
				break;

			case "getReportingManagerList":
				$result =  $CI->commonModel->getReportingManagerList($getServicesList);
				//$frameInfoDetails['reportingManagerInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['reportingManagerInfo'] = $result;
				}
				break;

			case "getTeamList":
				$result = $CI->commonModel->getTeamAutoList($getServicesList);
				//$frameInfoDetails['teamInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['teamInfo'] = $result;
				}
				break;

			case "getOpportunityTypeList":
				$result =  $CI->commonModel->getOpportunityTypeAutoList($getServicesList);
				//$frameInfoDetails['opportunityTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['opportunityTypeInfo'] = $result;
				}
				break;

			case "getDistributionRulesList": //  Need to analyze array of information
				$result =  $CI->commonModel->getdistributionRulesAutoList($getServicesList);
				//$frameInfoDetails['distributionRulesInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['distributionRulesInfo'] = $result;
				}
				break;

			case "getLocationList":
				$result =  $CI->commonModel->getLocationAutoList($getServicesList);
				//$frameInfoDetails['locationInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['locationInfo'] = $result;
				}
				break;

			case "getWarehouseList": 
				$result =  $CI->commonModel->getWarehouseAutoList($getServicesList);
				//$frameInfoDetails['warehouseInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['warehouseInfo'] = $result;
				}
				break;

			case "getBinList": // ARRAY OF INFORMATION 
				$result =  $CI->commonModel->getBinAutoList($getServicesList);
				//$frameInfoDetails['binInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['binInfo'] = $result;
				}
				break;
			
			case "getIssuingNoteList":
				$result =  $CI->commonModel->getIssuingNoteAutoList($getServicesList);
			//	$frameInfoDetails['issuingNoteInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['issuingNoteInfo'] = $result;
				}
				break;
			
			case "getPriceList":
				$result =  $CI->commonModel->getPriceListAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['priceListInfo'] = $result;
				}
				break;
			
			case "getDocumentNumberingList":
				$result =  $CI->commonModel->getDocumentNumberingAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['documentNumberingInfo'] = $result;
				}
				break;
			
			case "getTermsandConditionsList":
				$result =  $CI->commonModel->getTermsAndConditionAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['termsandConditionsInfo'] = $result;
				}
				break;
			
			case "getRentalWorklogList":
				$result =  $CI->commonModel->getRentalWorklogAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalWorklogInfo'] = $result;
				}
				break;
			
			
			case "getInspectionTemplateList":
				$result =  $CI->commonModel->getInspectionTemplateAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['inspectionTemplateInfo'] = $result;
				}
				break;

			case "getApprovalStagesList":
				$result =  $CI->commonModel->getApprovalStagesAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['approvalStagesInfo'] = $result;
				}
				break;
				
			case "getVehicleList":
				$result =  $CI->commonModel->getVehicleAutoList($getServicesList);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['approvalStagesInfo'] = $result;
				}
				break;
				
			
			case "getDocumentTypeList":
				$passDocumentTypeData['document_type_id'] = '!=0'; 
				$result =  $CI->commonModel->getModuleScreenMappingDetails($passDocumentTypeData);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['documentTypeInfo'] = $result;
				}
				break;
				
			
			/*
			case "getWarehouseBinList":
				$result =  $CI->commonModel->getWarehouseBinListAutoList($getServicesList);
				$frameInfoDetails['warehouseBinInfo'] = $result;
				break;
			*/	

			//--- MASTER STATIC DATA INFORMATION 
			case "getThreatLevelList": // Not used for edit ->
				$getServicesList['type'] = 'THREAD_LEVEL';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['threadLevelInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['threadLevelInfo'] = $result;
				}
				break;

			case "getApprovalStatusList": // Not used for edit ->
				$getServicesList['type'] = 'APPROVAL_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['threadLevelInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['ApprovalStatuslInfo'] = $result;
				}
				break;

			case "getCommonStatusList":
				$getServicesList['type'] = 'COMMON_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['statusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['statusInfo'] = $result;
				}
				break;

			case "getBusinessPartnerTypeList":
				$getServicesList['type'] = 'BUSINESS_PARTNER_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['partnerTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['partnerTypeInfo'] = $result;
				}
				break;

			case "getBusinessPartnerAddressTypeList":
				$getServicesList['type'] = 'BUSINESS_PARTNER_ADDRESS_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['partnerAddressInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['partnerAddressInfo'] = $result;
				}
				break;

			case "getBusinessPartnerStatusList":
				$getServicesList['type'] = 'BUSINESS_PARTNER_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['partnerStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['partnerStatusInfo'] = $result;
				}
				break;

			case "getActivityStatusList": // USED NO WHERE
				$getServicesList['type'] = 'ACTIVITY_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['activityStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityStatusInfo'] = $result;
				}
				break;

			case "getTransportStatusList": // Not used for edit ->
				$getServicesList['type'] = 'TRANSPORT_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['threadLevelInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['transportStatusInfo'] = $result;
				}
				break;
			

			case "getEmployeeTypeList":
				$getServicesList['type'] = 'EMPLOYEE_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['designationInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['employeeTypeInfo'] = $result;
				}
				break;

			case "getActivityRecurrenceTypeList":
				$getServicesList['type'] = 'ACTIVITY_RECURRENCE_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['activityRecurrenceInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityRecurrenceInfo'] = $result;
				}
				break;

			case "getActivityPriorityTypeList":
				$getServicesList['type'] = 'ACTIVITY_PRIORITY_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['activityPrioirtyInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityPrioirtyInfo'] = $result;
				}
				break;

			case "getActivityReminderTypeList":
				$getServicesList['type'] = 'ACTIVITY_REMINDER_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['activityReminderTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['activityReminderTypeInfo'] = $result;
				}
				break;

			case "getOpportunityStatusList":
				$getServicesList['type'] = 'OPPORTUNITY_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['opportunityStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['opportunityStatusInfo'] = $result;
				}
				break;
		
			case "getBusinessPartnerContactTypeList":
				$getServicesList['type'] = 'BP_CONTACT_TYPE';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['contactTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['contactTypeInfo'] = $result;
				}
				break;

			case "getPurchaseTransStatusList":
				$getServicesList['type'] = 'PURCHASE_TRANS_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['purchaseStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['purchaseStatusInfo'] = $result;
				}
				break;

			case "getSalesTransStatusList":
				$getServicesList['type'] = 'SALES_TRANS_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['salesStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['salesStatusInfo'] = $result;
				}
				break;
			
			case "getRentalTransStatusList":
				$getServicesList['type'] = 'RENTAL_TRANS_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalStatusInfo'] = $result;
				}
				break;

			case "getInventoryTransStatusList":
				$getServicesList['type'] = 'INVENTORY_TRANS_STATUS';
				$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
				//$frameInfoDetails['inventoryStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['inventoryStatusInfo'] = $result;
				}
				break;
			
			case "getDutyStatusList":
				$getServicesList['type']	= 'DUTY_STATUS'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				//$frameInfoDetails['dutyStatusInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['dutyStatusInfo'] = $result;
				}
			break;
			
			case "getItemTransactionTypeList":
				$getServicesList['type']	= 'ITEM_TRANSACTION_TYPE'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				//$frameInfoDetails['itemTransactionTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['itemTransactionTypeInfo'] = $result;
				}
				break;
			
			case "getWorklogItemTypeList":
				$getServicesList['type']	= 'WORKLOG_ITEM_TYPE'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				//$frameInfoDetails['itemTransactionTypeInfo'] = $result;
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['itemWorklogTypeInfo'] = $result;
				}
				break;
			
			case "getEquipmentOwnershipList":
				$getServicesList['type']	= 'EQUIPMENT_OWNERSHIP'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['equipmentOwnershipInfo'] = $result;
				}
				break;
			
			case "getRentalStatusList":
				$getServicesList['type']	= 'RENTAL_STATUS'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalStatusInfo'] = $result;
				}
				break;
				
		
		case "getMeterReadingList":
				$getServicesList['type']	= 'METER_READING'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['meterReadingInfo'] = $result;
				}
				break;
		
					
		case "getRentalMaintenancePriorityList":
				$getServicesList['type']	= 'RENTAL_MAINTENANCE_PRIORITY'; 
				$result =  $CI->commonModel->getMasterStaticDataAutoList($getServicesList, $categoryType);
				if(!empty($infoKeyDetails)){
					$frameInfoDetails[$infoKeyDetails] = $result; 
				}else{ 
					$frameInfoDetails['rentalMaintenancePriorityInfo'] = $result;
				}
				break;
			
		case "getTransportInvoiceStatusList":
			$getServicesList['type'] = 'TRANSPORT_INVOICE_STATUS';
			$result =  $CI->commonModel->getMasterStaticDataAutoList( $getServicesList, $categoryType);
			if(!empty($infoKeyDetails)){
				$frameInfoDetails[$infoKeyDetails] = $result; 
			}else{ 
				$frameInfoDetails['rentalStatusInfo'] = $result;
			}
			break;
			default: {
					$defaultFlag = 1;
					echo "Something went wrong in helper. Field key not found.".$fieldKey; // Dont remove the functionality removing affect unset functionality 
					exit;
				}
				// END OF STATIC INFORMATION
				/**/
		}
		
		/*
		// UNSET PREV ARRAY AND APPEND THE NEW ARRAY // -VE SCENARIO ISSUE COMMENTED 
		if (!empty($infoKeyDetails) && $defaultFlag == 0) {
			// REMOVE LAST SET ARRAY FOR MANIPULATION 
			end($frameInfoDetails); // move to last array 
			$lastKey 	 = key($frameInfoDetails);
			//echo "Last key is ".$lastKey;
			unset($frameInfoDetails[$lastKey]);
			// 
			$frameInfoDetails[$infoKeyDetails] = $result;
		}
		*/
	}
	//printr($frameInfoDetails);	exit;
	return $frameInfoDetails;
}


/**
 * @METHOD NAME 	: sendDocumentMail()
 *
 * @DESC 			: TO SEND DOWNLOADED DOCUMENT SEND PUROSE MAIL.
 *                    It Sends Mail with document attached while respective docs download.
 *                    From here, It exposed in all transaction controllers, then those methods 
 *                    defines as common URL in routes.php and exposed as servcie outer.
 *                    
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT	:
 *
 **/
function sendDocumentMail($mailHelperData)
{
		$CI = &get_instance();
		$CI->load->library('customemail');
		$mailerInfo  	= getMailerInfo();
		$loggedInUserInfo = getLoggedInUserInfo();


		// TEMPALTE FILE MANIPULATION
		$templateArray 		=  array(
			'USER_LANGUAGE' => $CI->currentUserLanguage,
		);

		$mailerTemplateData = getMailerTemplate($templateArray);
		$mailerData = $mailerTemplateData;

		
		// OVER ALL TEMPLATE MANIPULATION
		// $mailContent = "<br>Invoice Document has been attached with this mail, please check and contact administrator for further clarifications if required.";
		$mailContent = $mailHelperData['message'];
		$mailerData = str_replace("<<MAIL_TITLE>>","Invoice Document Mail",$mailerData);
		$mailerData = str_replace("<<BODY_CONTENT>>",$mailContent,$mailerData);
		$mailerData = str_replace("<<MAILIMGURL>>",MAILIMGURL,$mailerData);
		
		$mail_cc = null;
		if(isset($loggedInUserInfo['email_id']) 
		&& $loggedInUserInfo['email_id'] != ""){
			$mail_cc = $loggedInUserInfo['email_id'];
		}

		if(isset($mailHelperData['email_cc']) 
		&& $mailHelperData['email_cc'] != null){
			$mail_cc = $mailHelperData['email_cc'];
		}

		if($CI->customemail->sendemail_external(
			$mailHelperData['email_id'],
			$mailHelperData['subject'],
			$mailerData,
			$mailerInfo,
			$mailHelperData['email_bcc'],
			$mail_cc,
			$mailHelperData['documentDetails']
		)){
			$fileDetails = explode(".",$mailHelperData['documentDetails']['filePath']);
			
			if(file_exists($fileDetails[0].".pdf")){
				unlink($fileDetails[0].".pdf");
			}

			if(file_exists($fileDetails[0].".html")){
				unlink($fileDetails[0].".html");
			}
		}
}


/**
 * @METHOD NAME 	: sendMail()
 *
 * @DESC 			: TO Send General Purpose Mail.
 *                    Its for independent Mail-Service exposed in Common_services controller.
 *                    From there exposed as Mail service outer.
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT	:
 *
 **/
function sendMail($mailHelperData)
{
		$CI = &get_instance();
		$CI->load->library('customemail');
		$mailerInfo  = getMailerInfo();
		$loggedInUserInfo = getLoggedInUserInfo();

		// TEMPALTE FILE MANIPULATION
		$templateArray 		=  array(
			'USER_LANGUAGE' => $CI->currentUserLanguage,
		);

		$mailerTemplateData = getMailerTemplate($templateArray);
		$mailerData = $mailerTemplateData;

		$mailTitle = "";
		if(isset($mailHelperData['title']) && $mailHelperData['title'] != ""){
			$mailTitle = $mailHelperData['title'];
		}

		// OVER ALL TEMPLATE MANIPULATION
		$mailContent = $mailHelperData['email_body'];
		$mailerData = str_replace("<<MAIL_TITLE>>",$mailTitle,$mailerData);
		$mailerData = str_replace("<<BODY_CONTENT>>",$mailContent,$mailerData);
		$mailerData = str_replace("<<MAILIMGURL>>",MAILIMGURL,$mailerData);
		
		$mail_cc = null;
		if(isset($loggedInUserInfo['email_id']) 
		&& $loggedInUserInfo['email_id'] != ""){
			$mail_cc = $loggedInUserInfo['email_id'];
		}

		if(isset($mailHelperData['email_cc']) 
		&& $mailHelperData['email_cc'] != null){
			$mail_cc = $mailHelperData['email_cc'];
		}

		$CI->customemail->sendemail_external(
			$mailHelperData['email_id'],
			$mailHelperData['subject'],
			$mailerData,
			$mailerInfo,
			$mailHelperData['email_bcc'],
			$mail_cc,
			$mailHelperData['documentDetails']
		);
}


// /**
//  * @METHOD NAME 	: sendCronNotificationMail()
//  *
//  * @DESC 			: TO Send General Purpose Mail.
//  *                    Its for independent Mail-Service exposed in Mail_notifiction controller.
//  *                    From there exposed as Mail service in CLI for CRONJOB.
//  * @RETURN VALUE 	:
//  * @PARAMETER 		: --
//  * @ACCESS POINT	:
//  *
//  **/
// function sendCronNotificationMail($mailHelperData)
// {
// 		$CI = &get_instance();

// 		$CI->load->library('customemail');
// 		$mailerInfo  = getMailerInfo();
// 		$loggedInUserInfo = getLoggedInUserInfo();

// 		// TEMPALTE FILE MANIPULATION
// 		$templateArray 		=  array(
// 			'USER_LANGUAGE' => $CI->currentUserLanguage,
// 		);

// 		$mailerTemplateData = getMailerTemplate($templateArray);
// 		$mailerData = $mailerTemplateData;
		
// 		// OVER ALL TEMPLATE MANIPULATION
// 		$mailContent = $mailHelperData['email_body'];
// 		$mailerData = str_replace("<<MAIL_TITLE>>",$mailHelperData['title'],$mailerData);
// 		$mailerData = str_replace("<<BODY_CONTENT>>",$mailContent,$mailerData);
// 		$mailerData = str_replace("<<MAILIMGURL>>",MAILIMGURL,$mailerData);
		
// 		$mail_cc = null;
// 		if(isset($loggedInUserInfo['email_id']) 
// 		&& $loggedInUserInfo['email_id'] != ""){
// 			$mail_cc = $loggedInUserInfo['email_id'];
// 		}

// 		if(isset($mailHelperData['email_cc']) 
// 		&& $mailHelperData['email_cc'] != null){
// 			$mail_cc = $mailHelperData['email_cc'];
// 		}

// 		$CI->customemail->sendemail_external(
// 			$mailHelperData['email_id'],
// 			$mailHelperData['subject'],
// 			$mailerData,
// 			$mailerInfo,
// 			$mailHelperData['email_bcc'],
// 			$mail_cc,
// 			$mailHelperData['documentDetails']
// 		);
// }



/**
 * @METHOD NAME 	: checkDuplicateDocumentNumber()
 *
 * @DESC 			: TO CHECK DOCUMENT DUPLICATE NUMBER IS EXIST OR NOT.
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT	:
 *
 **/
function checkDuplicateDocumentNumber($docNumFormatted, $tableName){

	$CI = &get_instance();

	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}

	// Checks for existing document number.
	$dbObject->select([
		'id',
		'document_number'
	])
	->from($tableName)
	->where('document_number', $docNumFormatted)
	->where($tableName . '.is_deleted', '0');
	$res = $dbObject->get();

	
	if ($res->num_rows() >= 1) {
         return 1; // True
	}
	else{
        return 0; // False
	}

}



/**
 * @METHOD NAME 	: checkAndUpdateNextNumber()
 *
 * @DESC 			: TO UPDATE AND CHECK NEXT DOCUMENT NUBMERING.
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT	:
 *
 **/
 /*
 // OLD FUNCTIONALITY COMMENTED FOR NO USE 
function processDocumentNumber($postData, $tableName)
{

	$CI = &get_instance();

	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}

	// Getting document_number from post data.
	$documentNUmberingId = $postData['document_numbering_id'];

	// For Type Manual.
	if (isset($postData['document_numbering_type']) && $postData['document_numbering_type'] == "MANUAL") {
		
		$returnInfo = [];
		$duplicateStatus = checkDuplicateDocumentNumber($postData['document_number'], $tableName);
		if($duplicateStatus == 1){
			$returnInfo['Status'] = 'FAILURE';
			$returnInfo['StatusNumber'] = 6;
		}
		else{
			$returnInfo['Status'] = 'SUCCESS';
			$returnInfo['documentNumber'] = $postData['document_number'];

		}
		// print_r($returnInfo);exit;
		return $returnInfo;

	} else {  // For Type Custom.

		$returnInfo = [];
		// Initially sets Status to default return.
		$returnInfo['Status'] = 'SUCCESS'; 

		// Get lastest document number info.
		$dbObject->select(['id','first_number','last_number',
		'continue_series','prefix','suffix','digits','next_number'
		])
		->from(MASTER_DOCUMENT_NUMBERING)
		->where('id', $documentNUmberingId)
		// ->where(MASTER_DOCUMENT_NUMBERING . '.continue_series', 1)
		->where(MASTER_DOCUMENT_NUMBERING . '.is_lock', '0')
		->where(MASTER_DOCUMENT_NUMBERING . '.is_deleted', '0');
		$res = $dbObject->get();

		$currentNumber = 0;
		$digits = 0;
		$prefix = "";
		$suffix = "";
		$lastNum = 0;

		if ($res->num_rows() >= 1) {
			$docNumInfo = $res->row_array();
			if($docNumInfo['continue_series'] == 0){
				$returnInfo['Status'] = 'FAILURE';
				$returnInfo['StatusNumber'] = 5;
				return $returnInfo;

			}
			else{
				$lastNum = $docNumInfo['last_number'];
				$prefix = $docNumInfo['prefix'];
				$suffix = $docNumInfo['suffix'];
				$digits = $docNumInfo['digits'];
			}

			// Assigning $nextNumber based on criteria.
			if ($docNumInfo['next_number'] != "" && $docNumInfo['next_number'] != 0) {
				$currentNumber = $docNumInfo['next_number'];
			} else {
				$currentNumber = $docNumInfo['first_number'];
			}
		}
		else{
			$returnInfo = [];
			$returnInfo['Status'] = 'FAILURE';
			$returnInfo['StatusNumber'] = 7;
			return $returnInfo;
		}

		$originalCurrentNumber = $currentNumber;

        // Recursive checks for duplicates.
		$DuplicateStatusCheck = 1;
		$continueSeries = 1;

		while ($DuplicateStatusCheck == 1) {
			
				// Formating next number as per Prefix and Suffix.
				$currentNumberFull = str_pad($currentNumber, $digits, '0', STR_PAD_LEFT);
				$documentNumberFormatted = $prefix . "" . $currentNumberFull . "" . $suffix;

				// echo $documentNumberFormatted;exit;
			    // Checks for duplicates.
				$duplicateStatus = checkDuplicateDocumentNumber($documentNumberFormatted, $tableName);

				if ($duplicateStatus == 1) {

					$currentNumber = $currentNumber + 1;
          
				} else {
					$returnInfo = [];
					$returnInfo['Status'] = 'SUCCESS';
					$returnInfo['documentNumber'] = $documentNumberFormatted;
					$DuplicateStatusCheck = 0;
				}

				// if($currentNumber >= $lastNum){
				// 	$continueSeries = 0; // Status = 0;
				// }

				if ($currentNumber > $lastNum) {
					$returnInfo = [];
					$returnInfo['Status'] = 'FAILURE';
					$returnInfo['StatusNumber'] = 5;
				}
			
		} 

		// echo $currentNumber;exit;
		// Update Increment nextnumber and continue_series for upcomming use.
		if($originalCurrentNumber != $currentNumber){
			$nextNumber = $currentNumber;
			$dbObject->update(MASTER_DOCUMENT_NUMBERING, ['next_number' => $nextNumber], ['id' => $documentNUmberingId]);
		}


		return $returnInfo;
	}

}
*/


function processDocumentNumber($postData, $tableName)
{

	$CI = &get_instance();

	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}
	

	// Getting document_number from post data.
	$documentNUmberingId = $postData['document_numbering_id'];

	// For Type Manual.
	if (isset($postData['document_numbering_type']) && $postData['document_numbering_type'] == "MANUAL") {
		
		$returnInfo = [];
		$duplicateStatus = checkDuplicateDocumentNumber($postData['document_number'], $tableName);
		if($duplicateStatus == 1){
	
			echo json_encode(array(
						'status' => 'FAILURE',
						'message' => lang('MSG_287')[0],
						"responseCode" => 200
					));
			exit();

		}
		else{
			$returnInfo['Status'] = 'SUCCESS';
			$returnInfo['documentNumber'] = $postData['document_number'];

		}
		return $returnInfo;

	} else {  // For Type Custom.

		$returnInfo = [];
		// Initially sets Status to default return.
		$returnInfo['Status'] = 'SUCCESS'; 

		// Get lastest document number info.
		$dbObject->select(['id','first_number','last_number',
		'continue_series','prefix','suffix','digits','next_number'
		])
		->from(MASTER_DOCUMENT_NUMBERING)
		->where('id', $documentNUmberingId)
		// ->where(MASTER_DOCUMENT_NUMBERING . '.continue_series', 1)
		->where(MASTER_DOCUMENT_NUMBERING . '.is_lock', '0')
		->where(MASTER_DOCUMENT_NUMBERING . '.is_deleted', '0');
		$res = $dbObject->get();

		$currentNumber = 0;
		$digits = 0;
		$prefix = "";
		$suffix = "";
		$lastNum = 0;

		if ($res->num_rows() >= 1) {
			$docNumInfo = $res->row_array();
			if($docNumInfo['continue_series'] == 0){
		
				echo json_encode(array(
					'status' => 'FAILURE',
					'message' => lang('MSG_286')[0],
					"responseCode" => 200
				));
				exit();

			}
			else{
				$lastNum = $docNumInfo['last_number'];
				$prefix = $docNumInfo['prefix'];
				$suffix = $docNumInfo['suffix'];
				$digits = $docNumInfo['digits'];
			}

			// Assigning $nextNumber based on criteria.
			if ($docNumInfo['next_number'] != "" && $docNumInfo['next_number'] != 0) {
				$currentNumber = $docNumInfo['next_number'];
			} else {
				$currentNumber = $docNumInfo['first_number'];
			}
		}
		else{
			echo json_encode(array(
				'status' => 'FAILURE',
				'message' => lang('MSG_291')[0],
				"responseCode" => 200
			));
			exit();
		}

		$originalCurrentNumber = $currentNumber;

        // Recursive checks for duplicates.
		$DuplicateStatusCheck = 1;
		$continueSeries = 1;

		while ($DuplicateStatusCheck == 1) {
			
				// Formating next number as per Prefix and Suffix.
				$currentNumberFull = str_pad($currentNumber, $digits, '0', STR_PAD_LEFT);
				$documentNumberFormatted = $prefix . "" . $currentNumberFull . "" . $suffix;

				// echo $documentNumberFormatted;exit;
			    // Checks for duplicates.
				$duplicateStatus = checkDuplicateDocumentNumber($documentNumberFormatted, $tableName);

				if ($duplicateStatus == 1) {

					$currentNumber = $currentNumber + 1;
          
				} else {
					$returnInfo = [];
					$returnInfo['Status'] = 'SUCCESS';
					$returnInfo['documentNumber'] = $documentNumberFormatted;
					$DuplicateStatusCheck = 0;
				}
		
				if ($currentNumber > $lastNum) {
		
					echo json_encode(array(
						'status' => 'FAILURE',
						'message' => lang('MSG_286')[0],
						"responseCode" => 200
					));
					exit();
				}
			
		} 

		// echo $currentNumber;exit;
		// Update Increment nextnumber and continue_series for upcomming use.
		if($originalCurrentNumber != $currentNumber){
			$nextNumber = $currentNumber;
			$dbObject->update(MASTER_DOCUMENT_NUMBERING, ['next_number' => $nextNumber], ['id' => $documentNUmberingId]);
		}


		return $returnInfo;
	}

}

/**
 * @METHOD NAME 	: updateNextNumber()
 *
 * @DESC 			: TO UPDATE AND CHECK NEXT DOCUMENT NUBMERING.
 * @RETURN VALUE 	:
 * @PARAMETER 		: --
 * @ACCESS POINT	:
 *
 **/
 // COMMENTED OLD FUNCTIONALITY
/*
function updateNextNumber($postData, $tableName)
{

	$CI = &get_instance();
	if (empty($dbObject)) {
		$dbObject = $CI->app_db;
	}

	// Getting document_number from post data.
	$documentNUmberingId = $postData['document_numbering_id'];


	$dbObject->select([
		'id', 'first_number', 'last_number',
		'continue_series', 'prefix', 'suffix', 'digits', 'next_number'
	])
		->from(MASTER_DOCUMENT_NUMBERING)
		->where('id', $documentNUmberingId)
		->where(MASTER_DOCUMENT_NUMBERING . '.continue_series', 1)
		->where(MASTER_DOCUMENT_NUMBERING . '.is_lock', '0')
		->where(MASTER_DOCUMENT_NUMBERING . '.is_deleted', '0');
	$res = $dbObject->get();


	$currentNumber = 0;
	$digits = 0;
	$prefix = "";
	$suffix = "";
	$lastNumber = 0;
	
	if ($res->num_rows() >= 1) {
		$docNumInfo = $res->row_array();
		$prefix = $docNumInfo['prefix'];
		$suffix = $docNumInfo['suffix'];
		$digits = $docNumInfo['digits'];
		$lastNumber = $docNumInfo['last_number'];
		$currentNumber = $docNumInfo['next_number'];
	}


	// Formating next number as per Prefix and Suffix.
	$currentNumberFull = str_pad($currentNumber, $digits, '0', STR_PAD_LEFT);
	$documentNumberFormatted = $prefix . "" . $currentNumberFull . "" . $suffix;

	

	if ($postData['document_number'] == $documentNumberFormatted) {
		// Checks for last number is reached or not.
		if($currentNumber == $lastNumber){
			 $continueSeriesVal = 0;
		}
		else{
			$continueSeriesVal = 1;
		}
		$nextNumber = $currentNumber + 1;
		$dbObject->update(MASTER_DOCUMENT_NUMBERING, ['next_number' => $nextNumber,'continue_series' => $continueSeriesVal], ['id' => $documentNUmberingId]);
		$returnInfo['Status'] = 'SUCCESS';
		// $returnInfo['StatusNumber'] = 7;
		
	} else {
		$returnInfo['StatusNumber1'] = $postData['document_number'];
		$returnInfo['StatusNumber2'] = $documentNumberFormatted;
		$returnInfo['Status'] = 'FAILURE';
		$returnInfo['StatusNumber'] = 7;
	}

	return $returnInfo;
}
*/



function updateNextNumber($postData, $documentNumberType)
{

	// If documentNumberingType is Manual.
	// No need to update, Just return with Success.
	if ($documentNumberType != 'MANUAL') {

		$CI = &get_instance();
		if (empty($dbObject)) {
			$dbObject = $CI->app_db;
		}

		// Getting document_number from post data.
		$documentNUmberingId = $postData['document_numbering_id'];


		$dbObject->select([
			'id', 'first_number', 'last_number',
			'continue_series', 'prefix', 'suffix', 'digits', 'next_number'
		])
		->from(MASTER_DOCUMENT_NUMBERING)
		->where('id', $documentNUmberingId)
		->where(MASTER_DOCUMENT_NUMBERING . '.continue_series', 1)
		->where(MASTER_DOCUMENT_NUMBERING . '.is_lock', '0')
		->where(MASTER_DOCUMENT_NUMBERING . '.is_deleted', '0');
		$res = $dbObject->get();


		$currentNumber = 0;
		$digits = 0;
		$prefix = "";
		$suffix = "";
		$lastNumber = 0;

		if ($res->num_rows() >= 1) {
			$docNumInfo = $res->row_array();
			$prefix = $docNumInfo['prefix'];
			$suffix = $docNumInfo['suffix'];
			$digits = $docNumInfo['digits'];
			$lastNumber = $docNumInfo['last_number'];
			$currentNumber = $docNumInfo['next_number'];
		}


		// Formating next number as per Prefix and Suffix.
		$currentNumberFull = str_pad($currentNumber, $digits, '0', STR_PAD_LEFT);
		$documentNumberFormatted = $prefix . "" . $currentNumberFull . "" . $suffix;



		if ($postData['document_number'] == $documentNumberFormatted) {
			// Checks for last number is reached or not.
			if ($currentNumber == $lastNumber) {
				$continueSeriesVal = 0;
			} else {
				$continueSeriesVal = 1;
			}
			$nextNumber = $currentNumber + 1;
			$dbObject->update(MASTER_DOCUMENT_NUMBERING, ['next_number' => $nextNumber, 'continue_series' => $continueSeriesVal], ['id' => $documentNUmberingId]);
			// $returnInfo['Status'] = 'SUCCESS';
			// $returnInfo['StatusNumber'] = 7;

		} else {
			// $returnInfo['StatusNumber1'] = $postData['document_number'];
			// $returnInfo['StatusNumber2'] = $documentNumberFormatted;
			// $returnInfo['Status'] = 'FAILURE';
			// $returnInfo['StatusNumber'] = 7;

			echo json_encode(array(
				'status' => 'FAILURE',
				'message' => lang('MSG_291')[0],
				"responseCode" => 200
			));
			exit();
		}
	}
}


///////////////////////////////// GENERIC FUNCTIONS FOR TRANSACTIONS SCREENS ///////////////////////////////////////
/**
 * @METHOD NAME 	: recursiveTransactionKey()
 *
 * @DESC 			: TO FIND THE RECURSIVE TRANSACTION KEY 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function recursiveTransactionKey($transactionDetails, $currentJsonId, $appendData)
{
    foreach ($transactionDetails as $key => &$data) {
		
        if ($data['currentJsonId'] == $currentJsonId) {
            if (!isset($data['child'])) {
                $data['child'] = [$appendData];
            }else {
				$data['child'][] = $appendData;
            }
            break;
        }else if (isset($data['child']) && is_array($data['child'])) {
            $data['child'] = recursiveTransactionKey($data['child'], $currentJsonId, $appendData);
        }
    }
    return $transactionDetails;
}


/**
 * @METHOD NAME 	: trackTransaction()
 *
 * @DESC 			: TO TRACK THE TRANSACTION FOR ONLY SAVE OPERATION 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function trackTransaction($screenName,$transactionId,$itemListArray){
	
	$CI 			  = &get_instance();
	$screenNamesArray = array('PURCHASE_REQUEST','PURCHASE_ORDER','GRPO',
	'INVENTORY_TRANSFER','INVENTORY_TRANSFER_REQUEST','SALES_QUOTE','SALES_ORDER','SALES_DELIVERY','SALES_RETURN','SALES_AR_INVOICE','SALES_AR_DP_INVOICE','SALES_AR_CREDIT_MEMO','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INVOICE','RENTAL_INSPECTION_IN','RENTAL_INSPECTION_OUT','RENTAL_WORKLOG');
		
	$itemFlag = 1;
	
	//echo "Screen name is ".$screenName;
	
	if(in_array($screenName,$screenNamesArray)){
		
		// CHECK THE ITMES 
		foreach($itemListArray as $itemKey => $itemValue){
			if(!empty($itemValue['copyFromId']) && !empty($itemValue['copyFromType'])){
			
				$itemFlag = 2;
				$copyFromType = $itemValue['copyFromType'];
				$copyFromId = $itemValue['copyFromId'];
						
				$screenDetails	= $CI->config->item('SCREEN_NAMES')[$copyFromType];

				$parentTableName 	= $screenDetails['tableName'];
				$childTableName		= $screenDetails['childTableName'];
				$fieldName			= $screenDetails['childRefId'];
				// GET THE PARENT TABLE ID USING CHILD TABLE ID 
				$parentTableId  = $CI->commonModel->getTransTblParentId($childTableName, $fieldName, $copyFromId);
				
				if(empty($parentTableId)){
					echo json_encode(array(
						'status' 		=> 'ERROR',
						'message' 		=> 'Something Went wrong in Transaction Tracking. Please contact admin.',
						"responseCode" 	=> 200
					));
					exit();
				}
				
				$formTableName	= strtoupper(str_replace("tbl_","",$parentTableName));
				$parentTransactionId = $parentTableId."~".$formTableName;
				break;
			}
		}
		
		// Frame Transaction Details Array 
		$transactionIds 					   				= $transactionId."~".$screenName;
		$frameNewTransactionRecordArray						= array(
																'parentJsonId' 	=> '',
																'currentJsonId' => $transactionIds,
																'id' 			=> $transactionId,
																'type' 			=> $screenName
															);
		
		$newTransactionRecord['transactionInfo'][] 	=  $frameNewTransactionRecordArray;
		if($itemFlag==1){
			// DIRECTLY SAVING TRANSACTION SCREEN
			$CI->commonModel->saveTransactionRecord($transactionIds,$newTransactionRecord);
			
		}else if($itemFlag==2){
			// SAVING FROM COPY FROM PROVESSION
			$passData = array();
			$getTransactionRecord = $CI->commonModel->getTransactionTrackingDetails($parentTransactionId);
					
			if(count($getTransactionRecord)==0)
			{ 
				// NO RECORD EXISTS | SCENARIO APPLICABLE ONLY FOR NEW RECORDS
				$CI->commonModel->saveTransactionRecord($transactionIds,$newTransactionRecord);
			}else if(count($getTransactionRecord)>0)
			{ 
				// RECORD ALREADY EXISTS 
				$updateId			 = $getTransactionRecord[0]['id'];
				$updateTransactionId = $getTransactionRecord[0]['transaction_ids'];
				$updateTransactionId = $updateTransactionId.",".$transactionIds;
				$getTransactionData  = json_decode($getTransactionRecord[0]['transaction_details']);
				$getTransactionData  = json_decode(json_encode($getTransactionData),true);
		
				// $screenName - GRPO // $parentTransactionId - 1~PURCHASE_ORDER
				
				$updateTransactionRecordArray	= array(
															'parentJsonId' 	=> $parentTransactionId,
															'currentJsonId' => $transactionIds,
															'id' 			=> $transactionId,
															'type' 			=> $screenName
														);
														
				
				$transactionDetails  = recursiveTransactionKey($getTransactionData['transactionInfo'], $parentTransactionId, $updateTransactionRecordArray);
				
				
				$updateTransactionData['transactionInfo'] 	=  $transactionDetails;
				
				$updateTransactionRecord = $CI->commonModel->updateTransactionRecord($updateId,$updateTransactionId,$updateTransactionData);
			
			}
		}
	}
}


/**
 * @METHOD NAME 	: removeEmptyCopyIdRecords()
 *
 * @DESC 			: - 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function removeEmptyCopyIdRecords($getItemInformation)
{
	
	$frameArray = array();
	$cnt 		= 0;

	if (count($getItemInformation) > 0) {
		foreach ($getItemInformation as $getKey => $getItemValue) {
			if (
				!empty($getItemValue['copyFromId']) &&
				!empty($getItemValue['copyFromType'])
			) {
				$frameArray[$cnt] = $getItemValue;
				$cnt++;
			}
		}
	}
	return $frameArray;
}


/**
 * @METHOD NAME 	: findTransArrayValue()
 *
 * @DESC 			: TO 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function findTransArrayValue($id, $postDataItemList)
{
	$itemValue = "";

	if (count($postDataItemList) > 0) {
		foreach ($postDataItemList as $postItemKey => $postItemValue) {
			if ($postItemValue['id'] == $id) {
				$itemValue = $postItemValue;
				break;
			}
		}
	}
	return	$itemValue;
}


/**
 * @METHOD NAME 	: checkRecordsEligibleForCopy()
 *
 * @DESC 			: TO CHECK THE RECORDS ARE ELIGIBLE FOR COPY 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkRecordsEligibleForCopy($copiedIds,$currentScreenTableName)
{	
	$CI 				= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$currentScreenTableName];
	$parentTableName 	= $screenDetails['tableName'];
		
	$CI->app_db->select([		
							'id'
						])
		->from($parentTableName)
		->where_in('id', $copiedIds)
		->where('status!=', 1)
		->where('is_deleted', '0');

	$rs = $CI->app_db->get();
	
	if ($rs->num_rows() > 0) { 
		echo json_encode(array(
							'status' => 'FAILURE',
							'message' => 'Closed or Cancelled Document should not be copied!',
							"responseCode" => 200
						));
		exit();
	}
}


/**
 * @METHOD NAME 	: checkDraftRecordsExists()
 *
 * @DESC 			: TO CHECK THE DRAFT RECORDS ARE EXISTS 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkDraftRecordsExists($copiedIds,$currentScreenTableName)
{	
	$CI 				= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$currentScreenTableName];
	$parentTableName 	= $screenDetails['tableName'];
		
	$CI->app_db->select([		
							'id'
						])
		->from($parentTableName)
		->where_in('id', $copiedIds)
		->where('is_draft=', 1)
		->where('is_deleted', '0');

	$rs = $CI->app_db->get();
	
	if ($rs->num_rows() > 0) { 
		echo json_encode(array(
							'status' => 'FAILURE',
							'message' => 'Draft Document should not be copied!',
							"responseCode" => 200
						));
		exit();
	}
}


/**
 * @METHOD NAME 	: getUniqueSourceParentTableIdByRefColumn()
 *
 * @DESC 			: 1. GET THE UNIQUE SOURCE PARENT TABLE IDS BY LOOPING THE ITEM LIST ARRAY. 
					  2. THIS OPTION IS ENABLED FOR MULTIPLE COPY RECORD FUNCTIONALITY.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getUniqueSourceParentTableIdByRefColumn($itemListArray)
{
	
	$CI = &get_instance();
	$sourceParentTableIds = array();
	
	foreach ($itemListArray as $itemKey => $itemValue) {
		$copyFromId    = $itemValue['copyFromId'];
		$copyFromType  = $itemValue['copyFromType'];

		if (!empty($copyFromType) && !empty($copyFromId)) {
			$screenDetails	= $CI->config->item('SCREEN_NAMES')[$copyFromType];

			$parentTableName 	= $screenDetails['tableName'];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];

			// GET THE PARENT TABLE ID USING CHILD TABLE ID 
			$parentTableId  = $CI->commonModel->getTransTblParentId($childTableName, $fieldName, $copyFromId);

			$sourceParentTableId[] =  $parentTableId;
		}
	}
	$sourceParentTableIds = array_unique($sourceParentTableId);
	return $sourceParentTableIds;
}

/**
 * @METHOD NAME 	: getScreenDetailsByDocumentType()
 *
 * @DESC 			: 1. TO get the screen details by document type id 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getScreenDetailsByDocumentType($documentTypeId){
	$CI = &get_instance();
	$findScreenNameArray	= array();
	$screenDetails			= $CI->config->item('SCREEN_NAMES');
	foreach($screenDetails as $screenKey => $screenValue){
		if($screenValue['id'] == $documentTypeId){
			$findScreenNameArray = $screenValue;
			break;
		}
	}
	return $findScreenNameArray;
}


/**
 * @METHOD NAME 	: identityDocumentMode()
 *
 * @DESC 			: -
 * @RETURN VALUE 	: $modelOutput array
 * @PARAMETER 		: $getPostData array
 * @SERVICE 		: WEB
 * @ACCESS POINT 	: -
 **/
function identityDocumentMode($getPostData,$approvalProcessStatus){
	$documentProcessMode = 'PROCESS_DOCUMENT';
	if($getPostData['documentNumberingType'] == 'DRAFT'){
		$documentProcessMode = 'NO_VALIDATION';
	}else if($approvalProcessStatus==1){ // WAITING FOR APPROVAL 
		$documentProcessMode = 'DO_VALDATION_ALONE';
	}
	return $documentProcessMode;
}


//////////////////////APPROVAL PROCESS ACTIVITY ////////////////////////////////////////////////////////
/**
 * @METHOD NAME 	: checkApprovalProcessExists()
 *
 * @DESC 			: 1. TO CHECK WHETHER APPROVAL PROCESS EXISTS FOR THE PARTICULAR USER
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkApprovalProcessStatus($getPostData,$screenName)
{
	// Approval Process [1 -> Pending, 2-> Approved, 3 -> Rejected ]
	$CI = &get_instance();
	
	// Pass Data 
	$passData['approvalStatus'] = 2;

	// SCREEN DETAILS 
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$documentID 		= $screenDetails['id'];

//	echo "Document id is ".$documentID;

	$orginatorWhereQry 	= "FIND_IN_SET(".$CI->currentUserId.','.APPROVAL_TEMPLATES.".originator_id) >0 ";
	$documentIDWhereQry ="FIND_IN_SET(".$documentID.','.APPROVAL_TEMPLATES.".document_id) >0 ";

	$CI->app_db->select([		
							'id','originator_id',
							'document_id','approval_stages_id'
						])
		->from(APPROVAL_TEMPLATES)
		->where($orginatorWhereQry)
		->where($documentIDWhereQry)
		->where('is_deleted', '0');

	$rs = $CI->app_db->get();
	$approvalTemplateResult = $rs->result_array();
		
	if ($rs->num_rows() > 1) {
		echo json_encode(array(
			'status' => 'FAILURE',
			'message' => 'Multiple Documents or same user found in Approval Templates. Please, contact admin!',
			"responseCode" => 200
		));
		exit();
	}
	else if($rs->num_rows() ==0 ) {
		$passData['approvalStatus'] = 4; // No Approval process
	}
	else if($rs->num_rows() ==1 || $rs->num_rows() ==0){ // Temp Condition
		$passData['approvalStagesId'] = $approvalTemplateResult[0]['approval_stages_id'];
		$passData['approvalStatus'] = 1;// Pending 
	}
	return  $passData;
}

/**
 * @METHOD NAME 	: getOverAllApprovalStatusForDocument()
 *
 * @DESC 			: 1. TO GET THE DOCUMENT NUMBER TYPE ID : For Example: (Draft,Manual,)
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
 function getOverAllApprovalStatusForDocument($screenName,$documentId)
 {
	// APPROVAL PROCESS
	$CI = &get_instance();
	
	// SCREEN DETAILS 
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$documentTypeID 	= $screenDetails['id'];
	
	 
	$CI->app_db->select([		
						'id','document_id','document_type_id',
						'document_number','overall_approval_status',
						'approvers_id'
					])
	->from(APPROVAL_STATUS_REPORT)
	->where('document_id',$documentId)
	->where('document_type_id',$documentTypeID);
	
	$rs = $CI->app_db->get();
	$approvalStatusResult = $rs->result_array();
	return $approvalStatusResult;

 }


/**
 * @METHOD NAME 	: getDocumentNumberTypeId()
 *
 * @DESC 			: 1. TO GET THE DOCUMENT NUMBER TYPE ID : For Example: (Draft,Manual,)
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getDocumentNumberTypeId($screenName,$documentType){
	// screenName -> Purchase Request or GRPO (format: ) | documentType -> MANUAL, DRAFT, CUSTOM, PRIMARY 
	$CI = &get_instance();
	
	// SCREEN DETAILS 
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$documentTypeID 	= $screenDetails['id'];

	$CI->app_db->select([		
							'id','document_type_id',
							'document_numbering_type'
						])
		->from(MASTER_DOCUMENT_NUMBERING)
		->where('document_numbering_type',$documentType)
		->where('document_type_id',$documentTypeID)
		->where('is_deleted', '0');

	$rs = $CI->app_db->get();

	return $rs->result_array();

}


/**
 * @METHOD NAME 	: getApprovalStagesDetails()
 *
 * @DESC 			: 1. TO GET THE APPROVAL STAGE DETAILS 
 * @RETURN VALUE 	: -
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getApprovalStagesDetails($approvalStagesId){
	$CI = &get_instance();
	
	$CI->app_db->select([
		'id','no_of_approvals',
		'no_of_rejections','authorizer_id',
		'status'
	])
	->from(MASTER_APPROVAL_STAGES)
	->where('id',$approvalStagesId)
	->where('is_deleted', '0');
	$rs = $CI->app_db->get();

	return $rs;
}

/**
 * @METHOD NAME 	: saveApprovalStatusReport()
 *
 * @DESC 			: 1. TO SAVE THE APPROVAL STATUS REPORT FUNCTIONALITY
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function saveApprovalStatusReport($getData,$screenName){

	$CI = &get_instance();

	// SCREEN DETAILS 
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$documentTypeID 	= $screenDetails['id'];

	$rs 	   = getApprovalStagesDetails($getData['approval_stages_id']);
	$totRows   = $rs->num_rows();
	$getRsData = $rs->result_array();

	// Error Handling
	if($totRows ==0 || $totRows>1 ) {
		echo json_encode(array(
			'status' => 'FAILURE',
			'message' => 'Somthing Went wrong in Approval Mechanism. Please contact admin!',
			"responseCode" => 200
		));
		exit();
	}

	// SAVE RECORDS TO APPROVAL STATUS REPORT 
	$saveData['document_id'] 		= $getData['document_id'];
	$saveData['document_number'] 	= $getData['document_number'];
	$saveData['document_type_id'] 	= $documentTypeID;
	$saveData['no_of_approvals'] 	= $getRsData[0]['no_of_approvals'];
	$saveData['no_of_rejections'] 	= $getRsData[0]['no_of_rejections'];
	$saveData['approvers_id'] 		= $getRsData[0]['authorizer_id'];
	$saveData['document_created_by'] = $CI->currentUserId;

	$insertId 	= $CI->commonModel->insertQry(APPROVAL_STATUS_REPORT, $saveData);

	$passOutput['approvalStatusInsertId'] = $insertId;
	$passOutput['document_type_id']		  = $documentTypeID;
	$passOutput['approversId'] 			  = $getRsData[0]['authorizer_id'];
	return $passOutput;
}


/**
 * @METHOD NAME 	: checkDocumentForUpdate()
 *
 * @DESC 			: TO CHECK WHETHER DOCUMENT IS ELIGABLE FOR UPDATE OPERATION [GRPO,SALES,INVENTORY ETC]
 * CONSTRAINTS 		: DOCUMENT CREATOR AND DOCUMENT APPROVER CAN ABLE TO EDIT THE DOCUMENT
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkDocumentForUpdate($oldTableRecords,$screenName){
	//printr($oldTableRecords);
	$CI 	   = &get_instance();
	$id		   = $oldTableRecords['id'];
	$createdBy = $oldTableRecords['created_by'];
	$currentUserId = $CI->currentUserId;
	$approversModifyDocumentFlag = $CI->approversModifyDocument;
	//$currentUserId = 203;
	//$approversModifyDocumentFlag = 1;
	
	// FOR APPROVED DOCUMENTS 
	if($oldTableRecords['approval_status'] == 2){
		echo json_encode(array(
			'status' => 'FAILURE',
			'message' => 'Approved document could not be edited!',
			"responseCode" => 200
		));
		exit();
	}
	
	// PENDING OR REJECTED DOCUMENT
	if($oldTableRecords['approval_status'] == 1 || $oldTableRecords['approval_status'] == 3) {
		$chkModifyDocumentFlag = 0;
		$approvalStatusRecord 	= getOverAllApprovalStatusForDocument($screenName,$id);
		$approversId			= $approvalStatusRecord[0]['approvers_id'];
		
		if($createdBy == $currentUserId){
			$chkModifyDocumentFlag = 1;
		}
	
		if($approversModifyDocumentFlag==1 && $chkModifyDocumentFlag==0){ 
			$approverIdArray 	 = explode(",",$approversId);
			$checkApproverExists = in_array($currentUserId,$approverIdArray);
			if($checkApproverExists){
				$chkModifyDocumentFlag = 1;
			}
		}
		
		if($chkModifyDocumentFlag == 0){
			echo json_encode(array(
				'status' => 'FAILURE',
				'message' => 'You dont have permission to update the document!',
				"responseCode" => 200
			));
			exit();	
		}
	}

}


/**
 * @METHOD NAME 	: reInitializeApprovalProcess()
 *
 * @DESC 			: 1. TO RE-INTIALIZE THE APPROVAL PROCESS
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function reInitializeApprovalProcess($oldTableRecords,$getpostData,$screenName){
	$CI = &get_instance();
	$proceedApprovalActvityFlag = 0; 
	if(($oldTableRecords['approval_status'] == 1) || 
	   ($oldTableRecords['approval_status'] == 3)
	  ){
	  	$proceedApprovalActvityFlag = 1;
					
			// Approval Process [1 -> Pending, 2-> Approved, 3 -> Rejected ]
		
			// SCREEN DETAILS 
			$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
			$parentTableName 	= $screenDetails['tableName'];
			$childTableName		= $screenDetails['childTableName'];
			$documentTypeID 	= $screenDetails['id'];
			$documentId			= $getpostData['id'];

			$updateData['overall_approval_status']  =  1;
			$updateData['total_approved'] 			=  0;
			$updateData['total_rejected']  			=  0;
			$whereQry                      = array(
												   'document_id'		=> $documentId,
												   'document_type_id'	=> $documentTypeID,
												);
			$CI->commonModel->updateQry(APPROVAL_STATUS_REPORT, $updateData, $whereQry);

			// GET APPROVAL STATUS REPORT ID 
			$CI->app_db->select([
				'id'
			])
			->from(APPROVAL_STATUS_REPORT)
			->where('document_id',$documentId)
			->where('document_type_id',$documentTypeID);
			$rs = $CI->app_db->get();
			$totRows= $rs->num_rows();
			$getRsData = $rs->result_array();
			$approvalStatusReportId =  $getRsData[0]['id'];
			
			// UPDATE THE individual_approval_status_report -> prev_approval_flag
			$updateIndvApprovalData['prev_approval_flag'] = 1;
			$whereQry                					  = array(
																'approval_status_report_id'		=> $approvalStatusReportId,
																);
			$CI->commonModel->updateQry(INDIVIDUAL_APPROVAL_STATUS_REPORT, $updateIndvApprovalData, $whereQry);
	}
	return $proceedApprovalActvityFlag;
}



////////////////////// END OF APPROVAL PROCESS ACTIVITY ////////////////////////////////////////////////////////


////////////////////////////////GENERIC FUNCTIONS FOR TRANSACTIONS SCREENS ENDS //////////////////////////////////

/******************************** END OF FILE ************************************************************/
