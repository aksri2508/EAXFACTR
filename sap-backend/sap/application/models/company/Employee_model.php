<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Employee_model.php
* @Class  			 : Employee_model
* Model Name         : Employee_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Employee_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'EMPLOYEE_PROFILE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: bindValues()
	*
	* @DESC 		: TO BIND THE VALUES 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function bindValues($tableName,$getPostData){
		
		$rowData = array();

		if($tableName=='LOGIN'){
			$rowData = array(
							'username'          => $getPostData['emailId'],
							'password'          => $getPostData['password'],
							'profile_id'		=> $getPostData['profile_id']
						);
		}
		return $rowData;
	}
	
	
	/**
	* @METHOD NAME 	: checkEmployeeCount()
	*
	* @DESC 		: TO CHECK THE EMPLOYEE COUNT 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkEmployeeCount($flag,$getPostData)
    {
		// EMPLOYEE COUNT 
		$this->app_db->select(array('id',
									)
							  );
        $this->app_db->from(EMPLOYEE_PROFILE);
        $this->app_db->where('is_deleted', '0');
        $this->app_db->where('is_user', 1);
		
		if($flag==2) { // UPDATE FUNCTIONALITY
			$this->app_db->where('id!=', $getPostData['id']);
		}
        $rs = $this->app_db->get();
        $empRecordsCount  = $rs->num_rows();
		
		
		// GET COMPANY EMPLOYEE COUNT 
		$this->app_db->select(array('id',
									'total_users_count')
							);
							
        $this->app_db->from(COMPANY_DETAILS);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $companyDetails = $rs->result_array();
		
		//printr($companyDetails);
		
		if($empRecordsCount < $companyDetails[0]['total_users_count']){
			return 1; // 
		}else {
			return 0;
		}
	}
	
	
	/**
	* @METHOD NAME 	: saveEmployee()
	*
	* @DESC 		: TO SAVE THE EMPLOYEE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveEmployee($getPostData)
    {
		// FOR CUSTOMER ADDING ADDITIONAL PARAMETERS 
		if($getPostData['employeeTypeId'] == 3) { // Customer 
			$getPostData['isUser']  = 1;
			$getPostData['accessControlId']  = 2;
		}else if($getPostData['employeeTypeId'] == 5){ // Dealer 
			$getPostData['isUser']  = 1;
			$getPostData['accessControlId']  = 3; // FIXME
		}
		
		// BIND DATA
		$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
			
		// OPERATION DETAILS 
		if($getPostData['isUser']==1){
			if($this->checkEmployeeCount(1,$getPostData) == 0){
				$modelOutput['flag'] = 4;
				return $modelOutput;
			}
		}
		$modelOutput['sendRegMailFlag'] 	 = 0;
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(email_id)' => strtolower($getPostData['emailId']),
							);
		$chkEmailRecord 		= $this->commonModel->isExists(EMPLOYEE_PROFILE,$whereExistsQry);
		
		
		// CHECK EMPLOYEE CODE ALREADY EXISTS 
		$whereExistsQry = array(
							  'LCASE(emp_code)' => strtolower($getPostData['empCode']),
							);
		$chkEmpCodeRecord 		= $this->commonModel->isExists(EMPLOYEE_PROFILE,$whereExistsQry);
		
		
		if($chkEmailRecord !=0){
			 $modelOutput['flag'] = 2;
			 return $modelOutput;
		}else if ($chkEmpCodeRecord !=0){
			 $modelOutput['flag'] = 3;
			 return $modelOutput;
		}else {		
			$this->app_db->trans_start();
			
			$profileId = $this->commonModel->insertQry($this->tableName, $rowData);
			
			// Additional Info.
			$profileInfo 		 = $this->commonModel->getProfileInformation($profileId);
			$companyDetails	= $this->commonModel->getCompanyInformation();
			$branchName 	     = $profileInfo['profileInfo'][0]['branch_name'];
			$companyName 	     = $companyDetails[0]['company_name'];


			if($getPostData['isUser'] ==1){
				$genPassword 	= generatePassword();
				$password   	= passwordHash($genPassword); // Generate Password
				
				// LOGIN ARRAY FORMATION
				$loginData['emailId']	 =  $getPostData['emailId']; 				
				$loginData['password']	 =  $password; 				
				$loginData['profile_id'] =  $profileId; 				
					
				$loginId 		= $this->commonModel->insertQry(LOGIN, $this->bindValues('LOGIN',$loginData));

				$modelOutput['userId'] 	 = $profileId;
				$modelOutput['password'] = $genPassword;
				$modelOutput['companyName'] = $companyName;
				$modelOutput['branchName'] 	= $branchName;
				$modelOutput['sendRegMailFlag'] 	 = 1;
			}
			$this->app_db->trans_complete();
			$modelOutput['sId']	 = $profileId;

			$modelOutput['flag'] = 1;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateEmployee()
	*
	* @DESC 		: TO UPDATE THE EMPLOYEE DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateEmployee($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		
		//printr($getPostData);
		
		$modelOutput['sendRegMailFlag'] 	 = 0;
		
		$this->app_db->trans_start();
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
								 'LCASE(email_id)' => strtolower($getPostData['emailId']),
								 'id!='			   => $getPostData['id'],
								);	
		
		$chkRecord 		= $this->commonModel->isExists(EMPLOYEE_PROFILE,$whereExistsQry);
		
		
        if(0 == $chkRecord) {
			
			// GET EXISTING RECORD OF THE EMPLOYEE 
			$profileInfo 		 = $this->commonModel->getProfileInformation($getPostData['id']);
			$companyDetails	= $this->commonModel->getCompanyInformation();
			
			$profileId 			 = $profileInfo['profileInfo'][0]['profile_id'];
			$existingIsUser 	 = $profileInfo['profileInfo'][0]['is_user'];
			$branchName 	     = $profileInfo['profileInfo'][0]['branch_name'];
			$companyName 	     = $companyDetails[0]['company_name'];


			
			$whereQry = array('id'	=>	$getPostData['id']);			
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			
			
			// UPDATE TO LOGIN TABLE
			if(!empty($profileId)){
				$loginData 		= array(
									'username' => $getPostData['emailId']
								  );
								
				$whereLoginQry = array('profile_id'	=>	$profileId);	
				$this->commonModel->updateQry(LOGIN,$loginData,$whereLoginQry);
			}
		
			
			if($getPostData['employeeTypeId'] == 3){
				$getPostData['isUser'] = 1;
			}

			if($existingIsUser!=$getPostData['isUser']){
												
				if($getPostData['isUser']==1 && empty($profileId)){
					
					if($this->checkEmployeeCount(2,$getPostData) == 0){
						$modelOutput['flag'] = 4;
						return $modelOutput;
					}
					
					$genPassword 	= generatePassword();
					$password   	= passwordHash($genPassword); // Generate Password
					
					// LOGIN ARRAY FORMATION
					$loginData['username']	 =  $getPostData['emailId']; 				
					$loginData['password']	 =  $password;				
					$loginData['profile_id'] =  $profileId;
					$loginId 			  	 = $this->commonModel->insertQry(LOGIN, $this->bindValues('LOGIN',$loginData));
					
					$modelOutput['userId'] 				 = $getPostData['id'];
					$modelOutput['password'] 			 = $genPassword;
					$modelOutput['companyName'] 		 = $companyName;
					$modelOutput['branchName'] 			 = $branchName;
					$modelOutput['sendRegMailFlag'] 	 = 1;
				}
			}
			$this->app_db->trans_complete();
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editEmployee()
	*
	* @DESC 		: TO EDIT THE EMPLOYEE DETAILS
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editEmployee($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','bin_code','bin_name','status'));
        $this->app_db->select($rowData);
		/*
        $this->app_db->select(array('id',
									'emp_code',
									'gender_id',
									'first_name',
									'last_name',
									'email_id',
									'primary_country_code',
									'primary_contact_no',
									'status',
									'branch_id',
									'designation_id',
									'reporting_manager_id',
									'is_user',						
									'profile_img',
									'system_user',
									'distribution_rules_id')									
							);
		*/					
        $this->app_db->from(EMPLOYEE_PROFILE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	
	
    /**
	* @METHOD NAME 	: getEmployeeList()
	*
	* @DESC 		: TO GET THE EMPLOYEE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getEmployeeList($getPostData,$downloadFlag='')
    {
       $query = 'SELECT * FROM
					(
					SELECT
						a.id,
						a.emp_code,
						a.gender_id,
						CONCAT(a.first_name," ",a.last_name) as emp_name,
						a.email_id,
						a.primary_country_code,
						a.primary_contact_no,
						a.status,
						a.branch_id,
						a.employee_type_id,
						a.business_partner_id,
						a.reporting_manager_id,
						a.distribution_rules_id,
						a.is_user,
						a.profile_img,
						a.system_user,
						a.updated_on,
						a.sap_id,
						a.posting_status,
						a.sap_error,
						a.created_by,
					    CONCAT(cep.first_name," ",cep.last_name) as created_by_name,
						
						/* Master Activity */
						msd.name as status_name,
						msd1.name as employee_type_name,
						
												
						/* SAME EMPLOYEE PROFILE TABLE */
						CONCAT(ep.first_name," ",ep.last_name) as reporting_manager_name,

						/* Business Partner */
						bp.partner_name as business_partner_name
						
						FROM '.EMPLOYEE_PROFILE.' as a 
						
					LEFT JOIN (select * from '.MASTER_STATIC_DATA.' where type="COMMON_STATUS") as msd
						ON msd.master_id = a.status
						
					
					LEFT JOIN (select * from '.MASTER_STATIC_DATA.' where type="EMPLOYEE_TYPE") as msd1
						ON msd1.master_id = a.employee_type_id

						LEFT JOIN '.BUSINESS_PARTNER.' as bp
						ON bp.id = a.business_partner_id
					
					LEFT JOIN '.EMPLOYEE_PROFILE.' as ep
						ON ep.id = a.reporting_manager_id

					LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
						ON cep.id = a.created_by
					
					WHERE a.is_deleted = 0
						AND a.branch_id REGEXP LCASE(replace("'.$this->currentbranchId.'"," ","|"))
						
					) AS a 	
					WHERE id!=0';
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                 if ($fieldValue!="") {
					if($fieldName=="empName"){
						$query.=' AND LCASE(CONCAT(emp_code," ",emp_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="employeeTypeName"){
						$query.=' AND LCASE(employee_type_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="emailId"){
						$query.=' AND LCASE(email_id) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="empContactNo"){
						$query.=' AND LCASE(CONCAT(primary_country_code," ",primary_contact_no)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="status"){
						$query.=' AND status = "'.$fieldValue.'"';	
					}
					else if ($fieldName == "employeeTypeId") {
						$query .= ' AND employee_type_id = "' . $fieldValue . '"';
					}
					else if($fieldName=="businessPartnerName"){
						$query.=' AND LCASE(business_partner_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} 
					else if($fieldName=="createdByName"){
						$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('EMPLOYEE_PROFILE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
			}
			
        }else{
			$query.= ' ORDER BY updated_on desc';
		}
        
        // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
		$rs = $this->app_db->query($query);
		$totalRecords =$rs->num_rows();
        
        // PAGINATION
		if(empty($downloadFlag)){
			if (isset($tableProperties['first'])) {
				$offset = $tableProperties['first'];
				$limit  = $tableProperties['rows'];
			} else {
				$offset = 0;
				$limit  = $tableProperties['rows'];
			}
			$query.=' LIMIT '.$offset.','.$limit;
        }
		
		
		// GET RESULTS 		
        $searchResultSet = $this->app_db->query($query);
        $searchResultSet = $searchResultSet->result_array();
		
				
		foreach($searchResultSet as $key => $value){
			
			// DISTRIBUTION DETAILS 
			$distributionRulesDetails	= array();
			$distributionRulesId  		= $value['distribution_rules_id'];
			$distributionRulesArray  	= explode(",", $distributionRulesId);
			$distributionRulesValues	= '';

			if (count($distributionRulesArray) > 0) {
				foreach ($distributionRulesArray as $distributionKey => $distributionValue) {
					if (!empty($distributionValue)) {
						$getDistributionRulesInfo =  array(	
														'getDistributionRulesList' 	 => $distributionValue,
													);
													
						$distributionStatusInfoDetails	= getAutoSuggestionListHelper($getDistributionRulesInfo);	
						
						if (is_array($distributionStatusInfoDetails) && count($distributionStatusInfoDetails) > 0) {
							$distributionRulesDetails[$distributionKey]	= $distributionStatusInfoDetails['distributionRulesInfo'][0];
							$distributionRulesValues.= $distributionStatusInfoDetails['distributionRulesInfo'][0]['distribution_name'].",";
						}
					}
				}
			}
			
			
			// BRANCH DETAILS
			$branchDetails 		= array();
			$branchId  			= $value['branch_id'];
			$branchArray  		= explode(",",$branchId);
			$branchInfoValues	= "";
			
			if(count($branchArray)>0){
					$cnt = 0;
					
					foreach($branchArray as $branchKey => $branchValue){
						
						$getBranchDetailsInfo = array(	
														'getBranchList' 	 => $branchValue,
													);
													
						$branchDetailsResult  = getAutoSuggestionListHelper($getBranchDetailsInfo);	
						
						if(!empty($branchDetailsResult['branchInfo']))
						{	
							$branchDetails[$cnt] =  $branchDetailsResult['branchInfo'][0];
							$cnt++;
							//$branchInfoValues.= $branchDetails[0]['branch_name'].",";
							$branchInfoValues.= $branchDetails[0]['branch_name'].",";
						}
						
					}
			}
			
			// IS APPLICATION USER 
			$isUser = $value['is_user'];
			$isUserValue = "";
			
			if($isUser == 1 ){
				$isUserValue = 'Yes';
			}else{
				$isUserValue = 'No';
			}	
			
			$searchResultSet[$key]['is_user_value'] 				= $isUserValue;
			$searchResultSet[$key]['branch_info_values'] 			= rtrim($branchInfoValues,",");
			$searchResultSet[$key]['branch_info'] 					= $branchDetails;
			$searchResultSet[$key]['distribution_rules_info'] 		= $distributionRulesDetails;	
			$searchResultSet[$key]['distribution_rules_values'] 	= rtrim($distributionRulesValues,",");
			$searchResultSet[$key]['user_img_url'] 					= getFullImgUrl('employee',$value['profile_img']);
		}
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getAnalyticsCount()
	*
	* @DESC 		: TO ANALYTICS FOR ACTIVITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAnalyticsCount($typeId)
    {
		$this->app_db->select('COUNT(*) as total');
		$this->app_db->from(EMPLOYEE_PROFILE);
		
			if(!empty($typeId)){
				$this->app_db->where('employee_type_id',$typeId);
			}
			
		$this->app_db->where('is_deleted',0);
		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();		
		return $total = $resultData[0]['total'];
    }
	
}
?>