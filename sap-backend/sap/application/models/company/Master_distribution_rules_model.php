<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_distribution_rules_model.php
* @Class  			 : Master_distribution_rules_model
* Model Name         : Master_distribution_rules_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 11 MAY 2018
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_distribution_rules_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr 		= 'MASTER_DISTRIBUTION_RULES';
		$this->subTableNameStr		= 'MASTER_COST_CENTER';
		$this->tableName			= constant($this->tableNameStr);
		$this->subTableName 		= constant($this->subTableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveDistributionRules()
	*
	* @DESC 		: TO SAVE THE DISTRIBUTION RULE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveDistributionRules($getPostData)
    {	
	
		//printr($getPostData);
		
		
		// MASTER DISTRUBUTION RULES TABLE 
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
								'LCASE(distribution_name)' => strtolower($getPostData['distributionName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_DISTRIBUTION_RULES,$whereExistsQry);
		
        if (0 == $chkRecord) {						
			
			// DISTRUBUTION TABLE 
			$insertId 		 = $this->commonModel->insertQry($this->tableName, $rowData);
			
			$centerData		 = array(
									'centerCode'	   		=> $getPostData['distributionCode'],
									'centerName'			=> $getPostData['distributionName'],
									'distributionId'		=> $insertId,
									'empId' 				=> $getPostData['empId'],
									'sortCode'				=> $getPostData['sortCode'],
									'dimensionId' 			=> $getPostData['dimensionId'],
									'effectiveFrom' 		=> $getPostData['effectiveFrom'],
									'effectiveTo' 			=> $getPostData['effectiveTo'],
									'status'				=> $getPostData['status'],
							);
							
			// SAVE TO COST CENTER 
			$costCenterData  		= bindConfigTableValues($this->subTableNameStr, 'CREATE', $centerData);
			$insertId 		 		= $this->commonModel->insertQry($this->subTableName, $costCenterData);
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateDistributionRules()
	*
	* @DESC 		: TO UPDATE THE DISTRIBUTION RULES
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateDistributionRules($getPostData)
    {
		$whereExistsQry = array(
								'LCASE(distribution_name)' 	 => strtolower($getPostData['distributionName']),
								'id!='						 => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_DISTRIBUTION_RULES,$whereExistsQry);
		               
        if(0 == $totRows) {	
		
			// UPDATE IN DISTRIBUTION RULES TABLE
			
			$whereQry = array('id'=>$getPostData['id']);	
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		
			// UPDATE IN COST CENTER TABLE
			$whereQry 							= array('distribution_id'=>$getPostData['id']);
			$centerData							= array(
													'centerCode'	   		=> $getPostData['distributionCode'],
													'centerName'			=> $getPostData['distributionName'],
													'distributionId'		=> $getPostData['id'],
													'empId' 				=> $getPostData['empId'],
													'sortCode'				=> $getPostData['sortCode'],
													'dimensionId' 			=> $getPostData['dimensionId'],
													'effectiveFrom' 		=> $getPostData['effectiveFrom'],
													'effectiveTo' 			=> $getPostData['effectiveTo'],
													'status'				=> $getPostData['status'],
											);
							
			// SAVE TO COST CENTER 
			$costCenterData  		= bindConfigTableValues($this->subTableNameStr, 'UPDATE', $centerData);
			$this->commonModel->updateQry($this->subTableName, $costCenterData, $whereQry);
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 3;
        }
        return $modelOutput;
    }
   
	
    /**
	* @METHOD NAME 	: editDistributionRules()
	*
	* @DESC 		: TO EDIT THE DISTRIBUTION RULES
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editDistributionRules($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
        $this->app_db->from(MASTER_DISTRIBUTION_RULES);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getDistributionRulesList()
	*
	* @DESC 		: TO GET THE DISTRIBUTION RULE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getDistributionRulesList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
										MASTER_DISTRIBUTION_RULES.'.id',
										MASTER_DISTRIBUTION_RULES.'.distribution_code',
										MASTER_DISTRIBUTION_RULES.'.distribution_name',
										MASTER_DISTRIBUTION_RULES.'.emp_id',
										MASTER_DISTRIBUTION_RULES.'.sort_code',
										MASTER_DISTRIBUTION_RULES.'.dimension_id',
										MASTER_DISTRIBUTION_RULES.'.effective_from',
										MASTER_DISTRIBUTION_RULES.'.effective_to',
										MASTER_DISTRIBUTION_RULES.'.status',
										MASTER_DISTRIBUTION_RULES.'.posting_status',
										MASTER_DISTRIBUTION_RULES.'.sap_id',
										MASTER_DISTRIBUTION_RULES.'.sap_error',										
										MASTER_DIMENSION.'.dimension_name',
										'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name',
										MASTER_STATIC_DATA.'.name as statusName'
							));
							
        $this->app_db->from(MASTER_DISTRIBUTION_RULES);
		
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.MASTER_DISTRIBUTION_RULES.'.emp_id', 'left');

		$this->app_db->join(MASTER_DIMENSION, MASTER_DIMENSION.'.id = '.MASTER_DISTRIBUTION_RULES.'.dimension_id', 'left');
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_DISTRIBUTION_RULES.'.status', '');
		
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_DISTRIBUTION_RULES.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="distributionCode") {
						$this->app_db->like('LCASE(distribution_code)', strtolower($fieldValue));
					}else if($fieldName=="distributionName"){
						$this->app_db->like('LCASE(distribution_name)', strtolower($fieldValue));
					}else if($fieldName=="dimensionName"){
						$this->app_db->like('LCASE(dimension_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
				// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_DISTRIBUTION_RULES')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_DISTRIBUTION_RULES.'.updated_on', 'desc');
		}
        
        // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
        $tempdb       = clone $this->app_db;
        $totalRecords = $tempdb->count_all_results();
        
        // PAGINATION
        if (isset($tableProperties['first'])) {
            $offset = $tableProperties['first'];
            $limit  = $tableProperties['rows'];
        } else {
            $offset = 0;
            $limit  = $tableProperties['rows'];
        }
        $this->app_db->limit($limit, $offset);
        
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
        
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
}
?>