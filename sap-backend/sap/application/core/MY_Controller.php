<?php if( ! defined('BASEPATH')) exit('No direct script acess allowed');

class MY_Controller extends CI_Controller{

	public static $instance;
		
	protected $appDate  = NULL;
	protected $userDate = NULL;
	
	// Database Manipulation 
	public $app_db;
	public $org_db;
	public $currentCompanyId;
	
	public function __construct(){
		parent::__construct();
		self::$instance || self::$instance =& $this;
		
		// Loading Custom Helpers
		$this->load->helper('custom_helper');
		$this->load->helper('spi_helper');
		$this->load->helper('rental_helper');
		$this->load->helper('rental_invoice_helper');
		$this->load->helper('notification_helper');
		$this->load->helper('jwt_helper');
		$this->load->helper('language');
		
		
		$this->load->model('common/common_services_model','commonModel');
		
		$this->currentRequestOrginalData = '';
		
		// SetCurrent Date and Time
		//$this->setCurrentDateTime(); // cc->prem
		
		if ($this->input->is_cli_request())
		{
		    $this->setupCli();
		}
		else
		{
		    $this->setupWeb();
		    // $this->setupCli();
		}
		
	}
	
	protected function setupWeb()
	{
	    // SET HEADERS
		$this->setHeaders();
		
		// Validating Headers
        $this->validateHeaders();
		 		
		// Method (GET / POST / PUT / DELETE ) Request Data
		if (!empty($_FILES))
		{
			$tempRequestData = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];
		}
		else
		{
			$this->currentRequestOrginalData = file_get_contents('php://input');
			$tempRequestData = json_decode(file_get_contents('php://input'), true);
		}
		
		$this->currentRequestData  = validateInput($tempRequestData);
		
		// Validate Tokens
		$this->validateToken();
	
		
		
	}

	protected function setupCli()
	{

		// echo $this->uri->segment(1);exit;
		if ('cronjob' != strtolower($this->uri->segment(1)))
	    {
	        $outputData = [
	            'status'  => 'FAILURE',
			    'message' => 'Method is not allowed to call',
            ];
			echo json_encode($outputData);
			exit;
	    }

		// SET HEADERS
		//$this->setHeaders();
				
		// Validating Headers
		$this->validateHeadersCli();
				
		// Method (GET / POST / PUT / DELETE ) Request Data
		if (!empty($_FILES))
		{
			$tempRequestData = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];
		}
		else
		{
			$tempRequestData = json_decode(file_get_contents('php://input'), true);
		}
		
		$this->currentRequestData  = validateInput($tempRequestData);
		
		// Validate Tokens
		$this->validateToken();
		
	}
	
	
	// protected function setupCli()
	// {
	//     if ('cronjob' != strtolower($this->uri->segment(1)))
	//     {
	//         $outputData = [
	//             'status'  => 'FAILURE',
	// 		    'message' => 'Method is not allowed to call',
    //         ];
	// 		echo json_encode($outputData);
	// 		exit;
	//     }
	    
	//     $requiredOptions = [
	//         'mt_id',
	//     ];
	    
	//     if (!empty($requiredOptions)) {
	//         $missingHeaders  = array_diff(
	//             $requiredOptions, array_keys($this->uri->getCliHeaders())
    //         );
    //         if (!empty($missingHeaders)) {
    //             $outputData = [
	//                 'status'  => 'FAILURE',
	// 		        'message' => 'Following options are missing: '
	// 		            . implode(', ', $missingHeaders),
    //             ];
	// 		    echo json_encode($outputData);
	// 		    exit;
    //         }
    //     }
	// }
	
	
	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : TO SET THE HEADERS OF THE APPLICATION
	//--------------------------------------------------------------------------------------
	function setHeaders() {
		/*
		$this->output->set_header('Access-Control-Allow-Origin: *'); // Set Cross Origin Globally
		$this->output->set_header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		$this->output->set_header('Access-Control-Allow-Headers:X-Requested-With, X-Auth-Token, X-HTTP-Method-Override,Content-Type, Accept, Authorization, Multi-Tenant-Id, Origin');
		*/
		header('Access-Control-Allow-Origin:*'); // Set Cross Origin Globally
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers:X-Requested-With, X-Auth-Token, X-HTTP-Method-Override,Content-Type, Accept, Authorization, Multi-Tenant-Id, Origin, X-Identity-Key, X-Mobile-Device-Id, branchId');
	}
	
	
	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : VALIDATE THE HEADERS AND ASSIGN VARIABLES
	//--------------------------------------------------------------------------------------
	function validateHeaders()
	{

		$headers = $this->input->request_headers();

		foreach ($headers as $key => $value){
			// Accept Language
			if(strtolower($key)==strtolower("Accept-Language")){
				//$language = trim($value); // LATEST CHANGE IN CHROME : en-US,en;q=0.9 // So hard-coded the language
				$language = 'en';
				$config   =& get_config();
				$config['language']	= 'english';
				$this->lang->load("en_message_lang","en");
				$this->lang->load("en_label_lang","en");
				$this->currentUserLanguage 	= $language;
			}

			// Set MultiTenant Id
			if(strtolower($key)==strtolower("Multi-Tenant-Id")){
				$this->currentUserMultiTenantId = $value;
			}

			//set default multi tenent id (Mainly for registration)
			if( empty($this->currentUserMultiTenantId)) {
				$this->currentUserMultiTenantId = 1;
			}

			// Set X-Auth Token
			if(strtolower($key)==strtolower("X-Auth-Token")){
				$this->currentUserToken = $value;
			}
			
			
			// SET BRANCH ID 
			if(strtolower($key)==strtolower("branchId")){
				$this->currentbranchId = $value;
			}
			
			
			
		}	
	}


	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : VALIDATE THE HEADERS AND ASSIGN VARIABLES
	//--------------------------------------------------------------------------------------
	function validateHeadersCli()
	{

		$language = 'en';
		$config   = &get_config();
		$config['language']	= 'english';
		$this->lang->load("en_message_lang","en");
		$this->lang->load("en_label_lang","en");
		$this->currentUserLanguage 	= $language;

		$this->currentUserMultiTenantId = 1;

		$this->currentUserMultiTenantId = 1;

		$this->currentUserToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJjb21wYW55SWQiOiIxIiwiYnJhbmNoSWQiOiIxIiwiaWF0IjoxNjE2Njk1Mzc4LCJncm91cFVzZXJzIjpbIjEiLCIxMTAiLCIxMTEiLCIxMTIiLCIxMTMiLCIxMTQiLCIxMTUiLCIxMTYiLCIxMTciLCIxMTgiLCIxMTkiLCIxMjAiLCIxMjEiLCIxMjIiLCIxMjMiLCIxMjQiLCIxMjUiLCIxMjYiLCIxMjciLCIxMjgiLCIxMjkiLCIxMzAiLCIxMzEiLCIxMzIiLCIxMzMiLCIxMzQiLCIxMzUiLCIxMzYiLCIxMzciLCIxMzgiLCIxMzkiLCIxNDAiLCIxNDEiLCIxNDIiLCIxNDMiLCIxNDQiLCIxNDUiLCIxNDYiLCIxNDciLCIxNDgiLCIxNDkiLCIxNTAiLCIxNTEiLCIxNTIiLCIxNTMiLCIxNTQiLCIxNTUiLCIxNTYiLCIxNTciLCIxNTgiLCIxNjIiLCIxIl0sInJvbGVJZGVudGlmaWVyIjoiQURNSU4iLCJoaWVyYXJjaHlNb2RlIjoiMiIsIm1vZHVsZSI6IkJSQU5DSCIsInVzZXJCcmFuY2hJZHMiOiIxLDIsMyw0LDUsNiw3LDgsOSwxMCJ9.TEgYG3d7SQwnwuVwPGSL-s8qcVWu3J47kg3oTkIWlWI';
	
		// SET BRANCH ID 
		$this->currentbranchId = 1;
	}

	
	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : CHECK THE REQUEST METHOD IS GET / POST
	//--------------------------------------------------------------------------------------
	function setCurrentDateTime(){
        // SET PHP TIMEZONE TO UTC.
        date_default_timezone_set('UTC');
        
        // Current User date using CARBON
        $this->appDate         = new Carbon\Carbon('now', 'UTC');
		$this->currentDateTime = (string)$this->appDate;
		
		// Set MYSQL Session timezone to UTC
		$this->db->query('SET time_zone="'.$this->appDate->format('P').'"');
		
		$this->db->query('SET FOREIGN_KEY_CHECKS = 0');
	}


	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : CHECK THE REQUEST METHOD IS GET / POST
	//--------------------------------------------------------------------------------------
	function checkRequestMethod($methodName){
		$requestMethodName = $this->input->method();

		if(strtolower($methodName)==strtolower($requestMethodName)){
			return true;
		}else{
			$outputData['status']  = 'FAILURE';
			$outputData['message'] = 'Invalid Method Passed. You should pass method in '.strtoupper($methodName);
			echo json_encode($outputData);
			exit;
		}
	}

	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : IT IS USED TO VALIDATE THE TOKEN
	//--------------------------------------------------------------------------------------
	function validateToken() {
        // Module and Segment Details
        $moduleName 	 = strtolower($this->uri->segment(1));
        $controllerName  = strtolower($this->uri->segment(2));
        $actionName 	 = strtolower($this->uri->segment(3));
		

        //Is token not required ?
        $module = $this->config->item($moduleName, 'checkNoTokens');
		
        if (!empty($module)) {
            $module = array_change_key_case($module, CASE_LOWER);
            if (isset($module[$controllerName])) {
                $module[$controllerName] = array_map('strtolower', $module[$controllerName]);
                if (in_array($actionName, $module[$controllerName])) {
                    //Token is not required
                    return true;
                }
            }
        }
        
        /*AUTHENTICATION*/        
        try {

            $result             		 = JWT::decode($this->currentUserToken,$this->config->item('jwt_key'));

			$this->currentUserId 		 = $result->id;
            $this->currentCompanyId		 = $result->companyId;
			$this->module 				 = $result->module;
			
			if($this->module == 'BRANCH'){
			
				$this->rentalWorklogSheetType = $result->rentalWorklogSheetType;
				$this->approversModifyDocument = $result->approversModifyDocument;
			
				if(empty($this->currentbranchId)){
					$this->currentbranchId		 = $result->branchId;
				}
				
				if(empty($this->rentalWorklogSheetType)) {
					$this->rentalWorklogSheetType		 = 1;
				}
				//$this->rentalWorklogSheetType		 = 2;
				$this->currentgroupUsers	  = $result->groupUsers;
				$this->currentAccessControlId = $result->currentAccessControlId;
				$this->hierarchyMode 		  = $result->hierarchyMode;
				$this->currentUserBranchIds   = $result->userBranchIds;
				$this->currentEmployeeType 	  = strtolower($result->employeeType);
				$this->customerBusinessPartnerId  = $result->customerBusinessPartnerId;
				$this->dealerBusinessPartnerId  = $result->dealerBusinessPartnerId;
				
				
			}

			// CONNECT THE ORGANIZATION BASED DATABASE 
			$this->connectClientDb();
			
			if(ENABLE_ACL_CHECK == 1){
				$this->checkAcl();
			}
		    
        } catch (Exception $e) {
            //Invalid token response
            $outputData = [
                'status'  => 'FAILURE',
                'message' => ($e instanceof DomainException || $e instanceof DomainException) ? 'Invalid Auth Token' : 'Invalid Token',
            ];
            echo json_encode($outputData);
            exit;
        }
        
		
	}
	
	
	//--------------------------------------------------------------------------------------
	// DESCRIPTION    : IT IS USED TO CONNECT THE CLIENT DATABASE
	//--------------------------------------------------------------------------------------
	function connectClientDb(){
	
	
		if(!empty($this->currentCompanyId)){
			
			// GET DATABASE NAME BASED UPON THE COMPANY ID 
			$query 			= $this->db->query('SELECT * 
												FROM '.ORG_DATABASE_NAME.'.'.ORG_TBL_COMPANY_INFO.' WHERE 
												id='.$this->currentCompanyId);
			$companyOutPut 		= $query->result_array();
			
			
			// DATABASE DETAILS 
			$dbHostName = $companyOutPut[0]['db_hostname'];
			$dbUserName = $companyOutPut[0]['db_username'];
			$dbPassword = $companyOutPut[0]['db_password'];
			$dbName 	= $companyOutPut[0]['database_name'];
			
			
			// LOAD DATABASE
			$appDb = array(
					'dsn'	=> '',
					'hostname' => $dbHostName,
					'username' => $dbUserName,
					'password' => $dbPassword,
					'database' => $dbName,
					'dbdriver' => 'mysqli',
					'dbprefix' => '',
					'pconnect' => FALSE,
					'db_debug' => (ENVIRONMENT !== 'production'),
					'cache_on' => FALSE,
					'cachedir' => '',
					'char_set' => 'utf8',
					'dbcollat' => 'utf8_general_ci',
					'swap_pre' => '',
					'encrypt' => FALSE,
					'compress' => FALSE,
					'stricton' => FALSE,
					'failover' => array(),
					'save_queries' => TRUE
				);
			
			$this->app_db = $this->load->database($appDb,true);	
			
		}
	}
	
	
	protected function validate()
	{
		$route = $this->router->fetch_directory()
			. $this->router->fetch_class()
			. '/'
			. $this->router->fetch_method()
		;
		$route = strtolower($route);
		$rules = $this->getValidationOptions($route);
		
		if (!empty($rules))
		{
			$this->load->library('input_validator');
			$this->load->helper('validation');
			
			$isValid = $this->input_validator->validate($this->currentRequestData,$rules);
			if (!$isValid)
			{
				$this->throwValidationErrors($this->input_validator->getErrors());
				// Is exit needed here?
				exit();
			}
		}
	}
	
	protected function getValidationOptions($route)
	{
		$rules = $this->config->item($route, 'form_validations');
		return is_array($rules) ? $rules : [];
	}
	
	protected function throwValidationErrors(array $errors)
	{
		$flatErr = iterator_to_array(
			new \RecursiveIteratorIterator(
				new \RecursiveArrayIterator($errors)
			)
		);
		$outputData = [
			'status'  => 'FAILURE',
			'message' => implode("<br>", $flatErr),
		];
		echo json_encode($outputData);
		exit;
	}
	
	protected function checkAcl()
	{
		$moduleName 	 = strtolower($this->uri->segment(1));
        $controllerName  = strtolower($this->uri->segment(2));
        $actionName 	 = strtolower($this->uri->segment(3));

		$segment_url = "/".$moduleName."/".$controllerName."/";
		$acl_url = "/".$moduleName."/".$controllerName."/".$actionName;

		// Public Segment Urls
		$public_segment_url_list = array(
			"/pages/login/"
		);
        $is_public_url = (in_array($segment_url, $public_segment_url_list)) ? 1 : 0;

		if($is_public_url){
			return true;
		}
		else {

			$access_control_id = $this->currentAccessControlId;
			$segment_url = "'".$segment_url."'";

			$query = 'select * from
						(select
						/* Master Module Screen Mapping */
						a.id,
						a.module_id,
						a.screen_name,
						a.screen_order,
						a.url_segment,
	
						/* Master Module  */
						mm.module_name,
						mm.module_order,
			
						/* Access Control Screen List*/
						acs.id as access_control_list_id,
						acs.access_control_id as access_control_name_id,
						acs.master_module_screen_mapping_id as screen_id,
						acs.enable_view,
						acs.enable_add,
						acs.enable_update,
						acs.enable_download,
						acs.created_on,
						acs.updated_on,
						acs.created_by,
						acs.updated_by,
	
						/* Access Control */
						ac.access_control_name,
						ac.status as access_control_status,
	
						/* Master Screen Routing Url Mapping */
						msrum.enable_view_url_id,
						msrum.enable_add_url_id,
						msrum.enable_update_url_id,
						msrum.enable_download_url_id
			
						FROM '.MASTER_MODULE_SCREEN_MAPPING.' as a
						LEFT JOIN  '.MASTER_MODULE.' as mm
						ON mm.id = a.module_id
						JOIN '.ACCESS_CONTROL_SCREEN_LIST.' as acs
						ON acs.master_module_screen_mapping_id = a.id and acs.access_control_id = '.$access_control_id.'
						LEFT JOIN '.ACCESS_CONTROL.' as ac
						ON acs.access_control_id = ac.id
						LEFT JOIN '.MASTER_SCREEN_ROUTING_URL_MAPPING.' as msrum 
						ON msrum.master_module_screen_mapping_id = a.id
						WHERE a.is_deleted = 0 AND a.url_segment = '.$segment_url.')  as a
					WHERE id != 0 ORDER BY module_id';
	
			$rs	= $this->app_db->query($query);
			$resultData  = $rs->result_array();
			
			$blockedUrlIds = array();
			$disableFlag = 3;  // (1 => default, 2 => allowed, 3 => disabled)
			foreach($resultData as $sValue){
				
				if(isset($sValue['enable_view']) && $sValue['enable_view'] == $disableFlag){
					$dataUrlIds = explode(",",$sValue['enable_view_url_id']);
					$blockedUrlIds = array_merge($blockedUrlIds, $dataUrlIds);
				}
	
				if(isset($sValue['enable_add']) && $sValue['enable_add'] == $disableFlag){
					$dataUrlIds = explode(",",$sValue['enable_add_url_id']);
					$blockedUrlIds = array_merge($blockedUrlIds, $dataUrlIds);
				}
	
				if(isset($sValue['enable_update']) && $sValue['enable_update'] == $disableFlag){
					$dataUrlIds = explode(",",$sValue['enable_update_url_id']);
					$blockedUrlIds = array_merge($blockedUrlIds, $dataUrlIds);
				}
	
				if(isset($sValue['enable_download']) && $sValue['enable_download'] == $disableFlag){
					$dataUrlIds = explode(",",$sValue['enable_download_url_id']);
					$blockedUrlIds = array_merge($blockedUrlIds, $dataUrlIds);
				}
			}
	
			$IsAclAllowed = 1;
			if(count($blockedUrlIds) > 0){
				$blockedUrlIdStr = implode(",",$blockedUrlIds);
				$blockedUrls = array();
				$query = 'select * from
							(select
							/* Master Routing Url */
							a.id,
							a.url
							FROM '.MASTER_ROUTING_URL.' as a
							WHERE a.id IN ('.$blockedUrlIdStr.'))  as a
						WHERE id != 0';
		
				$rs	= $this->app_db->query($query);
				$resultUrlData  = $rs->result_array();
				foreach($resultUrlData as $uValue){
					$blockedUrls[] = trim(strtolower($uValue['url']));
				}
		
				if (in_array(trim($acl_url), $blockedUrls))
				{
					$IsAclAllowed = 0;
				}
			}
		
			if($IsAclAllowed == 0){
				$outputData = [
					'status'  => 'FAILURE',
					'message' => 'Permission not allowed Acl Error. Please contact Administrator.',
				];
				echo json_encode($outputData);
				exit;
			}
		}
		
	}
	
    protected function initCrypto() {
        $this->load->library('encryption');
	    $this->encryption->initialize([
	        'driver' => 'openssl',
            'cipher' => 'aes-256',
            'mode'   => 'ctr',
            'key'    => $this->config->item('identification_key'),
        ]);
    }
    
    protected function getIndentityText() {
        $headers  = $this->input->request_headers(true);
        $clientIp = $this->input->ip_address();
	    $browser  = isset($headers['X-Mobile-Device-Id'])
	        ? $headers['X-Mobile-Device-Id'] : $this->input->user_agent()
        ;
        
        //To avoid the empty value attack, set random string
        if (0 == strlen(trim($browser))) {
            $browser = md5(rand(111111, 999999));
        }
	    
	    $text = implode('+', [$clientIp, $browser]);
	    
	    return $text;
    }
	
	public function getIdentificationToken() {
	    $this->initCrypto();
	    
	    $identificationKey = $this->encryption->encrypt(
	        $this->getIndentityText()
        );
        
        return $identificationKey;
	}
	
	public function validateIdentificationKey() {
	    $this->initCrypto();
	    
	    $clientIdentity = $this->input->get_request_header('X-Identity-Key');
	    $identification = $this->encryption->decrypt($clientIdentity);
	    
        if ($this->getIndentityText() != $identification) {
            throw new Exception('Identification is failed');
        }
	}
	
	public function getAppDate() {
	    return $this->appDate;
	}
	
	public function getUserDate() {
	    if (is_null($this->userDate)) {
            try {
	            $tz = !isset($this->currentUserProfile)
	                ? $this->appDate->tz
	                : new DateTimeZone($this->currentUserProfile['php_tz_identifier'])
                ;
	            $this->userDate = new Carbon\Carbon('now', $tz);
	        }catch (Exception $e) {
	            log_message('INFO', 
	                'Invalid TZ: '.$this->currentUserId.':'.$this->currentUserProfile['php_tz_identifier']
                );
                $this->userDate = Carbon\Carbon::now();
	        }
        }
        return $this->userDate;
	}
}
?>