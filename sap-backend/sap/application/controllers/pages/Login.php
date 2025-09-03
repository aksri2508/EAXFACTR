<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Login.php
* @Class  			 : Login
* Model Name         : Login
* Description        :
* Module             : pages
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 
* @LastModifiedDate  : 
* @LastModifiedBy    : 
* @LastModifiedDesc  : 
* Features           : 
*/
class Login extends MY_Controller
{
    protected $user     = [];
    protected $loginId  = 0;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pages/login_model', 'nativeModel');
		//abcdef // $2y$14$NSGY8ehD3QZsqXLafhR8b.dGydhfiQwjVHUU7QXG9gTi/LIzehfUG
		$this->module = '';
    }
	
	
	/**
	* @METHOD NAME 	: checkOrganizationExpiry()
	*
	* @DESC         : TO CHECKTHE ORGANIZATION EXPIRY DEAILS 
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkOrganizationExpiry($mobDomainName= '')
    {
        $this->checkRequestMethod("get"); // Check the Request Method
		$outputData['status']  = "FAILURE";

			$getData 		   = $this->currentRequestData;
			$modelOutput 	   = $this->nativeModel->checkOrganizationExpiry($mobDomainName);

			if($modelOutput['flag']==2){
				$outputData['status']       = "FAILURE";
			}else{
				$outputData['status']       = "SUCCESS";
			}
			
			//$outputData['results']      = $modelOutput;
			
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getOrgCompanyInfo()
	*
	* @DESC         : TO GET THE COMPANY DETAILS OF THE ORGANIZATION 
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCompanyList($mobDomainName= '')
    {
        $this->checkRequestMethod("get"); // Check the Request Method
		$outputData['status']  = "FAILURE";

			$getData 		   = $this->currentRequestData;
			$modelOutput 	   = $this->nativeModel->getCompanyList($mobDomainName);

			$outputData['results']      = $modelOutput;
			$outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getBranchList()
	*
	* @DESC         : TO GET THE BRNACH DETAILS OF THE ORGANIZATION 
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBranchList()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";

			$getData 		   = $this->currentRequestData;
			$modelOutput 	   = $this->nativeModel->getBranchList($getData);
			
			$outputData['results']      = $modelOutput;
			$outputData['status']       = "SUCCESS";

        $this->output->sendResponse($outputData);
    }
	
	
    /**
	* @METHOD NAME 	: checkLogin()
	*
	* @DESC         : TO CHECK THE LOGIN CREDENTIALS
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function checkLogin()
    {
	//	echo passwordHash("123456");	exit;
		$outputData = ['status' => 'FAILURE'];
		
        $this->checkRequestMethod("post"); // Check the Request Method
		
		$loginType 		= $this->currentRequestData['loginType'];
		$loginCategory  = $this->config->item('login_category');
		$this->module = $loginCategory[$loginType];
		
		if(!array_key_exists($loginType,$loginCategory)){
			$outputData['message'] = lang('MSG_221');
			$this->output->sendResponse($outputData);
			return false;
		}
		
			if($this->currentRequestData['loginType'] == 'branch'){
				
				
				$resultSet  = $this->nativeModel->checkLogin($this->currentRequestData);
				
				// IS VALID USERNAME & PASSWORD
				if (!$resultSet) {
					$outputData['message'] = lang('MSG_02');
					$this->output->sendResponse($outputData);
					return false;
				}
				
				$loginDetails  		 = $resultSet->result_array();
				$userStatus 		 = $loginDetails[0]['status'];
				 
				if($userStatus!=1){
					$errMessage  = "";
					if($userStatus==2){ // INACTIVE USER ERROR MESSAGE
						$errMessage  = lang('MSG_142');
					}	
					$outputData['message'] = $errMessage;
					$this->output->sendResponse($outputData);
					return false;
				}	
				
			   
				$this->profileId	 = $loginDetails[0]['profile_id'];
				$this->user['id']    = $this->profileId;
						   
				
				$profileResultSet = $this->nativeModel->getProfileInfo($this->profileId);
				//Has valid profile?
				if (0 == $profileResultSet->num_rows()) {
					$outputData['message'] = 'Profile Not Found. Please contact administrator.';
					$this->output->sendResponse($outputData);
					return false;
				}
				
				$profile = $profileResultSet->row_array();
						
				if($profile['is_user']==0){
					$outputData['message'] = "Access Denied for this user. Please contact administrator";
					$this->output->sendResponse($outputData);
					return false;
				}
				
				$this->user['userName']     	= $profile['first_name']." ".$profile['last_name'];
				$this->user['profileImgUrl']    = getFullImgUrl('employee',$profile['profile_img']);
				

				// COMPANY AND BRANCH DETAILS 
				$orgTblCompanyId = "";
				$branchId  = "";
				
				if(isset($this->currentRequestData['companyId'])){
					$orgTblCompanyId = $this->currentRequestData['companyId'];
				}
				
				if(isset($this->currentRequestData['branchId'])){
					$branchId = $this->currentRequestData['branchId'];
				}
				
				// GET THE LIST OF BRANCH INFORMATION FOR THE USER 
				$branchDetails 		= array();
				$profileBranchId  	= $profile['branch_id'];
				$branchArray  		= explode(",",$profileBranchId);
			
				if(count($branchArray)>0){
						$cnt = 0;
						foreach($branchArray as $branchKey => $branchValue){
							
							$branchDetailsResult  = $this->nativeModel->getBranchName($branchValue);	
							
							if(!empty($branchDetailsResult[0]))
							{	
								$branchDetails[$cnt] =  $branchDetailsResult[0];
								$cnt++;
							}
						}
				}
			
				// GROUP USERS  
				$groupUsersResultSet	= $this->nativeModel->getGroupUsers($this->profileId);
				$groupUsers  = array();
				if(count($groupUsersResultSet)>0){
					$groupUsers  = array_column($groupUsersResultSet, 'id');
				}
				
				$groupUsers[] =  $this->profileId;
			
			
				// GET COMPANY DETAILS FROM OWN DATABASE TO GET THE HIRARCHY
				$getCompanyResult			   = $this->nativeModel->getCompanyDetails();
				$this->hierarchyMode 		   = $getCompanyResult[0]['hierarchy_mode'];
				$isSapFlag 					   = $getCompanyResult[0]['is_sap'];
				$this->rentalWorklogSheetType  = $getCompanyResult[0]['rental_worklog_sheet_type'];
				$this->approversModifyDocument = $getCompanyResult[0]['approvers_modify_document'];
				
				// GET THE SETTINGS DETAILS OF THE BRANCH 
				$getSettingsResult		= $this->nativeModel->getSettingsDetails($this->currentRequestData);
				
				// SETTINGS ARRAY 
				$this->settings = array(
									'salesTaxId'	 => 0,
									'purchaseTaxId'  => 0,
									'bpCreditLimitStrictMode'  => 0,
								);
				
				if(count($getSettingsResult) > 0 ){
					$this->settings = array(
									'salesTaxId'	 => $getSettingsResult[0]['sales_tax_id'],
									'purchaseTaxId'  => $getSettingsResult[0]['purchase_tax_id'],
									'bpCreditLimitStrictMode'  => $getSettingsResult[0]['bp_credit_limit_strict_mode'],
								);
				}
				
				// GET THE LINE ITEM CONFIGURATION 
				$getLineItemConfig	 =  $this->nativeModel->getLineItemConfigDetails($this->profileId);	
		
				// TOKEN DETAILS 
				$token = [
							'id'  					 => $this->user['id'],
							'companyId' 			 => $orgTblCompanyId,
							'branchId'  			 => $branchId,
							'iat' 				 	 => time(),
							'groupUsers'			 => array_values($groupUsers),
							//'roleIdentifier'		 => $this->user['roleIdentifier'],
							'currentAccessControlId' => $loginDetails[0]['access_control_id'],
							'hierarchyMode' 		 => $this->hierarchyMode,
							'module'				 => $this->module,
							'userBranchIds'  		 => $profileBranchId,
							'rentalWorklogSheetType' => $this->rentalWorklogSheetType,
							'approversModifyDocument' => $this->approversModifyDocument,
							'employeeType'			  => $loginDetails[0]['employeeTypeName'],
							'customerBusinessPartnerId' => $loginDetails[0]['business_partner_id'],
							'dealerBusinessPartnerId' => $loginDetails[0]['business_partner_id'],
							//'isSap'		 	 => $isSapFlag,
							//'settings'		 => $this->settings,
				//	        'exp' => '',   // Token Expired time  // 1356999524
							

						 ];

				$this->user['token']    = JWT::encode($token, $this->config->item('jwt_key'));
				$this->user['identity'] = $this->getIdentificationToken();
				$this->user['branchId'] = $branchId;
				$defaultWarehouseDetails = $this->nativeModel->getDefaultWarehouseDetails($branchId);
				$defaultWarehouseId = "";
				if(isset($defaultWarehouseDetails[0]['id'])){
					$defaultWarehouseId = $defaultWarehouseDetails[0]['id'];
				}
				
				// GET NOTIFICATION COUNT 
				$notificationCount = $this->nativeModel->getNotificationCount($this->profileId);
				
				$this->user['notificationCount'] = $notificationCount;
				$this->user['defaultWarehouse'] = $defaultWarehouseId;
				$this->user['hierarchyMode'] = $this->hierarchyMode;
				$this->user['settings'] 	 = $this->settings;
				$this->user['isSap'] 		 = $isSapFlag;
				$this->user['toasterFreeze'] 	 = $getCompanyResult[0]['toaster_freeze'];
				$this->user['toasterPosition'] 	 = $getCompanyResult[0]['toaster_position'];
				$this->user['lineItemConfig'] 	 = $getLineItemConfig;
				$this->user['branchList']		 = $branchDetails;
				$this->user['worklogSheetType']  = $this->rentalWorklogSheetType;
				$this->user['employeeTypeName']  = $loginDetails[0]['employeeTypeName'];
				$this->user['designation_name']  = $loginDetails[0]['designation_name'];
				$this->user['emp_code']  		 = $loginDetails[0]['emp_code'];
				$this->user['employee_type_id']  		 = $loginDetails[0]['employee_type_id'];
				$this->user['business_partner_id']  		 = $loginDetails[0]['business_partner_id'];
				
				$accessControlInformation		 = $this->getAccessControlScreenList($loginDetails[0]['access_control_id']);	

				//printr($accessControlInformation);
				$this->user['access_control_data']  = isset($accessControlInformation['access_control_data']) 
														? $accessControlInformation['access_control_data'] : [];
				
				$outputData = [
					'status'  => 'SUCCESS',
					'message' => lang('MSG_01'),
					'results' => $this->user,
				];
				$this->output->sendResponse($outputData);
				
			}else if($this->currentRequestData['loginType'] == 'company'){
					$this->checkCompanyLogin();
			}
    }
		
		
	/**
	* @METHOD NAME 	: checkCompanyLogin()
	*
	* @DESC         : TO CHECK THE COMPANY LOGIN DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @Service      : WEB
	* @ACCESS POINT : -
	**/
	public function checkCompanyLogin()
    {
		
		$outputData = ['status' => 'FAILURE'];
		$resultSet  = $this->nativeModel->checkLogin($this->currentRequestData);
		
		// IS VALID USERNAME & PASSWORD
		if (!$resultSet) {
			$outputData['message'] = lang('MSG_02');
			$this->output->sendResponse($outputData);
			return false;
		}
		
		$loginDetails  		 = $resultSet->result_array();
		$userStatus 		 = $loginDetails[0]['status'];
		
		//echo "User status is:".$userStatus;
		
		if($userStatus!=1){
			$errMessage  = "";
			if($userStatus==2){ // INACTIVE USER ERROR MESSAGE
				$errMessage  = lang('MSG_142');
			}	
			$outputData['message'] = $errMessage;
			$this->output->sendResponse($outputData);
			return false;
		}	
		
		// FRAME FINAL RESULT IF THE RESULTS ARE SUCCESS
		$this->user['companyId']    = $loginDetails[0]['profile_id'];
		$this->user['companyName']  = $loginDetails[0]['company_name'];
		$this->user['userName']     = $loginDetails[0]['username'];
		
		// TOKEN DETAILS 
		$token = [
					'id'  			 => $this->user['companyId'],
					'companyId' 	 => $this->user['companyId'],					
					'iat' 			 => time(),
					'module'		 => $this->module,
					//	'groupUsers'	 => array_values($groupUsers),
					//	'roleIdentifier' => $this->user['roleIdentifier'],
					//	'hierarchyMode'  => $this->hierarchyMode,
					//	'exp' => '',   // Token Expired time  // 1356999524
				 ];

		$this->user['token']    = JWT::encode($token, $this->config->item('jwt_key'));
		$this->user['identity'] = $this->getIdentificationToken();
		
		$outputData = [
							'status'  => 'SUCCESS',
							'message' => lang('MSG_01'),
							'results' => $this->user,
					  ];
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: forgotPassword()
	*
	* @DESC         : FIND OUT THE USER FROM EMAIL OR PHONE NUMBER AND INITIATE THE RESET PROCESS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @Service      : WEB
	* @ACCESS POINT : -
	**/
    public function forgotPassword()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		
		$outputData['status']  	= "FAILURE";
        $modelData 			= $this->nativeModel->getUserInfo($this->currentRequestData);
		
		
			if (count($modelData['userDetails'])>0) {
				$outputData                 = $this->sendResetPasswordEmail($modelData);
			} else {
				$outputData['message']      = lang('MSG_33');
			}
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: sendResetPasswordEmail()
	*
	* @DESC         : SEND THE PASSWORD RESET MAIL TO USER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: $userDetails array
	* @Service      : WEB
	* @ACCESS POINT : -
	**/
    protected function sendResetPasswordEmail($getData) 
	{
	/*	
		$outputData = [
			'status'       => 'SUCCESS',
			'message'      => 'New Password Sent in mail.',
			//'results'      => ['resetType' => 2, 'url' => $resetUrl]
		];
*/
		
		//return $outputData;
		
		//printr($getData);
		
    	$mailerInfo  = $getData['mailerInfo'];
		$userDetails = $getData['userDetails'][0];
		
		$templateName = "forgotpassword_mail";
		$mailContent  = $this->config->item($templateName);
		$mailTitle	  = $mailContent['title'];
		
		// GET PROFILE INFORMATION 
		$profileResultSet 	= $this->nativeModel->getProfileInfo($userDetails['profile_id']);
		$profileInfo 		= $profileResultSet->row_array();
	//	printr($profileInfo);
		
		$name			 = $profileInfo['first_name']." ".$profileInfo['last_name'];
		$username		 = $userDetails['username'];
		
		// GENERATE PASSWORD 
		$genPassword 	= generatePassword();
		$password   	= passwordHash($genPassword);
		
		// BODY DATA MANIPULATION 
		$replaceBody = [
			'<<MAIL_CUR_DATE>>'     => date('D, d M Y'),
			'<<NAME>>'              => $name,
			'<<USERNAME>>'			=> $username,   //
			'<<PASSWORD>>'			=> $genPassword, 	// DEFAULT PASSWORD
		];
		$mailContent['body'] = str_replace(array_keys($replaceBody), $replaceBody, $mailContent['body']);

		// TEMPALTE FILE MANIPULATION
		$templateArray 		=  array(
										'USER_LANGUAGE' => $this->currentUserLanguage,
									);
		$mailerTemplateData = getMailerTemplate($templateArray);

		// OVER ALL TEMPLATE MANIPULATION
		$mailerData = str_replace("<<MAIL_TITLE>>",$mailTitle,$mailerTemplateData);
		$mailerData = str_replace("<<BODY_CONTENT>>",$mailContent['body'],$mailerData);
		$mailerData = str_replace("<<MAILIMGURL>>",MAILIMGURL,$mailerData);
		
		//echo $mailerData;
		//exit;
		
		// UPDATE NEW PASSWORD TO DATABASE 
		
		$passUpdatePwdData['password']  = $password;
		$passUpdatePwdData['id'] 		= $userDetails['id'];
		$this->nativeModel->updatePassword($passUpdatePwdData);
		
	
		$this->load->library('customemail');
		$this->customemail->sendemail_external(
			$userDetails['username'],
			$mailContent['subject'],
			$mailerData,
			$mailerInfo
		);

		$outputData = [
			'status'       => 'SUCCESS',
			'message'      =>'New Password Sent in mail.',
			//'results'      => ['resetType' => 2, 'url' => $resetUrl]
		];

		return $outputData;
    }

	
	/**
	* @METHOD NAME 	: getAccessControlScreenList()
	*
	* @DESC         : TO GET  ACCESS CONTROL SCREEN LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @Service      : WEB
	* @ACCESS POINT : -
	**/
    public function getAccessControlScreenList($accessControlId)
    {
         if (!empty($accessControlId) && is_numeric($accessControlId)) 
         {
			$getData 		   = $this->currentRequestData;
			$modelOutput 	   = $this->nativeModel->getAccessControlScreenList($getData, $accessControlId);

            $moduleData = array();
            $processData = array();
			
			//printr($modelOutput['searchResults']);	exit;

            // ReArranging data to Modulewise.
            foreach($modelOutput['searchResults'] as $sValue){				
				
				if(!isset($sValue['enable_view']) || $sValue['enable_view'] != 2){
					continue;
				}
                $processData[$sValue['module_id']][] = $sValue;
            }
			

            $access_control_name_id = null;
            $access_control_name = null;
            $access_control_status = null;
			
			
			
            // Process Data.
            foreach($processData as $mKey => $mValue){

                $screenDataList = array();

                foreach($mValue as $sValue){
					
					

                    $assignData= array();
                    $assignData = $sValue;
                    unset($assignData['module_id']);
        
                    $screenData = array();
                    $screenData['access_control_list_id'] = $assignData['access_control_list_id'];
                    $screenData['screenId'] = $assignData['screen_id'];
                    $screenData['screen_name'] = $assignData['screen_name'];
                    $screenData['screen_order'] = $assignData['screen_order'];
                    $screenData['enable_view'] = $assignData['enable_view'];
                    $screenData['enable_add'] = $assignData['enable_add'];
                    $screenData['enable_update'] = $assignData['enable_update'];
                    $screenData['enable_download'] = $assignData['enable_download'];

                    if(isset($assignData['access_control_name_id']) && 
                    ($assignData['access_control_name_id'] != "" || 
                    $assignData['access_control_name_id'] != null)){
                        $access_control_name_id = $assignData['access_control_name_id'];
                        $access_control_name = $assignData['access_control_name'];
                        $access_control_status = $assignData['access_control_status'];
                    }
                    $screenDataList[] = $screenData;
                }

                $mData = array();
                $mData['module_id'] = $mValue[0]['module_id'];
                $mData['module_name'] = $mValue[0]['module_name'];
                $mData['module_order'] = $mValue[0]['module_order'];
                $mData['screenList'] = $screenDataList;
                $moduleData[] = $mData;
            }
			
            $returnData['access_control_name_id'] = $access_control_name_id;
            $returnData['access_control_name'] = $access_control_name;
            $returnData['access_control_status'] = $access_control_status;
            $returnData['access_control_data'] = $moduleData;
			return $returnData;			
		}else{
			return [];
		}
    }
	
	
}
?>