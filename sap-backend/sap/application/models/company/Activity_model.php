<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Activity_model.php
* @Class  			 : Activity_model
* Model Name         : Activity_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 19 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Activity_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'ACTIVITY';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveActivity()
	*
	* @DESC 		: TO SAVE THE ACTIVITY DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveActivity($getPostData)
    {	
	    // Transaction Start
	    $this->app_db->trans_start();
		   
		$rowData 				= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$rowData['branch_id'] 	= $this->currentbranchId;
		$rowData['distribution_rules_id'] 	= arrangeDistributionRulesData($getPostData['distributionRulesId']);
		$insertId 							= $this->commonModel->insertQry($this->tableName, $rowData);
		   
		if($insertId>0){
			$whereQry					= array('id'=>$insertId);
			$updateData['activity_no']  = $insertId;
			$this->commonModel->updateQry($this->tableName,$updateData,$whereQry);
			
			$this->app_db->trans_complete();
			
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1;
		}else{
			$modelOutput['flag'] = 3;
		}
       
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateActivity()
	*
	* @DESC 		: TO UPDATE THE ACTIVITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateActivity($getPostData)
    {
		$whereQry			  = array('id'=>$getPostData['id']);
		$rowData 			  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$rowData['branch_id'] = $this->currentbranchId;
		$rowData['distribution_rules_id'] 	= arrangeDistributionRulesData($getPostData['distributionRulesId']);
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1;
		return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editActivity()
	*
	* @DESC 		: TO EDIT THE ACTIVITY
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editActivity($getPostData)
    {
		$rowData   = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$rowData[] = ACTIVITY.".distribution_rules_id";
		$rowData[] = 'datediff(DATE('.ACTIVITY.'.start_date_time),DATE(NOW())) as over_due_value';	
		$this->app_db->select($rowData);
		$this->app_db->from(ACTIVITY);		
		$this->app_db->where(ACTIVITY.'.id', $getPostData['id']);
        $this->app_db->where(ACTIVITY.'.is_deleted', '0');
        $this->app_db->where_in('branch_id', explode(",",$this->currentUserBranchIds));
		
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
    /**
	* @METHOD NAME 	: getActivityList()
	*
	* @DESC 		: TO GET THE ACTIVITY LIST DETAILS 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getActivityList($getPostData,$downloadFlag='')
    {
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
		
		$query = 'select * from
					(select	
						a.id, 
						a.activity_no,
						a.activity_type_id,
						a.assigned_to_id,
						a.priority_id,
						a.status,
						a.start_date_time,
						a.end_date_time,
						a.business_partner_id,
						a.recurrence_end_date,
						a.remarks,
						a.distribution_rules_id,
						datediff(DATE(a.start_date_time),DATE(NOW())) as over_due_value,
						a.created_by,
						a.created_on,
						a.updated_on,
						a.document_type_id,
						a.document_id,
						a.bp_contacts_id,
						a.reminder_before_time,
						a.reminder_type_id,
						a.sap_id,
						a.posting_status,
						a.sap_error,
						a.branch_id,
						CONCAT(cep.first_name," ",cep.last_name) as created_by_name,

						 /* Master STATIC DATA */
						msd.name as document_type_name,
					
						/* Master Activity */
						ma.activity_name,	
						
						 /* Master STATIC DATA -1  */
						msd1.name as reminder_type_name,
						msd2.name as priority_name,
						msd3.name as status_name,
						
						/* Employee master */
						CONCAT(ep.first_name," ",ep.last_name) as assigned_to_name,
						ep.emp_code as assigned_to_code,
						ep.profile_img,
						
						/* Business partner */
						bp.partner_name,								
						bp.partner_code,
						
						/* BRANCH INFORMATION */
						mb.branch_code,
						mb.branch_name
									
					FROM '.ACTIVITY.' as a
					LEFT JOIN '.MASTER_ACTIVITY.' as ma 
						ON ma.id = a.activity_type_id
					LEFT JOIN '.EMPLOYEE_PROFILE.' as ep 
						ON ep.id = a.assigned_to_id
					LEFT JOIN '.BUSINESS_PARTNER.' as bp
						ON bp.id = a.business_partner_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "DOCUMENT_TYPE") as msd
						ON msd.master_id = a.document_type_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "ACTIVITY_REMINDER_TYPE") as msd1
						ON msd1.master_id = a.reminder_type_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "ACTIVITY_PRIORITY_TYPE") as msd2
						ON msd2.master_id = a.priority_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "ACTIVITY_STATUS") as msd3
						ON msd3.master_id = a.status
					LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
						ON cep.id = a.created_by
					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = a.branch_id
					
					WHERE a.is_deleted = 0
					AND a.branch_id in ('.$this->currentUserBranchIds.')
					)  as a
					
				WHERE id != 0 ';
		
		// ADMIN CONDITION & RM Flow 
		if(($this->hierarchyMode==2) && $this->currentAccessControlId!=1){
			$totalGroupUsersCount = count($this->currentgroupUsers);
			if($totalGroupUsersCount>0){
				$query.= 'AND created_by in ('.implode(",",$this->currentgroupUsers).')';
			}
		}
		        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!=""){
					if($fieldName=="activityNo") {
						$query.=' AND activity_no REGEXP LCASE(replace("'.$fieldValue.'"," ","|"))';
					}else if($fieldName=="activityTypeId"){		
						$query.=' AND activity_type_id = "'.$fieldValue.'"';					   
					}
					else if($fieldName=="assignedToName"){
						$query.=' AND LCASE(CONCAT(assigned_to_code," ",assigned_to_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="priorityId"){
						$query.=' AND priority_id = "'.$fieldValue.'"';					   
					}
					else if($fieldName=="partnerName"){
						$query.=' AND LCASE(CONCAT(partner_code," ",partner_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="status"){
						$query.=' AND status = "'.$fieldValue.'"';					   
					}
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} 
					else if($fieldName=="startDateTime"){
						$query.=' AND DATE(start_date_time) = "'.$fieldValue.'"';
					}
					else if($fieldName=="fromDate"){
						$query.=' AND DATE(created_on) >= "'.$fieldValue.'"';
					}
					else if($fieldName=="toDate"){
						$query.=' AND DATE(created_on) <= "'.$fieldValue.'"';
					}
					else if($fieldName=="createdByName"){
						$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="branchName"){
						$query.=' AND LCASE(CONCAT(branch_code," ",branch_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('ACTIVITY')['columns_list'],$fieldName);

			if(!empty($fieldName)){
				$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
			}
			
        }else{
			$query.= ' ORDER BY updated_on desc';
		}
		
        
		// PAGINATION
		if (isset($tableProperties['first'])) {
			$offset = $tableProperties['first'];
			$limit  = $tableProperties['rows'];
		} else {
			$offset = 0;
			$limit  = $tableProperties['rows'];
		}
		
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		
		// CHECK HIRARACHY MODE
		if(($this->hierarchyMode==1) && ($this->currentAccessControlId!=1))
		{	// TO FIND THE DISTRIBUTION RULES RECORD
			$searchResultData  = processDistributionRulesData($searchResultData,$empDistributionRulesId);
			$totalRecords 	   = count($searchResultData);
		}else if(($this->hierarchyMode==1) && ($this->currentAccessControlId==1)){
			$totalRecords 					= count($searchResultData);
		}else if ($this->hierarchyMode==2){ // Hirarchy Mode -> 2 (Reporting Manager Flow)
			$totalRecords 					= count($searchResultData);
		}
		
		// DOWNLOAD BASED OPERATIONS 
		if(empty($downloadFlag)){	
			$searchResultSet				= getOffSetRecords($searchResultData,$offset,$limit);
		}else{
			$searchResultSet				= $searchResultData;
		}
				
		

		// PASS COMMON PARAMETERS
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 1;
		
		foreach($searchResultSet as $key => $value){
			
			// FRAME ALL THE INFO DATA
			$statusInfoDetails	= array();
			
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
			
			
			// DOCUMENT NUMBER DETAILS 
			$passDocumenNumbertData['documentNoId']   	= $value['document_id'];
			$passDocumenNumbertData['documentTypeId']   = $value['document_type_id'];
			$documentNumberDetails   	  				= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumenNumbertData));
			
			$documentNumberInfoValues = '';
			if(is_array($documentNumberDetails) && count($documentNumberDetails)>0){
					$documentNumberInfoValues	= $documentNumberDetails[0]['document_number'];
			}
			
			// GET BUSINESS PARTNER CONTACTS LIST 
			$getInfoData		 = array(
										'getBusinessPartnerContactsList' 	 => $value['bp_contacts_id'],
									);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			// BUSINESS PARTNER CONTACTS LIST 
			$businessPartnerContactsDetails	= $statusInfoDetails['bpContactsInfo'];
			
			$businessPartnerInfoValues = '';
			if(is_array($businessPartnerContactsDetails) && count($businessPartnerContactsDetails)>0){
					$businessPartnerInfoValues	= $businessPartnerContactsDetails[0]['contact_name'];
			}
			
			// SEARCH RESULTS DATA 
			$searchResultSet[$key]['document_number_info'] 			= $documentNumberDetails;
			$searchResultSet[$key]['document_number_info_values'] 	= $documentNumberInfoValues;
			$searchResultSet[$key]['distribution_rules_info'] 		= $distributionRulesDetails;	
			$searchResultSet[$key]['distribution_rules_values'] 	= rtrim($distributionRulesValues,",");
			$searchResultSet[$key]['business_partner_contacts_info']		= $businessPartnerContactsDetails;
			$searchResultSet[$key]['business_partner_contacts_info_values'] = $businessPartnerInfoValues;
			
			// PHOTO URL
			$searchResultSet[$key]['assigned_to_img_url'] 	= getFullImgUrl('employee',$value['profile_img']);
		}
		
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	

	/**
	* @METHOD NAME 	: getAnalyticsCount()
	*
	* @DESC 		: TO ANALYTICS COUNT FOR ACTIVITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAnalyticsCount($statusId)
    {
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
		
		// SELECT QUERY 
		$this->app_db->select(array('id','distribution_rules_id'));
		$this->app_db->from(ACTIVITY);	
		
			if(!empty($statusId)){
				$this->app_db->where('status',$statusId);
			}
			
		$this->app_db->where('is_deleted',0);
		$this->app_db->where_in(ACTIVITY.'.branch_id', $this->currentUserBranchIds);
		
		
		// ADMIN CONDITION 
		if(($this->hierarchyMode==2) && ($this->currentAccessControlId!=1)){			
			$this->app_db->where_in(ACTIVITY.'.created_by', $this->currentgroupUsers,false);
		}
		
		$rs = $this->app_db->get();
		$searchResultData =  $rs->result_array();	
		
		
		if($this->hierarchyMode==1){		
				// CHECK HIRARACHY MODE
				if($this->currentAccessControlId!=1)
				{	// TO FIND THE DISTRIBUTION RULES RECORD
					$searchResultData  = processDistributionRulesData($searchResultData,$empDistributionRulesId);
				}
		}
		$totalRecords 		=  count($searchResultData);
		return $totalRecords;
    }
	
}
?>