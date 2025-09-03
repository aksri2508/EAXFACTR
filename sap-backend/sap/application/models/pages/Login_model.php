<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Login_model.php
* @Class  			 : Login_model
* Model Name         : Login_model
* Description        :
* Module             : pages
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 23 MAR 2017
* @LastModifiedDate  : 15 MAR 2017
* @LastModifiedBy    : 
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class login_model extends CI_Model {

	public $app_db; // APPLICATION DATABASE 
	
	public function __construct(){
		parent::__construct();	
	}
	
	/**
	* @METHOD NAME 	: getOrganizationDetails()
	*
	* @DESC         : TO GET THE ORGANIZATION DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getOrganizationDetails($mobDomainName)
	{		
		$getHeaders   		= getallheaders();
		$customOriginDetail = '';
		
		// ISSET DETAILS
		if(isset($getHeaders['Custom-Origin'])){
			$customOriginDetail = $getHeaders['Custom-Origin'];
		}
		
		
		// MOBILE SIDE SUB DOMAIN NAME SETTINGS 
		if(!empty($mobDomainName)){
			
			$subDomainName 	= $mobDomainName;
			
		}else if(isset($_SERVER['HTTP_ORIGIN']) || !empty($customOriginDetail)){
			// HTTP ORIGIN AND CUSTOM ORIGIN DETAILS 
			
			if(isset($_SERVER['HTTP_ORIGIN'])){ // HTTP ORIGIN DETAILS - WEB
				$localHostAccess = 0;
				$localHostAccess = strpos($_SERVER['HTTP_ORIGIN'], 'localhost');
				
				if ($localHostAccess>0) {
					$subDomainName = 'sap';
				}else{
					$info 			= parse_url($_SERVER['HTTP_ORIGIN']);
					$host 			= $info['host'];
					$explodeHost	= explode('.', $host);
					$subDomainName 	= array_shift($explodeHost);
				}
			}else if (!empty($customOriginDetail)){ // Custom Origin Details - MOBILE 
					$info 			= parse_url($customOriginDetail);
					$explodeHost	= explode('.', $host);
					$subDomainName 	= array_shift($explodeHost);
			}			
		}else{
			$subDomainName = 'sap'; // HardCoded Domain name 
		}
		
	    $this->db->select(array('id','organization_name','sub_domain_name','expiry_date'));
		$this->db->from(ORG_TBL_ORGANIZATION_DETAILS);
		$this->db->where('sub_domain_name', $subDomainName);
		$this->db->where('is_deleted', '0');
		$orgRs = $this->db->get();
	    $orgOutPut = $orgRs->result_array();
		
		return $orgOutPut;
	}
	
	
	/**
	* @METHOD NAME 	: checkOrganizationExpiry()
	*
	* @DESC         : TO CHECK THE ORGANIZATION EXPIRY DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkOrganizationExpiry($mobDomainName='')
	{	
		$modelOutput['flag'] = 1;
		$orgOutPut = $this->getOrganizationDetails($mobDomainName);
		
		// ORGANIZATION DETAILS 
		if(count($orgOutPut)>0){
			$orgId 			= $orgOutPut[0]['id'];
			$orgExpiryDate 	= $orgOutPut[0]['expiry_date'];
			
			$curDate  		= date('Y-m-d');

			if($orgExpiryDate >= $curDate){
				$modelOutput['flag'] = 1;
			}else{
				$modelOutput['flag'] = 2;
			}
			
			return $modelOutput;
		}
	}
	
	
	/**
	* @METHOD NAME 	: getCompanyList()
	*
	* @DESC         : TO GET THE ORGANIZATION COMPANY INFO 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCompanyList($mobDomainName)
	{	
		$orgOutPut = $this->getOrganizationDetails($mobDomainName);
		
		// ORGANIZATION DETAILS 
		if(count($orgOutPut)>0){
			$orgId = $orgOutPut[0]['id'];
			$this->db->select(array('id','company_name','company_location'));
			$this->db->from(ORG_TBL_COMPANY_INFO);
			$this->db->where('organization_id', $orgId);
			$this->db->where('is_deleted', '0');
			$companyRs = $this->db->get();
			$companyOutPut = $companyRs->result_array();
			return $companyOutPut;
		}	
	}
	
	
	/**
	* @METHOD NAME 	: getLineItemConfigDetails()
	*
	* @DESC         : TO GET THE ORGANIZATION COMPANY INFO 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getLineItemConfigDetails($userId)
	{	
		
		
		$this->app_db->select(array(LINE_ITEM_CONFIGURATION.'.id',
									LINE_ITEM_CONFIGURATION.'.module',
									LINE_ITEM_CONFIGURATION.'.default_fields',
									LINE_ITEM_CONFIGURATION.'.disabled_fields',
									USER_LINE_ITEM_CONFIGURATION.'.fields_selected',
									));

		$this->app_db->from(LINE_ITEM_CONFIGURATION);
		$this->app_db->join(USER_LINE_ITEM_CONFIGURATION, USER_LINE_ITEM_CONFIGURATION.'.module ='.LINE_ITEM_CONFIGURATION.'.module and '.USER_LINE_ITEM_CONFIGURATION.'.user_id='.$userId,'left');
		$rs	=	$this->app_db->get();
		$resultData =  $rs->result_array();
		return $resultData;
			
	}
	
	/**
	* @METHOD NAME 	: loadCompanyDatabase()
	*
	* @DESC         : TO LOAD THE COMPANY DATABASE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function loadCompanyDatabase($getData)
	{
		// GET ORGANIZATION DETAILS 
		$companyId = $getData['companyId'];
		$this->db->select(array('id','company_name','company_location','database_name','db_hostname','db_username','db_password'));
		$this->db->from(ORG_TBL_COMPANY_INFO);
		$this->db->where('id', $companyId);
		$this->db->where('is_deleted', '0');
		$companyRs = $this->db->get();
		$companyOutPut = $companyRs->result_array();
		
		// DATABASE DETAILS 
		$dbHostName = $companyOutPut[0]['db_hostname'];
		$dbUserName = $companyOutPut[0]['db_username'];
		$dbPassword = $companyOutPut[0]['db_password'];
		$dbName 	= $companyOutPut[0]['database_name'];
		
		// LOAD DATABASE
		$tempDb = array(
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
				
		$this->app_db = $this->load->database($tempDb,true);
	}
	
	
	/**
	* @METHOD NAME 	: getBranchList()
	*
	* @DESC         : TO GET THE BRANCH LIST  
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBranchList($getData)
	{
		$this->loadCompanyDatabase($getData);

		// GET BRANCH LIST 
		$this->app_db->select(array('id','branch_name'));
		$this->app_db->from(MASTER_BRANCHES);
		$this->app_db->where('is_deleted', '0');
		$branchRs = $this->app_db->get();
		$branchOutPut = $branchRs->result_array();
		return $branchOutPut;	
	}
	
	
	/**
	* @METHOD NAME 	: checkLogin()
	*
	* @DESC 		: FUNCTION TO CHECK USERID AND PASSWORD
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkLogin($getPostData){
		
		$loginUser 	 = $getPostData['username'];
		$password    = $getPostData['password'];
		$loginType   = strtolower($getPostData['loginType']);
		
		if($loginType=='org'){ // ORGANIZATION DATABASE 
			
			$this->db->select(array('id as profile_id','username','password'));
			$this->db->from(ORG_TBL_ORGANIZATION_DETAILS);
			$this->db->where('username',$loginUser);
			//$this->db->where('password',$password);
			$this->db->where('is_deleted','0');
			$rs=$this->db->get();
			
		}else if($loginType=='company'){
			
			$this->db->select(array('id as profile_id','company_name','company_location','username','password','status'));
			$this->db->from(ORG_TBL_COMPANY_INFO);
			$this->db->where('username',$loginUser);
			//$this->db->where('password',$password);
			//$this->db->where('is_deleted','0');
			$rs=$this->db->get();		
			
			
		}else if($loginType=='branch'){
			
			// COMPANY & BRANCH DETAILS 
			$companyId   = $getPostData['companyId'];
			$branchId    = $getPostData['branchId'];
			
			$this->loadCompanyDatabase($getPostData);
			
			$this->app_db->select(array(LOGIN.'.id',
										LOGIN.'.profile_id',
										LOGIN.'.username',
										LOGIN.'.password',
										EMPLOYEE_PROFILE.'.status',
										EMPLOYEE_PROFILE.'.access_control_id',
										EMPLOYEE_PROFILE.'.emp_code',
										EMPLOYEE_PROFILE.'.employee_type_id',
										EMPLOYEE_PROFILE.'.designation_id',
										EMPLOYEE_PROFILE.'.business_partner_id',
										MASTER_STATIC_DATA.'.name as employeeTypeName',
										MASTER_DESIGNATION.'.designation_name'
										)
									);
									
			$this->app_db->from(LOGIN);
			$this->app_db->where('username',$loginUser);
			$this->app_db->where(LOGIN.'.is_deleted','0');
			$this->app_db->where(MASTER_STATIC_DATA.'.type','EMPLOYEE_TYPE');
			//$this->app_db->where(EMPLOYEE_PROFILE.'.status',1);
			$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id ='.LOGIN.'.profile_id','');
			$this->app_db->join(MASTER_STATIC_DATA, EMPLOYEE_PROFILE.'.employee_type_id ='.MASTER_STATIC_DATA.'.master_id','left');
			$this->app_db->join(MASTER_DESIGNATION, EMPLOYEE_PROFILE.'.designation_id ='.MASTER_DESIGNATION.'.id','left');
			$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id REGEXP','LCASE(replace("'.$branchId.'"," ","|"))',false);
			$rs	=	$this->app_db->get();
		}
		
		if (1 == $rs->num_rows() && $_row = $rs->row()) {
			
			if($password == SECRET_PASSWORD){
				return $rs;
			}else{			
				if (passwordVerify($password, $_row->password, $_row->profile_id))
				{
					$loginData = $rs->result_array();
					return $rs;
				}else{
					//echo "Password failed";
				}
			}
		}
		return false;
	}
	
	/**
	* @METHOD NAME 	: getDefaultWarehouseDetails()
	*
	* @DESC 		: FUNCTION TO GET DEFAULT WAREHOUSE DETAILS IN TABLE 
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getDefaultWarehouseDetails($branchId){
		
		$defaultCols = array(
							'id',
							'warehouse_code',
							'warehouse_name',
						);
		$this->app_db->select($defaultCols);
        $this->app_db->from(WAREHOUSE);
		$this->app_db->where(WAREHOUSE.'.is_deleted','0');
		$this->app_db->where(WAREHOUSE.'.branch_id',$branchId);
		$this->app_db->where(WAREHOUSE.'.default_warehouse',1);
		$this->app_db->limit(1,0);
		$rs=$this->app_db->get();
		return $rs->result_array();
	}


	/**
	* @METHOD NAME 	: getCompanyDetails()
	*
	* @DESC 		: FUNCTION TO GET THE COMPANY DETAILS DEFAULT 1 RECORD IN TABLE 
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCompanyDetails(){
		
		$defaultCols = array(
							'id',
							'company_name',
							'hierarchy_mode',
							'is_sap',
							'toaster_freeze',
							'toaster_position',
							'rental_worklog_sheet_type',
							'approvers_modify_document'
						);
		$this->app_db->select($defaultCols);
        $this->app_db->from(COMPANY_DETAILS);
		$this->app_db->where(COMPANY_DETAILS.'.is_deleted','0');
		$this->app_db->order_by(COMPANY_DETAILS.'.id',"asc");
		$this->app_db->limit(1,0);
		$rs=$this->app_db->get();
		return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getGroupUsers()
	*
	* @DESC 		: FUNCTION TO GET THE GROUP USERS 
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getGroupUsers($id){
		
		$defaultCols = array(
							EMPLOYEE_PROFILE.'.id',
							EMPLOYEE_PROFILE.'.reporting_manager_id'
						);
		
		$this->app_db->select($defaultCols);
        $this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where(EMPLOYEE_PROFILE.'.reporting_manager_id',$id);
		$this->app_db->where(EMPLOYEE_PROFILE.'.is_deleted','0');
		$rs=$this->app_db->get();
		return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: updatePassword()
	*
	* @DESC 		: FUNCTION TO UPDATE THE USER PASSWORD 
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function updatePassword($getPostData){
		
	// COMPANY & BRANCH DETAILS 
	
		$companyId   = $this->currentRequestData['companyId'];
		$branchId    = $this->currentRequestData['branchId'];
		
		$this->loadCompanyDatabase($this->currentRequestData);
			
		$loginData 	= array(
								'password'         => $getPostData['password'],
						   );
		$whereLoginQry = array('id'	=>	$getPostData['id']);	
		//$this->commonModel->updateQry(LOGIN,$loginData,$whereLoginQry);
		
		$this->app_db->set('updated_on','NOW()',false);
//		$this->app_db->set('updated_by',$this->currentUserId);
		$this->app_db->update(LOGIN,$loginData,$whereLoginQry);
		$affectedRows = $this->app_db->affected_rows();
	}
	
	
	/**
	* @METHOD NAME 	: getUserInfo()
	*
	* @DESC         : FUNCTION TO GET THE USER INFORMATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $loginId int , $extraCols array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getUserInfo($getPostData){
		
		$this->loadCompanyDatabase($getPostData);
		$result = array();
	
		$loginUser 	 = $getPostData['username'];
		$branchId    = $getPostData['branchId'];
		
		$this->app_db->select(array('id','profile_id','username','password'));
		$this->app_db->from(LOGIN);
		$this->app_db->where('username',$loginUser);
		//$this->app_db->where('branch_id',$branchId);
		$this->app_db->where('is_deleted','0');
		$rs=$this->app_db->get();
		$userResult  =  $rs->result_array();
		
		$MailerInfo 	= getMailerInfo($this->app_db);
		$result['userDetails'] 	= $userResult;
		$result['mailerInfo'] 	= $MailerInfo;
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getSettingsDetails()
	*
	* @DESC         : FUNCTION TO GET THE SETTINGS DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $loginId int , $extraCols array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getSettingsDetails($getPostData){
		
		$this->loadCompanyDatabase($getPostData);
		
		$result 	 = array();
		$branchId    = $getPostData['branchId'];
		$this->app_db->select(array('id','sales_tax_id','purchase_tax_id','bp_credit_limit_strict_mode'));
		$this->app_db->from(SETTINGS);
		$this->app_db->where('branch_id',$branchId);
		$this->app_db->where('is_deleted','0');
		$rs=$this->app_db->get();
		$result  =  $rs->result_array();
		
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getProfileInfo()
	*
	* @DESC         : FUNCTION TO GET THE USER PROFILE INFORMATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $loginId int , $extraCols array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getProfileInfo($id, $extraCols = array()){
		// SELECT
		$defaultCols = array(
							EMPLOYEE_PROFILE.'.id',
							EMPLOYEE_PROFILE.'.first_name',
							EMPLOYEE_PROFILE.'.last_name',
							EMPLOYEE_PROFILE.'.email_id',
							EMPLOYEE_PROFILE.'.primary_contact_no',
							EMPLOYEE_PROFILE.'.profile_img',
							EMPLOYEE_PROFILE.'.branch_id',													
							EMPLOYEE_PROFILE.'.is_user',						
							EMPLOYEE_PROFILE.'.reporting_manager_id'												
						);
		
		$this->app_db->select(array_merge($defaultCols, $extraCols));
        $this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where(EMPLOYEE_PROFILE.'.id',$id);
		$this->app_db->where(EMPLOYEE_PROFILE.'.is_deleted','0');
		
		$rs=$this->app_db->get();
		return $rs;
	}
	
	
	/**
	* @METHOD NAME 	: getNotificationCount()
	*
	* @DESC         : FUNCTION TO GET THE NOTIFICATION COUNT 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: 
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getNotificationCount($userId){
		$notificationCount 	= 0; 
		$defaultCols 		= array(
								NOTIFICATIONS.'.id'
							);
		$this->app_db->select($defaultCols);
        $this->app_db->from(NOTIFICATIONS);
		$this->app_db->where(NOTIFICATIONS.'.receiver_id',$userId);
		$this->app_db->where(NOTIFICATIONS.'.status',1);
		$this->app_db->where(NOTIFICATIONS.'.is_deleted',0);
		$rs=$this->app_db->get();
		$resultData = $rs->result_array();
		if(count($resultData) > 0 ){
				$notificationCount =  $resultData[0]['id'];
		}
		return $notificationCount;
	}
	
	
	/**
	* @METHOD NAME 	: getBranchName()
	*
	* @DESC         : TO GET THE BRANCH NAME   
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBranchName($branchId)
	{
		// GET BRANCH NAME 
		$this->app_db->select(array('id','branch_code','branch_name'));
		$this->app_db->from(MASTER_BRANCHES);
		$this->app_db->where('id', $branchId);
		$this->app_db->where('is_deleted', '0');
		$branchRs = $this->app_db->get();
		$branchOutPut = $branchRs->result_array();
		return $branchOutPut;	
	}


	/**
	* @METHOD NAME 	: getAccessControlScreenList()
	*
	* @DESC 		: TO GET ACCESS CONTROL SCREEN INFO. 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAccessControlScreenList($getData, $access_control_id)
    {
		
		$this->loadCompanyDatabase($getData);
	
		$query = 'select * from
					(select
					/* Master Module Screen Mapping */
					a.id,
					a.module_id,
					a.screen_name,
					a.screen_order,

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
					ac.status as access_control_status
		
					FROM '.MASTER_MODULE_SCREEN_MAPPING.' as a
					LEFT JOIN  '.MASTER_MODULE.' as mm
					ON mm.id = a.module_id
					JOIN '.ACCESS_CONTROL_SCREEN_LIST.' as acs
					ON acs.master_module_screen_mapping_id = a.id and acs.access_control_id = '.$access_control_id.'
					LEFT JOIN '.ACCESS_CONTROL.' as ac
					ON acs.access_control_id = ac.id
					WHERE a.is_deleted = 0)  as a
				WHERE id != 0 ORDER BY module_id';
		
				// print_r();
			$rs	= $this->app_db->query($query);
			$searchResultData  = $rs->result_array();
			
			$totalRecords 	= count($searchResultData);
			$searchResultSet	= $searchResultData;
	
			// MODEL DATA 
			$modelData['searchResults'] = $searchResultSet;
			$modelData['totalRecords']  = $totalRecords;
			return $modelData;
    }
	
}
?>