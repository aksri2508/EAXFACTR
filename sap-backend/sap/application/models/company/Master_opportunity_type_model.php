<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_opportunity_type_model.php
* @Class  			 : Master_opportunity_type_model
* Model Name         : Master_opportunity_type_model
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
class Master_opportunity_type_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_OPPORTUNITY_TYPE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveOpportunityType()
	*
	* @DESC 		: TO SAVE THE OPPORTUNITY TYPE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunityType($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(type_description)' => strtolower($getPostData['typeDescription']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_OPPORTUNITY_TYPE,$whereExistsQry);
		
        if (0 == $chkRecord) {			
			$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateOpportunityType()
	*
	* @DESC 		: TO UPDATE THE Opportunity Type
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunityType($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(type_description)' => strtolower($getPostData['typeDescription']),
								 'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_OPPORTUNITY_TYPE,$whereExistsQry);
		               
        if(0 == $totRows) {		
			$whereQry = array('id'=>$getPostData['id']);
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editOpportunityType()
	*
	* @DESC 		: TO EDIT THE OPPORTUNITY TYPE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editOpportunityType($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
       // $this->app_db->select(array('id','type_description','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_OPPORTUNITY_TYPE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getOpportunityTypeList()
	*
	* @DESC 		: TO GET THE OPPORTUNITY TYPE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getOpportunityTypeList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
										MASTER_OPPORTUNITY_TYPE.'.id',
										MASTER_OPPORTUNITY_TYPE.'.type_description',
										MASTER_OPPORTUNITY_TYPE.'.status',
										MASTER_OPPORTUNITY_TYPE.'.posting_status',
										MASTER_OPPORTUNITY_TYPE.'.sap_id',
										MASTER_OPPORTUNITY_TYPE.'.sap_error',
										MASTER_STATIC_DATA.'.name as statusName'
							));
							
        $this->app_db->from(MASTER_OPPORTUNITY_TYPE);
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_OPPORTUNITY_TYPE.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_OPPORTUNITY_TYPE.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="typeDescription") {
						$this->app_db->like('LCASE(type_description)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_OPPORTUNITY_TYPE.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_OPPORTUNITY_TYPE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_OPPORTUNITY_TYPE.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_OPPORTUNITY_TYPE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_OPPORTUNITY_TYPE.'.updated_on', 'desc');
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