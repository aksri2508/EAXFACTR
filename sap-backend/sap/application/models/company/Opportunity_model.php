<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Opportunity_model.php
* @Class  			 : Opportunity_model
* Model Name         : Opportunity_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Opportunity_model extends CI_Model
{    
    public function __construct()
    {
		parent::__construct();
		$this->tableNameStr 			= 'OPPORTUNITY';
		$this->itemTableNameStr 		= 'OPPORTUNITY_STAGES';
		$this->itemTableColumnRef		= 'opportunity_id';
		$this->itemTableColumnReqRef 	= 'opportunityId';
		$this->tableName 				= constant($this->tableNameStr);
		$this->itemTableName 			= constant($this->itemTableNameStr);
	}
	
	
	/**
	* @METHOD NAME 	: saveOpportunity()
	*
	* @DESC 		: TO SAVE THE OPPORTUINITY DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunity($getPostData)
    {
		// Sub-Array Formation 
		$getListData         = $getPostData['stageListArray'];
		
        // Adding Transaction Start
        $this->app_db->trans_start();
		
		$rowData 							= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$rowData['branch_id'] 				= $this->currentbranchId;
		$rowData['distribution_rules_id']	= arrangeDistributionRulesData($getPostData['distributionRulesId']);
		$insertId 							= $this->commonModel->insertQry($this->tableName, $rowData);
		
		if ($insertId > 0) {
				$whereQry					   = array('id'=>$insertId);	
				$updateData['opportunity_no']  = $insertId;
				$this->commonModel->updateQry($this->tableName,$updateData,$whereQry);
				
				foreach ($getListData as $key => $value) {
					$value[$this->itemTableColumnReqRef] = $insertId;
					$this->saveOpportunityStages($value);
				}
			   $this->app_db->trans_complete(); // TRANSACTION COMPLETE
		}else {
			$modelOutput['flag'] = 2; // Failure
		}
	
		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1; // Success
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: saveOpportunityStages()
	*
	* @DESC 		: TO SAVE THE OPPORTUINITY STAGES DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunityStages($getPostData)
    {
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {

			$rowData							= bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId							= $this->commonModel->insertQry($this->itemTableName, $rowData);
			
			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
    
    
	/**
	* @METHOD NAME 	: updateOpportunity()
	*
	* @DESC 		: TO UPDATE THE OPPORTUNITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunity($getPostData)
    {
		$deletedStageChildIds = $getPostData['deletedStageChildIds'];
        $id       		 = $getPostData['id'];
		$getListData     = $getPostData['stageListArray'];
		
		// Adding Transaction Start
		$this->app_db->trans_start();
			$whereQry 							= array('id'=>$id);
			$rowData 							= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$rowData['branch_id'] 				= $this->currentbranchId;
			$rowData['distribution_rules_id']	= arrangeDistributionRulesData($getPostData['distributionRulesId']);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
           
		// PROCESS STAGES 
			// DELETE OPERATION
			if (count($deletedStageChildIds) > 0) { // Child values
				foreach ($deletedStageChildIds as $key => $value) {
					$passStageId  = array('id' => $value);
					$this->deleteOpportunityStages($passStageId);
				}
			}

			// List Data 
			foreach ($getListData as $key => $value) {
				// CHECK WHETHER DATA ALREADY EXISTS IN TABLE
				$whereExistsQry = array(
									'stage_id' => strtolower(trim($value['stageId'])),
								  );
				
				$value[$this->itemTableColumnReqRef] = $id;	// opportunity_id
				
				if (empty($value['id'])) { // INSERT THE RECORD 
					$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $value);
	
					$rowData[$this->itemTableColumnRef] = $id;
	
					$this->commonModel->insertQry($this->itemTableName, $rowData);
					
				} else {
					$value['id'] = $value['id'];
					$this->updateOpportunityStages($value);
				}
			}
			
		
			// To Complete the Transaction
			$this->app_db->trans_complete();

			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
        return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: updateOpportunityStages()
	*
	* @DESC 		: TO UPDATE THE OPPORTUINITY STAGES 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunityStages($getPostData)
    {
		
		$whereQry = array('id' => $getPostData['id']);
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success

		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: deleteOpportunityStages()
	*
	* @DESC 		: TO DELETE THE OPPORTUNITY STAGES
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteOpportunityStages($getPostData)
    {
		
		// DELETE IN OPPORTUNITY STAGES TABLE
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry($this->itemTableName, $whereQry);
		
		$modelOutput['flag'] = 1; // Success
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: editOpportunity()
	*
	* @DESC 		: TO EDIT THE OPPORTUNITY
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editOpportunity($getPostData)
    {	
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from(OPPORTUNITY);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
		$this->app_db->where_in('branch_id', explode(",",$this->currentUserBranchIds));
        $rs = $this->app_db->get();
		return  $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: getOpportunityStagesList()
	*
	* @DESC 		: TO GET THE OPPORTUNITY STAGES LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getOpportunityStagesList($getPostData){
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
		$this->app_db->from($this->itemTableName);
		$this->app_db->where($this->itemTableColumnRef, $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
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
    public function getAnalyticsCount($statusId)
    {
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
		
		$this->app_db->select(array('id','distribution_rules_id'));
		$this->app_db->from(OPPORTUNITY);	
		
			if(!empty($statusId)){
				$this->app_db->where('opportunity_status',$statusId);
			}
			
		$this->app_db->where('is_deleted',0);
		$this->app_db->where_in(OPPORTUNITY.'.branch_id', $this->currentUserBranchIds);		
		
		// ADMIN CONDITION & RM Flow 
		if(($this->hierarchyMode==2) && $this->currentAccessControlId!=1){
			$totalGroupUsersCount = count($this->currentgroupUsers);
			if($totalGroupUsersCount>0){
				$this->app_db->where_in(OPPORTUNITY.'.created_by', $this->currentgroupUsers,false);				
			}
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
	
	
    /**
	* @METHOD NAME 	: getOpportunityList()
	*
	* @DESC 		: TO GET THE OPPORTUNITY LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getOpportunityList($getPostData,$downloadFlag='')
    {	
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
		
		$query = 'SELECT * FROM
		
					(SELECT
						o.id,
						o.opportunity_no,
						o.opportunity_name,
						o.opportunity_type_id,
						o.business_partner_id,
						o.bp_contacts_id,
						o.emp_id,						
						o.start_date,
						o.closing_date,
						o.potential_amount,
						o.level_of_interest_id,
						o.industry_id,
						o.information_source_id,
						o.competitor_id,
						o.opportunity_status,
						o.reason_id,
						o.remarks,
						o.branch_id,
						o.distribution_rules_id,
						o.created_by,
						o.created_on,
						o.updated_on,
						
						bp.partner_name,
						bp.partner_code,
						o.sap_id,
						o.sap_error,
						o.posting_status,
						
					    CONCAT(cep.first_name," ",cep.last_name) as created_by_name,
						
						/* Master of level of interest */
						mlit.interest_name as level_of_interest_name,
						
						/* Master industry */
						mi.industry_name,
						
						/* Master Competitor */
						mc.competitor_name,
						
						/* MASTER OF INFORMATION SOURCE */
						mis.source_description as information_source_name,
						
						/* MASTER REASON */
						mr.reason_description as reason_name,
						
						/* MASTER OPPORTUINITY TYPE */
						mot.type_description AS opportunity_type_name,
						
						ep.emp_code,
						CONCAT(ep.first_name," ",ep.last_name) as emp_name,
						ep.profile_img,
						
						/* BRANCH INFORMATION */
						mb.branch_code,
						mb.branch_name
						
					FROM '.OPPORTUNITY.' as o
					
					LEFT JOIN '.EMPLOYEE_PROFILE.' as ep 
						ON ep.id = o.emp_id
					
					LEFT JOIN '.MASTER_INDUSTRY.' as mi 
						ON mi.id = o.industry_id
					
					LEFT JOIN '.MASTER_INFORMATION_SOURCE.' as mis 
						ON mis.id = o.information_source_id
					
					LEFT JOIN '.MASTER_LEVEL_OF_INTEREST.' as mlit
						ON mlit.id = o.level_of_interest_id
						
					LEFT JOIN '.BUSINESS_PARTNER.' as bp
						ON bp.id = o.business_partner_id
					
					LEFT JOIN '.MASTER_COMPETITOR.' as mc
						ON mc.id = o.competitor_id
					
					LEFT JOIN '.MASTER_REASON.' as mr
						ON mr.id = o.reason_id
					
					LEFT JOIN '.MASTER_OPPORTUNITY_TYPE.' as mot
						ON mot.id = o.opportunity_type_id
					LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
						ON cep.id = o.created_by
					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = o.branch_id
					
					where o.is_deleted = 0
					and o.branch_id in ('.$this->currentUserBranchIds.')) as a
				WHERE id != 0 ';
				
		
		// ADMIN CONDITION & RM Flow 
		if(($this->hierarchyMode==2) && $this->currentAccessControlId!=1){
			//$query.= ' created_by in ('.$this->currentgroupUsers.')';
			$query.= 'AND created_by in ('.implode(",",$this->currentgroupUsers).')';
		}
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="opportunityNo") {
						$query.=' AND LCASE(opportunity_no) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="opportunityName"){
						$query.=' AND LCASE(opportunity_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="partnerName"){
						$query.=' AND LCASE(CONCAT(partner_code," ",partner_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="empName"){
						$query.=' AND LCASE(CONCAT(emp_code," ",emp_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="startDate"){
						$query.=' AND DATE(start_date) = "'.$fieldValue.'"';
					}
					else if($fieldName=="closingDate"){
						$query.=' AND DATE(closing_date) = "'.$fieldValue.'"';
					}
					else if($fieldName=="potentialAmount"){
						$query.=' AND LCASE(potential_amount) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="levelOfInterestId"){
						$query.=' AND level_of_interest_id = "'.$fieldValue.'"';
					}
					else if($fieldName=="opportunityStatus"){
						  $query.=' AND opportunity_status = "'.$fieldValue.'"';
					}
					else if($fieldName=="fromDate"){
						$query.=' AND DATE(created_on) >= "'.$fieldValue.'"';
					}
					else if($fieldName=="toDate"){
						$query.=' AND DATE(created_on) <= "'.$fieldValue.'"';
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
			$fieldName	   = getListingParams($this->config->item('OPPORTUNITY')['columns_list'],$fieldName);
				
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
		}else if ($this->hierarchyMode==2){ // Hirarchy Mode -> 2 (Reporting manager flow)
			$totalRecords 					= count($searchResultData);
		}
		
		// DOWNLOAD BASED OPERATIONS 
		if(empty($downloadFlag)){	
			$searchResultSet				= getOffSetRecords($searchResultData,$offset,$limit);
		}else{
			$searchResultSet				= $searchResultData;
		}
		
		
		//GET ALL DATA FROM THE TABLE 
		$passType['type'] 	  	= 'OPPORTUNITY_STATUS';
		$opportunityStatusList  = $this->commonModel->getMasterStaticDataAutoList($passType,2);
		
		$passType['type'] 	  	= 'THREAD_LEVEL';
		$threadLevelList  		= $this->commonModel->getMasterStaticDataAutoList($passType,2);
		
		//printr($searchResultSet);
		
		foreach($searchResultSet as $key => $value){
			$opportunityStatusId 	= array_search($value['opportunity_status'], array_column($opportunityStatusList, 'id'));
			$statusName = "";
			
			if($opportunityStatusId !== false){
				$statusName = $opportunityStatusList[$opportunityStatusId]['name'];
			}
		
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
			
			// GET BUSINESS PARTNER CONTACTS LIST 
			$getInfoData		 = array(
										'getBusinessPartnerContactsList' 	=> $value['bp_contacts_id'],
										'getCompetitorList' 				=> $value['competitor_id'],
									);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			// BUSINESS PARTNER CONTACTS LIST 
			$businessPartnerContactsDetails	= $statusInfoDetails['bpContactsInfo'];
			
			$businessPartnerInfoValues = '';
			if(is_array($businessPartnerContactsDetails) && count($businessPartnerContactsDetails)>0){
					$businessPartnerInfoValues	= $businessPartnerContactsDetails[0]['contact_name'];
			}
			
			
			// GET THREAD NAME		
			$threadLevelName = "";
			if($value['competitor_id']!=0){
				// COMPETITOR Details
				$competitorDetails   	   = $statusInfoDetails['competitorInfo'];
								
				$threadLevelId		= array_search($competitorDetails[0]['threat_level_id'], array_column($threadLevelList, 'id'));						
				$threadLevelName 	= $threadLevelList[$threadLevelId]['name'];
			}
			
			// SEARCH RESULT DATA 
			$searchResultSet[$key]['distribution_rules_info'] 	= $distributionRulesDetails;	
			$searchResultSet[$key]['distribution_rules_values'] 	= rtrim($distributionRulesValues,",");
			$searchResultSet[$key]['emp_img_url'] 				= getFullImgUrl('employee',$value['profile_img']);
			$searchResultSet[$key]['status_name'] 				= $statusName;
			$searchResultSet[$key]['business_partner_contacts_info'] = $businessPartnerContactsDetails;
			$searchResultSet[$key]['business_partner_contacts_info_values'] = $businessPartnerInfoValues;
			$searchResultSet[$key]['thread_level_name'] = $threadLevelName;

		}
		
		
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
}
?>
